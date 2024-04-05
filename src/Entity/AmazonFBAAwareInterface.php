<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Entity;

interface AmazonFBAAwareInterface
{
    public function getEarliestArrivalDate(): ?\DateTimeInterface;

    public function setEarliestArrivalDate(?\DateTimeInterface $earliestArrivalDate): void;

    public function getLatestArrivalDate(): ?\DateTimeInterface;

    public function setLatestArrivalDate(?\DateTimeInterface $latestArrivalDate): void;
}
