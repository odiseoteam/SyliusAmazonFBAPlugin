<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Repository;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Repository\ShippingMethodRepositoryInterface as BaseShippingMethodRepositoryInterface;

interface ShippingMethodRepositoryInterface extends BaseShippingMethodRepositoryInterface
{
    public function findByCalculatorEnabledForChannel(ChannelInterface $channel, string $calculator): array;
}
