<?php

/*
 * This file is part of the Odiseo Amazon FBA Plugin package, a commercial software.
 * Only users who have purchased a valid license and accept to the terms of the License Agreement can install
 * and use this program.
 *
 * Copyright (c) 2018-2024 - Pablo D'amico
 */

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Form\Type;

use Odiseo\SyliusAmazonFBAPlugin\Utils\Countries;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AmazonFBAConfigurationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('clientId', TextType::class, [
                'label' => 'odiseo_sylius_amazon_fba_plugin.form.amazon_fba_configuration.client_id',
            ])
            ->add('clientSecret', TextType::class, [
                'label' => 'odiseo_sylius_amazon_fba_plugin.form.amazon_fba_configuration.client_secret',
            ])
            ->add('refreshToken', TextType::class, [
                'label' => 'odiseo_sylius_amazon_fba_plugin.form.amazon_fba_configuration.refresh_token',
                'required' => false,
            ])
            ->add('countryCode', ChoiceType::class, [
                'label' => 'sylius.ui.country',
                'choices' => Countries::VALUES,
            ])
            ->add('sandbox', CheckboxType::class, [
                'label' => 'odiseo_sylius_amazon_fba_plugin.form.amazon_fba_configuration.sandbox',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_amazon_fba_configuration';
    }
}
