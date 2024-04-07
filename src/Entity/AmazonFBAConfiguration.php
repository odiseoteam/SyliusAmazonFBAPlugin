<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class AmazonFBAConfiguration implements AmazonFBAConfigurationInterface
{
    use TimestampableTrait;
    use ToggleableTrait;

    protected ?int $id = null;

    protected ?string $code = null;

    protected ?string $clientId = null;

    protected ?string $clientSecret = null;

    protected ?string $refreshToken = null;

    protected ?string $countryCode = null;

    protected bool $sandbox = true;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(?string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(?string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function setSandbox(bool $sandbox): void
    {
        $this->sandbox = $sandbox;
    }
}
