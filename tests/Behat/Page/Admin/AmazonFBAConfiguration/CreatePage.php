<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Page\Admin\AmazonFBAConfiguration;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Behaviour\ContainsErrorTrait;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsErrorTrait;

    public function fillCode(string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }

    public function fillClientId(string $clientId): void
    {
        $this->getDocument()->fillField('Client ID', $clientId);
    }

    public function fillClientSecret(string $clientSecret): void
    {
        $this->getDocument()->fillField('Client secret', $clientSecret);
    }

    public function fillRefreshToken(string $refreshToken): void
    {
        $this->getDocument()->fillField('Refresh token', $refreshToken);
    }
}
