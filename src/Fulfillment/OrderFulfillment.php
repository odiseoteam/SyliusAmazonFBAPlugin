<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Fulfillment;

use Odiseo\SyliusAmazonFBAPlugin\Api\AmazonFBAManager;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

final class OrderFulfillment implements OrderFulfillmentInterface
{
    public function __construct(
        private AmazonFBAManager $amazonFBAManager,
    ) {
    }

    public function createOrder(ShipmentInterface $shipment): void
    {
        /** @var OrderInterface $order */
        $order = $shipment->getOrder();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $channelCode = $channel->getCode();

        $configuration = $shipment->getMethod()?->getConfiguration();

        if (!isset($configuration[$channelCode])) {
            return;
        }

        $speedCategory = $configuration[$channelCode]['speed_category'] ?? null;
        if (null !== $speedCategory) {
            $this->amazonFBAManager->createFulfillmentOrders($order, $speedCategory);
        }
    }

    public function confirmOrder(ShipmentInterface $shipment): void
    {
        /** @var OrderInterface $order */
        $order = $shipment->getOrder();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $channelCode = $channel->getCode();

        $configuration = $shipment->getMethod()?->getConfiguration();

        if (!isset($configuration[$channelCode])) {
            return;
        }

        $speedCategory = $configuration[$channelCode]['speed_category'] ?? null;
        if (null !== $speedCategory) {
            $this->amazonFBAManager->confirmFulfillmentOrders($order, $speedCategory);
        }
    }

    public function cancelOrder(ShipmentInterface $shipment): void
    {
        /** @var OrderInterface $order */
        $order = $shipment->getOrder();

        $this->amazonFBAManager->cancelFulfillmentOrders($order);
    }
}
