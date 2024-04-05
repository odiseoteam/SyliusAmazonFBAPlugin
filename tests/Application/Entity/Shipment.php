<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAAwareInterface;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBATrait;
use Sylius\Component\Core\Model\Shipment as BaseShipment;

/**
 * @ORM\Table(name="sylius_shipment")
 * @ORM\Entity
 */
class Shipment extends BaseShipment implements AmazonFBAAwareInterface
{
    use AmazonFBATrait;
}
