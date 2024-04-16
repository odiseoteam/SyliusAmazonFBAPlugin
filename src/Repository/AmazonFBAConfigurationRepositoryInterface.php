<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Repository;

use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AmazonFBAConfigurationRepositoryInterface extends RepositoryInterface
{
    public function findOneByEnabled(): ?AmazonFBAConfigurationInterface;
}
