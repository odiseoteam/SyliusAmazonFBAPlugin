<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Api;

use GuzzleHttp\Exception\ClientException;
use Odiseo\SyliusAmazonFBAPlugin\Repository\ShippingMethodRepositoryInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;

final class AmazonFBAManager
{
    private array $rates = [];

    public function __construct(
        private string $marketplaceId,
        private AmazonFBAClient $amazonFBAClient,
        private ShippingMethodRepositoryInterface $shippingMethodRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function getRates(OrderInterface $order): array
    {
        $channel = $order->getChannel();

        $bodyRequest = $this->buildFBAOrderPreviewBodyRequest($order, $channel);

        if (!empty($this->rates)) {
            return $this->rates;
        }

        try {
            $response = $this->amazonFBAClient->request(
                'fba/outbound/2020-07-01/fulfillmentOrders/preview',
                'POST',
                $bodyRequest
            );
        } catch (ClientException $e) {
            $arrayResponse = json_decode((string) $e->getResponse()->getBody(), true);
            $this->logger->debug('Problem getting rates.');
            $errors = $arrayResponse['errors'] ?? [];
            if (!empty($errors) && !empty($errors[0]['message'])) {
                $this->logger->debug($errors[0]['message']);
            }

            return [];
        } catch (\Exception $e) {
            $this->logger->debug('Problem getting rates.');
            $this->logger->debug($e->getMessage());

            return [];
        }

        $arrayResponse = json_decode($response->getBody()->getContents(), true);
        $FBAPreviews = $arrayResponse['payload']['fulfillmentPreviews'];

        $servicesRates = [];
        foreach ($FBAPreviews as $FBAPreview) {
            if ($FBAPreview['isFulfillable'] === false) {
                continue;
            }
            $FBAPerOrderFulfillmentFeeIndex = array_search('FBAPerOrderFulfillmentFee', array_column($FBAPreview['estimatedFees'], 'name'));
            $earliestArrivalDate = $FBAPreview['fulfillmentPreviewShipments'][0]['earliestArrivalDate'];
            $earliestArrivalDate = (new \DateTime($earliestArrivalDate))->format('Y-m-d');
            $latestArrivalDate = $FBAPreview['fulfillmentPreviewShipments'][0]['latestArrivalDate'];
            $latestArrivalDate = (new \DateTime($latestArrivalDate))->format('Y-m-d');
            if ($FBAPerOrderFulfillmentFeeIndex !== false) {
                $servicesRates[] = [
                    'shippingSpeedCategory' => $FBAPreview['shippingSpeedCategory'],
                    'amount' => $FBAPreview['estimatedFees'][$FBAPerOrderFulfillmentFeeIndex]['amount']['value'],
                    'earliestArrivalDate' => $earliestArrivalDate,
                    'latestArrivalDate' => $latestArrivalDate
                ];
            }
        }

        if (!empty($servicesRates)) {
            $this->rates = $servicesRates;
        }

        $this->logger->debug('No problem getting rates');

        return $servicesRates;
    }

    public function createFulfillmentOrders(
        OrderInterface $order,
        string $shippingSpeedCategory = 'Standard',
        bool $keepOnHold = true,
    ): void {
        $channel = $order->getChannel();

        $bodyRequest = $this->buildFBAOrderBodyRequest($order, $channel, $shippingSpeedCategory, $keepOnHold);

        try {
            $this->amazonFBAClient->request(
                'fba/outbound/2020-07-01/fulfillmentOrders',
                'POST',
                $bodyRequest
            );
        } catch (ClientException $e) {
            $arrayResponse = json_decode((string) $e->getResponse()->getBody(), true);
            $this->logger->debug('Problem creating order.');
            $errors = $arrayResponse['errors'] ?? [];
            if (!empty($errors) && !empty($errors[0]['message'])) {
                $this->logger->debug($errors[0]['message']);;
            }
        } catch (\Exception $e) {
            $this->logger->debug('Problem creating order.');
            $this->logger->debug($e->getMessage());
        }
    }

    public function confirmFulfillmentOrders(
        OrderInterface $order,
        string $shippingSpeedCategory = 'Standard',
        bool $keepOnHold = false,
    ): void {
        $channel = $order->getChannel();

        $bodyRequest = $this->buildFBAOrderBodyRequest($order, $channel, $shippingSpeedCategory, $keepOnHold);

        try {
            $this->amazonFBAClient->request(
                'fba/outbound/2020-07-01/fulfillmentOrders/'.$channel->getCode().'-'.$order->getId(),
                'PUT',
                $bodyRequest
            );
        } catch (ClientException $e) {
            $arrayResponse = json_decode((string) $e->getResponse()->getBody(), true);
            $this->logger->debug('Problem confirming order.');
            $errors = $arrayResponse['errors'] ?? [];
            if (!empty($errors) && !empty($errors[0]['message'])) {
                $this->logger->debug($errors[0]['message']);;
            }
        } catch (\Exception $e) {
            $this->logger->debug('Problem confirming order.');
            $this->logger->debug($e->getMessage());
        }
    }

    public function cancelFulfillmentOrders(
        OrderInterface $order,
    ): void {
        $channel = $order->getChannel();

        try {
            $this->amazonFBAClient->request(
                '/fba/outbound/2020-07-01/fulfillmentOrders/'.$channel->getCode().'-'.$order->getId().'/cancel',
                'PUT',
            );
        } catch (ClientException $e) {
            $arrayResponse = json_decode((string) $e->getResponse()->getBody(), true);
            $this->logger->debug('Problem cancelling order.');
            $errors = $arrayResponse['errors'] ?? [];
            if (!empty($errors) && !empty($errors[0]['message'])) {
                $this->logger->debug($errors[0]['message']);;
            }
        } catch (\Exception $e) {
            $this->logger->debug('Problem cancelling order.');
            $this->logger->debug($e->getMessage());
        }
    }

    public function getInventorySummaries(array $skus): array
    {
        $queryParameters = $this->buildFBAInventoryQueryParameters($skus);

        $response = $this->amazonFBAClient->query(
            '/fba/inventory/v1/summaries',
            $queryParameters
        );

        $arrayResponse = json_decode((string) $response->getBody(), true);
        return $arrayResponse['payload']['inventorySummaries'] ?? [];
    }

    private function buildFBAOrderPreviewBodyRequest(OrderInterface $order, ChannelInterface $channel): array
    {
        $address = $order->getShippingAddress();
        if (null === $address) {
            return [];
        }

        $bodyItems = [];

        $items = $order->getItems();
        foreach ($items as $item) {
            if (!$item->getVariant()->isShippingRequired()) {
                continue;
            }
            $bodyItems[] = [
                'quantity' => $item->getQuantity(),
                'sellerFulfillmentOrderItemId' => $item->getId(),
                'sellerSku' => $item->getVariant()->getCode(),
            ];
        }

        $shippingMethods = $this->shippingMethodRepository->findByCalculatorEnabledForChannel($channel, 'amazon_fba_rate');

        $speedCategories = [];

        /** @var ShippingMethodInterface $shippingMethod */
        foreach ($shippingMethods as $shippingMethod) {
            if ($speedCategory = $shippingMethod->getConfiguration()[$channel->getCode()]['speed_category'] ?? null) {
                $speedCategories[] = $speedCategory;
            }
        }

        return [
            'address' => [
                'addressLine1' => $address->getStreet(),
                'city' => $address->getCity(),
                'countryCode' => $address->getCountryCode(),
                'name' => $address->getFullName(),
                'postalCode' => $address->getPostcode(),
                'email' => $channel->getContactEmail(),
                'phoneNumber' => $address->getPhoneNumber(),
            ],
            'items' => $bodyItems,
            'marketplaceId' => $this->marketplaceId,
            'shippingSpeedCategories' => $speedCategories
        ];
    }

    private function buildFBAOrderBodyRequest(
        OrderInterface $order,
        ChannelInterface $channel,
        string $shippingSpeedCategory,
        bool $keepOnHold = true,
    ): array {
        $bodyItems = [];
        $address = $order->getShippingAddress();
        $items = $order->getItems();
        foreach ($items as $item) {
            if (!$item->getVariant()->isShippingRequired()) {
                continue;
            }
            $bodyItems[] = [
                'quantity' => $item->getQuantity(),
                'sellerFulfillmentOrderItemId' => $item->getId(),
                'sellerSku' => $item->getVariant()->getCode(),
            ];
        }
        return [
            'destinationAddress' => [
                'addressLine1' => $address->getStreet(),
                'city' => $address->getCity(),
                'countryCode' => $address->getCountryCode(),
                'name' => $address->getFullName(),
                'postalCode' => $address->getPostcode(),
                'email' => $order->getCustomer()->getEmail(),
                'phoneNumber' => $address->getPhoneNumber()
            ],
            'displayableOrderComment' => 'Order from '.$channel->getCode().'. Order #'.$order->getNumber(),
            'displayableOrderDate' => $order->getCreatedAt()->format('Y-m-d'),
            'displayableOrderId' => $channel->getCode().'-'.$order->getId(),
            'items' => $bodyItems,
            'sellerFulfillmentOrderId' => $channel->getCode().'-'.$order->getId(),
            'shippingSpeedCategory' => $shippingSpeedCategory,
            'marketplaceId' => $this->marketplaceId,
            'fulfillmentAction' => $keepOnHold ? 'Hold' : 'Ship',
            'notificationEmails' => [
                $order->getCustomer()->getEmail(),
                $channel->getContactEmail(),
            ]
        ];
    }

    private function buildFBAInventoryQueryParameters(array $skus): array
    {
        return [
            'details' => 'true',
            'granularityType' => 'Marketplace',
            'granularityId' => $this->marketplaceId,
            'sellerSkus' => implode(',', $skus),
            'marketplaceIds' => $this->marketplaceId
        ];
    }
}
