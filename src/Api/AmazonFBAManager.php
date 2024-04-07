<?php

/*
 * This file is part of the Odiseo Amazon FBA Plugin package, a commercial software.
 * Only users who have purchased a valid license and accept to the terms of the License Agreement can install
 * and use this program.
 *
 * Copyright (c) 2018-2024 - Pablo D'amico
 */

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Api;

use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class AmazonFBAManager
{
    private array $rates = [];

    public function __construct(
        private AmazonFBAClient $amazonFBAClient,
        private LoggerInterface $logger,
    ) {
    }

    public function getRates(OrderInterface $order): array
    {
        if (count($this->rates) > 0) {
            return $this->rates;
        }

        try {
            $bodyRequest = $this->buildFBAOrderPreviewBodyRequest($order);

            $response = $this->amazonFBAClient->request(
                'fba/outbound/2020-07-01/fulfillmentOrders/preview',
                'POST',
                $bodyRequest,
            );
        } catch (ClientException $e) {
            /** @var array $arrayResponse */
            $arrayResponse = json_decode((string) $e->getResponse()?->getBody(), true);

            $this->logger->debug('Problem getting rates.');

            $error =  $arrayResponse['errors'][0]['message'] ?? null;
            if (null !== $error) {
                $this->logger->debug($error);
            }

            return [];
        } catch (\Exception $e) {
            $this->logger->debug('Problem getting rates.');
            $this->logger->debug($e->getMessage());

            return [];
        }

        $servicesRates = [];

        /** @var array $arrayResponse */
        $arrayResponse = json_decode($response->getBody()->getContents(), true);

        $FBAPreviews = $arrayResponse['payload']['fulfillmentPreviews'] ?? [];
        foreach ($FBAPreviews as $FBAPreview) {
            if ($FBAPreview['isFulfillable'] === false) {
                continue;
            }

            $FBAPerOrderFulfillmentFeeIndex = array_search(
                'FBAPerOrderFulfillmentFee',
                array_column($FBAPreview['estimatedFees'], 'name'),
                true,
            );

            $earliestArrivalDate = $FBAPreview['fulfillmentPreviewShipments'][0]['earliestArrivalDate'];
            $earliestArrivalDate = (new \DateTime($earliestArrivalDate))->format('Y-m-d');

            $latestArrivalDate = $FBAPreview['fulfillmentPreviewShipments'][0]['latestArrivalDate'];
            $latestArrivalDate = (new \DateTime($latestArrivalDate))->format('Y-m-d');

            if ($FBAPerOrderFulfillmentFeeIndex !== false) {
                $servicesRates[] = [
                    'shippingSpeedCategory' => $FBAPreview['shippingSpeedCategory'],
                    'amount' => $FBAPreview['estimatedFees'][$FBAPerOrderFulfillmentFeeIndex]['amount']['value'],
                    'earliestArrivalDate' => $earliestArrivalDate,
                    'latestArrivalDate' => $latestArrivalDate,
                ];
            }
        }

        if (count($servicesRates) > 0) {
            $this->rates = $servicesRates;
        }

        $this->logger->debug('No problem getting rates');

        return $servicesRates;
    }

    public function createFulfillmentOrders(
        OrderInterface $order,
        string $shippingSpeedCategory,
        bool $keepOnHold = true,
    ): void {
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        try {
            $bodyRequest = $this->buildFBAOrderBodyRequest($order, $channel, $shippingSpeedCategory, $keepOnHold);

            $this->amazonFBAClient->request(
                'fba/outbound/2020-07-01/fulfillmentOrders',
                'POST',
                $bodyRequest,
            );
        } catch (ClientException $e) {
            /** @var array $arrayResponse */
            $arrayResponse = json_decode((string) $e->getResponse()?->getBody(), true);

            $this->logger->debug('Problem creating order.');

            $error =  $arrayResponse['errors'][0]['message'] ?? null;
            if (null !== $error) {
                $this->logger->debug($error);
            }
        } catch (\Exception $e) {
            $this->logger->debug('Problem creating order.');
            $this->logger->debug($e->getMessage());
        }
    }

    public function confirmFulfillmentOrders(
        OrderInterface $order,
        string $shippingSpeedCategory,
        bool $keepOnHold = false,
    ): void {
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $id = ((string) $channel->getCode()) . '-' . ((string) $order->getId());

        try {
            $bodyRequest = $this->buildFBAOrderBodyRequest($order, $channel, $shippingSpeedCategory, $keepOnHold);

            $this->amazonFBAClient->request(
                'fba/outbound/2020-07-01/fulfillmentOrders/' . $id,
                'PUT',
                $bodyRequest,
            );
        } catch (ClientException $e) {
            /** @var array $arrayResponse */
            $arrayResponse = json_decode((string) $e->getResponse()?->getBody(), true);

            $this->logger->debug('Problem confirming order.');

            $error =  $arrayResponse['errors'][0]['message'] ?? null;
            if (null !== $error) {
                $this->logger->debug($error);
            }
        } catch (\Exception $e) {
            $this->logger->debug('Problem confirming order.');
            $this->logger->debug($e->getMessage());
        }
    }

    public function cancelFulfillmentOrders(
        OrderInterface $order,
    ): void {
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $id = ((string) $channel->getCode()) . '-' . ((string) $order->getId());

        try {
            $this->amazonFBAClient->request(
                '/fba/outbound/2020-07-01/fulfillmentOrders/' . $id . '/cancel',
                'PUT',
            );
        } catch (ClientException $e) {
            /** @var array $arrayResponse */
            $arrayResponse = json_decode((string) $e->getResponse()?->getBody(), true);

            $this->logger->debug('Problem cancelling order.');

            $error =  $arrayResponse['errors'][0]['message'] ?? null;
            if (null !== $error) {
                $this->logger->debug($error);
            }
        } catch (\Exception $e) {
            $this->logger->debug('Problem cancelling order.');
            $this->logger->debug($e->getMessage());
        }
    }

    public function getInventorySummaries(array $skus): array
    {
        try {
            $queryParameters = $this->buildFBAInventoryQueryParameters($skus);

            $response = $this->amazonFBAClient->query(
                '/fba/inventory/v1/summaries',
                $queryParameters,
            );
        } catch (ClientException $e) {
            /** @var array $arrayResponse */
            $arrayResponse = json_decode((string) $e->getResponse()?->getBody(), true);

            $this->logger->debug('Problem getting inventory.');

            $error =  $arrayResponse['errors'][0]['message'] ?? null;
            if (null !== $error) {
                $this->logger->debug($error);
            }

            return [];
        } catch (\Exception $e) {
            $this->logger->debug('Problem getting inventory.');
            $this->logger->debug($e->getMessage());

            return [];
        }

        /** @var array $arrayResponse */
        $arrayResponse = json_decode($response->getBody()->getContents(), true);

        return $arrayResponse['payload']['inventorySummaries'] ?? [];
    }

    private function buildFBAOrderPreviewBodyRequest(OrderInterface $order): array
    {
        $address = $order->getShippingAddress();
        if (null === $address) {
            return [];
        }

        $bodyItems = [];

        $items = $order->getItems();
        foreach ($items as $item) {
            $bodyItems[] = [
                'quantity' => $item->getQuantity(),
                'sellerFulfillmentOrderItemId' => $item->getId(),
                'sellerSku' => $item->getVariant()?->getCode(),
            ];
        }

        return [
            'address' => [
                'addressLine1' => $address->getStreet(),
                'city' => $address->getCity(),
                'countryCode' => $address->getCountryCode(),
                'name' => $address->getFullName(),
                'postalCode' => $address->getPostcode(),
                'email' => $order->getCustomer()?->getEmail(),
                'phoneNumber' => $address->getPhoneNumber(),
            ],
            'items' => $bodyItems,
            'marketplaceId' => $this->amazonFBAClient->getMarketplaceId(),
        ];
    }

    private function buildFBAOrderBodyRequest(
        OrderInterface $order,
        ChannelInterface $channel,
        string $shippingSpeedCategory,
        bool $keepOnHold = true,
    ): array {
        $address = $order->getShippingAddress();
        if (null === $address) {
            return [];
        }

        $bodyItems = [];

        $items = $order->getItems();
        foreach ($items as $item) {
            $bodyItems[] = [
                'quantity' => $item->getQuantity(),
                'sellerFulfillmentOrderItemId' => $item->getId(),
                'sellerSku' => $item->getVariant()?->getCode(),
            ];
        }

        $id = ((string) $channel->getCode()) . '-' . ((string) $order->getId());
        $comment = 'Order from ' . ((string) $channel->getCode()) . '. Order #' . ((string) $order->getNumber());

        return [
            'destinationAddress' => [
                'addressLine1' => $address->getStreet(),
                'city' => $address->getCity(),
                'countryCode' => $address->getCountryCode(),
                'name' => $address->getFullName(),
                'postalCode' => $address->getPostcode(),
                'email' => $order->getCustomer()?->getEmail(),
                'phoneNumber' => $address->getPhoneNumber(),
            ],
            'items' => $bodyItems,
            'marketplaceId' => $this->amazonFBAClient->getMarketplaceId(),
            'displayableOrderComment' => $comment,
            'displayableOrderDate' => $order->getCreatedAt()?->format('Y-m-d'),
            'displayableOrderId' => $id,
            'sellerFulfillmentOrderId' => $id,
            'shippingSpeedCategory' => $shippingSpeedCategory,
            'fulfillmentAction' => $keepOnHold ? 'Hold' : 'Ship',
            'notificationEmails' => [
                $order->getCustomer()?->getEmail(),
                $channel->getContactEmail(),
            ],
        ];
    }

    private function buildFBAInventoryQueryParameters(array $skus): array
    {
        return [
            'details' => 'true',
            'granularityType' => 'Marketplace',
            'granularityId' => $this->amazonFBAClient->getMarketplaceId(),
            'sellerSkus' => implode(',', $skus),
            'marketplaceIds' => $this->amazonFBAClient->getMarketplaceId(),
        ];
    }
}
