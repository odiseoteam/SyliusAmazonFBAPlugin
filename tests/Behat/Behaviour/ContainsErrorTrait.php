<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Behaviour;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Behaviour\DocumentAccessor;

trait ContainsErrorTrait
{
    use DocumentAccessor;

    public function containsError(): bool
    {
        $validationMessageElements = $this->getDocument()->findAll('css', '.sylius-validation-error');

        return count($validationMessageElements) > 0;
    }

    public function containsErrorWithMessage(string $message, bool $strict = true): bool
    {
        $validationMessageElements = $this->getDocument()->findAll('css', '.sylius-validation-error');

        /** @var NodeElement $validationMessageElement */
        foreach ($validationMessageElements as $validationMessageElement) {
            if (true === $strict && $message === $validationMessageElement->getText()) {
                return true;
            }

            if (false === $strict && strstr($validationMessageElement->getText(), $message)) {
                return true;
            }
        }

        return false;
    }
}
