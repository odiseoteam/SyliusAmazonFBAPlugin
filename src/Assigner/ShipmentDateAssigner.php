<?php

/*
 * This file is part of the Odiseo Amazon FBA Plugin package, a commercial software.
 * Only users who have purchased a valid license and accept to the terms of the License Agreement can install
 * and use this program.
 *
 * Copyright (c) 2018-2024 - Pablo D'amico
 */

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Assigner;

use Odiseo\SyliusAmazonFBAPlugin\Api\AmazonFBAManager;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAAwareInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
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

        /** @var OrderInterface $order */
        $order = $shipment->getOrder();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $channelCode = $channel->getCode();

        $configuration = $shipment->getMethod()?->getConfiguration();

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
