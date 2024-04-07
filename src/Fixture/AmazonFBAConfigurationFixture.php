<?php

/*
 * This file is part of the Odiseo Amazon FBA Plugin package, a commercial software.
 * Only users who have purchased a valid license and accept to the terms of the License Agreement can install
 * and use this program.
 *
 * Copyright (c) 2018-2024 - Pablo D'amico
 */

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Fixture;

use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class AmazonFBAConfigurationFixture extends AbstractResourceFixture
{
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $node = $resourceNode->children();

        $node->scalarNode('code')->cannotBeEmpty();
        $node->scalarNode('client_id')->cannotBeEmpty();
        $node->scalarNode('client_secret')->cannotBeEmpty();
        $node->scalarNode('refresh_token')->cannotBeEmpty();
        $node->scalarNode('country_code')->cannotBeEmpty();
        $node->booleanNode('sandbox');
        $node->booleanNode('enabled');
    }

    public function getName(): string
    {
        return 'amazon_fba_configuration';
    }
}
