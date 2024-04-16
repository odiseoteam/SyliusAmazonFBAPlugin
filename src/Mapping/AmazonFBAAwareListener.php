<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Mapping;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAAwareInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

final class AmazonFBAAwareListener implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $reflection = $classMetadata->reflClass;

        /**
         * @phpstan-ignore-next-line
         */
        if ($reflection === null || $reflection->isAbstract()) {
            return;
        }

        if (
            $reflection->implementsInterface(ShipmentInterface::class) &&
            $reflection->implementsInterface(AmazonFBAAwareInterface::class)
        ) {
            $this->mapAmazonFBAAware($classMetadata);
        }
    }

    private function mapAmazonFBAAware(ClassMetadata $metadata): void
    {
        if (!$metadata->hasField('earliestArrivalDate')) {
            $metadata->mapField([
                'fieldName' => 'earliestArrivalDate',
                'columnName' => 'earliest_arrival_date',
                'type' => 'datetime',
                'nullable' => true,
            ]);
        }
        if (!$metadata->hasField('latestArrivalDate')) {
            $metadata->mapField([
                'fieldName' => 'latestArrivalDate',
                'columnName' => 'latest_arrival_date',
                'type' => 'datetime',
                'nullable' => true,
            ]);
        }
    }
}
