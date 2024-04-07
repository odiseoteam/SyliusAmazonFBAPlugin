<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAmazonFBAPlugin\Behat\Behaviour;

interface ContainsErrorInterface
{
    public function containsError(): bool;

    public function containsErrorWithMessage(string $message, bool $strict = true): bool;
}
