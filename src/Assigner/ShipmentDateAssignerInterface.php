<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Assigner;

use Sylius\Component\Core\Model\ShipmentInterface;

interface ShipmentDateAssignerInterface
{
    public function assignDate(ShipmentInterface $shipment): void;
}
