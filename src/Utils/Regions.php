<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Utils;

final class Regions
{
    public const EUROPE = 'eu-west-1';

    private const EUROPE_PRODUCTION_HOST = 'sellingpartnerapi-eu.amazon.com';

    private const EUROPE_SANDBOX_HOST = 'sandbox.sellingpartnerapi-eu.amazon.com';

    private const EUROPE_PRODUCTION_URL = 'https://' . self::EUROPE_PRODUCTION_HOST;

    private const EUROPE_SANDBOX_URL = 'https://' . self::EUROPE_SANDBOX_HOST;

    public const FAR_EAST = 'us-west-2';

    private const FAR_EAST_PRODUCTION_HOST = 'sellingpartnerapi-fe.amazon.com';

    private const FAR_EAST_SANDBOX_HOST = 'sandbox.sellingpartnerapi-fe.amazon.com';

    private const FAR_EAST_PRODUCTION_URL = 'https://' . self::FAR_EAST_PRODUCTION_HOST;

    private const FAR_EAST_SANDBOX_URL = 'https://' . self::FAR_EAST_SANDBOX_HOST;

    public const NORTH_AMERICA = 'us-east-1';

    private const NORTH_AMERICA_PRODUCTION_HOST = 'sellingpartnerapi-na.amazon.com';

    private const NORTH_AMERICA_SANDBOX_HOST = 'sandbox.sellingpartnerapi-na.amazon.com';

    private const NORTH_AMERICA_PRODUCTION_URL = 'https://' . self::NORTH_AMERICA_PRODUCTION_HOST;

    private const NORTH_AMERICA_SANDBOX_URL = 'https://' . self::NORTH_AMERICA_SANDBOX_HOST;

    private static array $regions = [
        self::EUROPE => [
            'production_url' => self::EUROPE_PRODUCTION_URL,
            'sandbox_url' => self::EUROPE_SANDBOX_URL,
        ],
        self::FAR_EAST => [
            'production_url' => self::FAR_EAST_PRODUCTION_URL,
            'sandbox_url' => self::FAR_EAST_SANDBOX_URL,
        ],
        self::NORTH_AMERICA => [
            'production_url' => self::NORTH_AMERICA_PRODUCTION_URL,
            'sandbox_url' => self::NORTH_AMERICA_SANDBOX_URL,
        ],
    ];

    public static function getRegion(string $region): array
    {
        if (!\array_key_exists($region, self::$regions)) {
            throw new \BadMethodCallException('Call to undefined method ' . self::class . '::' . $region . '()');
        }

        return self::$regions[$region];
    }
}
