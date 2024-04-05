<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Provider;

use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Odiseo\SyliusAmazonFBAPlugin\Repository\AmazonFBAConfigurationRepositoryInterface;

final class EnabledAmazonFBAConfigurationProvider implements EnabledAmazonFBAConfigurationProviderInterface
{
    public function __construct(
        private AmazonFBAConfigurationRepositoryInterface $amazonFBAConfigurationRepository,
    ) {
    }

    public function getConfiguration(): ?AmazonFBAConfigurationInterface
    {
        return $this->amazonFBAConfigurationRepository->findOneByEnabled();
    }
}
