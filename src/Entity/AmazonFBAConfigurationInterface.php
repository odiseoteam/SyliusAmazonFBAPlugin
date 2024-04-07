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

use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface AmazonFBAConfigurationInterface extends
    CodeAwareInterface,
    ResourceInterface,
    ToggleableInterface,
    TimestampableInterface
{
    public function getClientId(): ?string;

    public function setClientId(?string $clientId): void;

    public function getClientSecret(): ?string;

    public function setClientSecret(?string $clientSecret): void;

    public function getRefreshToken(): ?string;

    public function setRefreshToken(?string $refreshToken): void;

    public function getCountryCode(): ?string;

    public function setCountryCode(?string $countryCode): void;

    public function isSandbox(): bool;

    public function setSandbox(bool $sandbox): void;
}
