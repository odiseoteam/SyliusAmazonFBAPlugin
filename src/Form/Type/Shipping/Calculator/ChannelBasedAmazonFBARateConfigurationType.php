<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Form\Type\Shipping\Calculator;

use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChannelBasedAmazonFBARateConfigurationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => AmazonFBARateConfigurationType::class,
            'entry_options' => fn (ChannelInterface $channel): array => [
                'label' => $channel->getName(),
                'currency' => $channel->getBaseCurrency()?->getCode(),
            ],
        ]);
    }

    public function getParent(): string
    {
        return ChannelCollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_channel_based_shipping_calculator_amazon_fba_rate';
    }
}
