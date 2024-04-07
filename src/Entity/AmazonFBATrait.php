<?php

/*
 * This file is part of the Odiseo Amazon FBA Plugin package, a commercial software.
 * Only users who have purchased a valid license and accept to the terms of the License Agreement can install
 * and use this program.
 *
 * Copyright (c) 2018-2024 - Pablo D'amico
 */

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
