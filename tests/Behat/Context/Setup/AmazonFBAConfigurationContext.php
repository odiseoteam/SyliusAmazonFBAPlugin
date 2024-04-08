<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Odiseo\SyliusAmazonFBAPlugin\Repository\AmazonFBAConfigurationRepositoryInterface;
use Odiseo\SyliusAmazonFBAPlugin\Utils\Countries;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AmazonFBAConfigurationContext implements Context
{
    public function __construct(
        private FactoryInterface $amazonFBAConfigurationFactory,
        private AmazonFBAConfigurationRepositoryInterface $amazonFBAConfigurationRepository,
    ) {
    }

    /**
     * @Given there is an existing Amazon FBA configuration with :code code
     */
    public function thereIsAConfigurationWithCode(string $code): void
    {
        $configuration = $this->createConfiguration($code);

        $this->saveConfiguration($configuration);
    }

    private function createConfiguration(string $code): AmazonFBAConfigurationInterface
    {
        /** @var AmazonFBAConfigurationInterface $configuration */
        $configuration = $this->amazonFBAConfigurationFactory->createNew();

        $configuration->setCode($code);
        $configuration->setEnabled(true);
        $configuration->setSandbox(true);
        $configuration->setClientId('123456');
        $configuration->setClientSecret('123456');
        $configuration->setRefreshToken('123456');
        $configuration->setCountryCode(Countries::VALUES['United States']);

        return $configuration;
    }

    private function saveConfiguration(AmazonFBAConfigurationInterface $configuration): void
    {
        $this->amazonFBAConfigurationRepository->add($configuration);
    }
}
