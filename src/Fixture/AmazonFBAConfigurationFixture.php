<?php

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
        $node->booleanNode('sandbox');
        $node->booleanNode('enabled');
    }

    public function getName(): string
    {
        return 'amazon_fba_configuration';
    }
}
