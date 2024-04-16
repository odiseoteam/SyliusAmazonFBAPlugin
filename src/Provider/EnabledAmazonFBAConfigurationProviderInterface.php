<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Provider;

use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;

interface EnabledAmazonFBAConfigurationProviderInterface
{
    public function getConfiguration(): ?AmazonFBAConfigurationInterface;
}
