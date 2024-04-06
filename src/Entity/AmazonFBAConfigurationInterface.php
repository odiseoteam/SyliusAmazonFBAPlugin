<?php

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

    public function isSandbox(): ?bool;

    public function setSandbox(?bool $sandbox): void;

    public function getCountryCode(): ?string;

    public function setCountryCode(?string $countryCode): void;
}
