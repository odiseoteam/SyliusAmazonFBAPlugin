<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Repository;

use Sylius\Component\Core\Model\ChannelInterface;

trait ShippingMethodRepositoryTrait
{
    public function findByCalculatorEnabledForChannel(ChannelInterface $channel, string $calculator): array
    {
        return $this->createEnabledForChannelQueryBuilder($channel)
            ->andWhere('o.calculator = :calculator')
            ->setParameter('calculator', $calculator)
            ->getQuery()
            ->getResult()
        ;
    }
}
