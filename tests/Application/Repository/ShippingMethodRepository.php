<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Application\Repository;

use Odiseo\SyliusAmazonFBAPlugin\Repository\ShippingMethodRepositoryInterface;
use Odiseo\SyliusAmazonFBAPlugin\Repository\ShippingMethodRepositoryTrait;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ShippingMethodRepository as BaseShippingMethodRepository;

class ShippingMethodRepository extends BaseShippingMethodRepository implements ShippingMethodRepositoryInterface
{
    use ShippingMethodRepositoryTrait;
}
