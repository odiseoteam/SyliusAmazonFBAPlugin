<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Fulfillment;

use Sylius\Component\Core\Model\ShipmentInterface;

interface OrderFulfillmentInterface
{
    public function createOrder(ShipmentInterface $shipment): void;

    public function confirmOrder(ShipmentInterface $shipment): void;

    public function cancelOrder(ShipmentInterface $shipment): void;
}
