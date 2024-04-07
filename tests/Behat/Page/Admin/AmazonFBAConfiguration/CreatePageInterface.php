<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Page\Admin\AmazonFBAConfiguration;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;
use Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Behaviour\ContainsErrorInterface;

interface CreatePageInterface extends BaseCreatePageInterface, ContainsErrorInterface
{
    public function fillCode(string $code): void;

    public function fillClientId(string $clientId): void;

    public function fillClientSecret(string $clientSecret): void;

    public function fillRefreshToken(string $refreshToken): void;
}
