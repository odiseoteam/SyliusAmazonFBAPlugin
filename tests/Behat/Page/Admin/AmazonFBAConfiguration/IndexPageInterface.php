<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Page\Admin\AmazonFBAConfiguration;

use Sylius\Behat\Page\Admin\Crud\IndexPageInterface as BaseIndexPageInterface;

interface IndexPageInterface extends BaseIndexPageInterface
{
    public function deleteConfiguration(string $code): void;
}
