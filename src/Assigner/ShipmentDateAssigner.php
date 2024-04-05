<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Assigner;

use Odiseo\SyliusAmazonFBAPlugin\Api\AmazonFBAManager;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAAwareInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

final class ShipmentDateAssigner implements ShipmentDateAssignerInterface
{
    public function __construct(
        private AmazonFBAManager $amazonFBAManager,
    ) {
    }

    public function assignDate(ShipmentInterface $shipment): void
    {
        if (!$shipment instanceof AmazonFBAAwareInterface) {
            return;
        }

        $order = $shipment->getOrder();
        $channel = $order->getChannel();

        $channelCode = $channel->getCode();

        $configuration = $shipment->getMethod()->getConfiguration();

        if (!isset($configuration[$channelCode])) {
            return;
        }

        $rates = $this->amazonFBAManager->getRates($order);

        foreach ($rates as $rate) {
            $speedCategory = $configuration[$channelCode]['speed_category'] ?? null;
            if ($speedCategory === $rate['shippingSpeedCategory']) {
                $shipment->setEarliestArrivalDate(new \DateTime($rate['earliestArrivalDate']));
                $shipment->setLatestArrivalDate(new \DateTime($rate['latestArrivalDate']));

                break;
            }
        }
    }
}
