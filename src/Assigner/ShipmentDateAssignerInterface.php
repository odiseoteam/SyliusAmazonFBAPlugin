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

use Sylius\Component\Core\Model\ShipmentInterface;

interface ShipmentDateAssignerInterface
{
    public function assignDate(ShipmentInterface $shipment): void;
}
