<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Entity;

trait AmazonFBATrait
{
    protected ?\DateTimeInterface $earliestArrivalDate = null;

    protected ?\DateTimeInterface $latestArrivalDate = null;

    public function getEarliestArrivalDate(): ?\DateTimeInterface
    {
        return $this->earliestArrivalDate;
    }

    public function setEarliestArrivalDate(?\DateTimeInterface $earliestArrivalDate): void
    {
        $this->earliestArrivalDate = $earliestArrivalDate;
    }

    public function getLatestArrivalDate(): ?\DateTimeInterface
    {
        return $this->latestArrivalDate;
    }

    public function setLatestArrivalDate(?\DateTimeInterface $latestArrivalDate): void
    {
        $this->latestArrivalDate = $latestArrivalDate;
    }
}
