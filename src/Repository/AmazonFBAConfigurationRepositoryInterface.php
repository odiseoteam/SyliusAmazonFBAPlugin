<?php

/*
 * This file is part of the Odiseo Amazon FBA Plugin package, a commercial software.
 * Only users who have purchased a valid license and accept to the terms of the License Agreement can install
 * and use this program.
 *
 * Copyright (c) 2018-2024 - Pablo D'amico
 */

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Repository;

use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AmazonFBAConfigurationRepositoryInterface extends RepositoryInterface
{
    public function findOneByEnabled(): ?AmazonFBAConfigurationInterface;
}
