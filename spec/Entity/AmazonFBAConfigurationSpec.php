<?php

namespace spec\Odiseo\SyliusAmazonFBAPlugin\Entity;

use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfiguration;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

class AmazonFBAConfigurationSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(AmazonFBAConfiguration::class);
    }

    public function it_implements_resource_interface(): void
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    public function it_implements_timestampable_interface(): void
    {
        $this->shouldImplement(TimestampableInterface::class);
    }

    public function it_implements_easy_post_configuration_interface(): void
    {
        $this->shouldImplement(AmazonFBAConfigurationInterface::class);
    }

    public function it_toggles(): void
    {
        $this->enable();
        $this->isEnabled()->shouldReturn(true);
        $this->disable();
        $this->isEnabled()->shouldReturn(false);
    }

    public function it_allows_access_via_properties(): void
    {
        $this->setCode('configuration_default');
        $this->getCode()->shouldReturn('configuration_default');
        $this->setClientId('clientId');
        $this->getClientId()->shouldReturn('clientId');
        $this->setClientSecret('clientSecret');
        $this->getClientSecret()->shouldReturn('clientSecret');
        $this->setRefreshToken('refreshToken');
        $this->getRefreshToken()->shouldReturn('refreshToken');
        $this->setSandbox(true);
        $this->isSandbox()->shouldReturn(true);
    }
}
