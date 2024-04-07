<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Fixture\Factory;

use Faker\Factory;
use Faker\Generator as FakerGenerator;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmazonFBAConfigurationExampleFactory implements ExampleFactoryInterface
{
    protected FakerGenerator $faker;

    protected OptionsResolver $optionsResolver;

    public function __construct(
        protected FactoryInterface $amazonFBAConfigurationFactory,
    ) {
        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): AmazonFBAConfigurationInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var AmazonFBAConfigurationInterface $amazonFBAConfiguration */
        $amazonFBAConfiguration = $this->amazonFBAConfigurationFactory->createNew();

        $amazonFBAConfiguration->setCode($options['code']);
        $amazonFBAConfiguration->setClientId($options['client_id']);
        $amazonFBAConfiguration->setClientSecret($options['client_secret']);
        $amazonFBAConfiguration->setRefreshToken($options['refresh_token']);
        $amazonFBAConfiguration->setCountryCode($options['country_code']);
        $amazonFBAConfiguration->setSandbox($options['sandbox']);
        $amazonFBAConfiguration->setEnabled($options['enabled']);

        return $amazonFBAConfiguration;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('code')
            ->setRequired('client_id')
            ->setRequired('client_secret')
            ->setRequired('refresh_token')
            ->setRequired('country_code')
            ->setDefault('sandbox', true)
            ->setDefault('enabled', false)
            ->setAllowedTypes('sandbox', 'bool')
            ->setAllowedTypes('enabled', 'bool')
        ;
    }
}
