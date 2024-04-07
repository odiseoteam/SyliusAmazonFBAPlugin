<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Page\Admin\AmazonFBAConfiguration;

use Sylius\Behat\Page\Admin\Crud\IndexPage as BaseIndexPage;

final class IndexPage extends BaseIndexPage implements IndexPageInterface
{
    public function deleteConfiguration(string $code): void
    {
        $this->deleteResourceOnPage(['code' => $code]);
    }
}
