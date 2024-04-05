<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Form\Type\Shipping\Calculator;

use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

final class AmazonFBARateConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('speed_category', ChoiceType::class, [
                'label' => 'odiseo_sylius_amazon_fba_plugin.form.shipping_calculator.amazon_fba_rate_configuration.speed_category',
                'choices' => [
                    'Standard' => 'Standard',
                    'Expedited' => 'Expedited',
                    'Priority' => 'Priority',
                ],
                'constraints' => [
                    new NotBlank(['groups' => ['odiseo']]),
                ],
            ])
            ->add('default_amount', MoneyType::class, [
                'label' => 'odiseo_sylius_amazon_fba_plugin.form.shipping_calculator.amazon_fba_rate_configuration.default_amount',
                'constraints' => [
                    new NotBlank(['groups' => ['odiseo']]),
                    new Range(['min' => 0, 'minMessage' => 'sylius.shipping_method.calculator.min', 'groups' => ['odiseo']]),
                    new Type(['type' => 'integer', 'groups' => ['odiseo']]),
                ],
                'currency' => $options['currency'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
                'validation_groups' => ['odiseo'],
            ])
            ->setRequired('currency')
            ->setAllowedTypes('currency', 'string')
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_shipping_calculator_amazon_fba_rate';
    }
}
