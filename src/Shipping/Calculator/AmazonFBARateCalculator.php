<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Shipping\Calculator;

use Odiseo\SyliusAmazonFBAPlugin\Api\AmazonFBAManager;
use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface as BaseShipmentInterface;

final class AmazonFBARateCalculator implements CalculatorInterface
{
    public function __construct(
        private AmazonFBAManager $amazonFBAManager,
    ) {
    }

    public function calculate(BaseShipmentInterface $subject, array $configuration): int
    {
        /** @var ShipmentInterface $shipment */
        $shipment = $subject;

        /** @var OrderInterface $order */
        $order = $shipment->getOrder();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();

        $channelCode = $channel->getCode();

        if (!isset($configuration[$channelCode])) {
            throw new MissingChannelConfigurationException(sprintf(
                'Channel %s has no configuration defined for shipping method %s',
                $channel->getName(),
                $shipment->getMethod()?->getName(),
            ));
        }

        $rates = $this->amazonFBAManager->getRates($order);

        foreach ($rates as $rate) {
            if ($rate['shippingSpeedCategory'] === $configuration[$channelCode]['speed_category']) {
                return (int) ($rate['amount'] * 100);
            }
        }

        return (int) $configuration[$channelCode]['default_amount'];
    }

    public function getType(): string
    {
        return 'amazon_fba_rate';
    }
}
