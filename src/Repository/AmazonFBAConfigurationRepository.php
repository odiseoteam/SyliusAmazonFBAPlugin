<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Repository;

use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AmazonFBAConfigurationRepository extends EntityRepository implements AmazonFBAConfigurationRepositoryInterface
{
    public function findOneByEnabled(): ?AmazonFBAConfigurationInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.enabled = :enabled')
            ->setParameter('enabled', true)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
