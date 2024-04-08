<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Page\Admin\AmazonFBAConfiguration\CreatePageInterface;
use Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Page\Admin\AmazonFBAConfiguration\IndexPageInterface;
use Webmozart\Assert\Assert;

final class ManagingAmazonFBAConfigurationsContext implements Context
{
    public function __construct(
        private CurrentPageResolverInterface $currentPageResolver,
        private IndexPageInterface $indexPage,
        private CreatePageInterface $createPage
    ) {
    }

    /**
     * @Given I want to add a new Amazon FBA configuration
     */
    public function iWantToAddNewConfiguration(): void
    {
        $this->createPage->open();
    }

    /**
     * @When I fill the code with :code
     * @When I rename the code with :code
     */
    public function iFillTheCodeWith(string $code): void
    {
        $this->createPage->fillCode($code);
    }

    /**
     * @When I fill the client id with :clientId
     * @When I rename the client id with :clientId
     */
    public function iFillTheClientIdWith(string $clientId): void
    {
        $this->createPage->fillClientId($clientId);
    }

    /**
     * @When I fill the client secret with :clientSecret
     * @When I rename the client secret with :clientSecret
     */
    public function iFillTheClientSecretWith(string $clientSecret): void
    {
        $this->createPage->fillClientSecret($clientSecret);
    }

    /**
     * @When I fill the refresh token with :refreshToken
     * @When I rename the refresh token with :refreshToken
     */
    public function iFillTheRefreshTokenWith(string $refreshToken): void
    {
        $this->createPage->fillRefreshToken($refreshToken);
    }

    /**
     * @When I add it
     */
    public function iAddIt(): void
    {
        $this->createPage->create();
    }

    /**
     * @Then /^the (Amazon FBA configuration "([^"]+)") should appear in the admin/
     */
    public function configurationShouldAppearInTheAdmin(AmazonFBAConfigurationInterface $configuration): void
    {
        $this->indexPage->open();

        Assert::true(
            $this->indexPage->isSingleResourceOnPage(['code' => $configuration->getCode()]),
            sprintf('Amazon FBA configuration %s should exist but it does not', $configuration->getCode())
        );
    }

    /**
     * @Then I should be notified that the form contains invalid fields
     */
    public function iShouldBeNotifiedThatTheFormContainsInvalidFields(): void
    {
        Assert::true(
            $this->resolveCurrentPage()->containsError(),
            'The form should be notified you that the form contains invalid field but it does not'
        );
    }

    /**
     * @Then I should be notified that there is already an existing Amazon FBA configuration with provided code
     */
    public function iShouldBeNotifiedThatThereIsAlreadyAnExistingConfigurationWithSlug(): void
    {
        Assert::true(
            $this->resolveCurrentPage()->containsErrorWithMessage(
                'There is an existing configuration with this code.',
                false
            )
        );
    }

    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->indexPage,
            $this->createPage
        ]);
    }
}
