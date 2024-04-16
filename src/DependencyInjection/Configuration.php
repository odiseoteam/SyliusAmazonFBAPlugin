<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        return new TreeBuilder('odiseo_sylius_amazon_fba_plugin');
    }
}
