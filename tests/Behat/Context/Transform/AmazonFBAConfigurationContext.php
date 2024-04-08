<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class AmazonFBAConfigurationContext implements Context
{
    public function __construct(
        private RepositoryInterface $amazonFBAConfigurationRepository
    ) {
    }

    /**
     * @Transform /^Amazon FBA configuration "([^"]+)"$/
     * @Transform /^"([^"]+)" Amazon FBA configuration/
     */
    public function getConfigurationByName(string $code): AmazonFBAConfigurationInterface
    {
        /** @var AmazonFBAConfigurationInterface|null $configuration */
        $configuration = $this->amazonFBAConfigurationRepository->findOneBy(['code' => $code]);

        Assert::notNull(
            $configuration,
            'Amazon FBA configuration with code %s does not exist'
        );

        return $configuration;
    }
}
