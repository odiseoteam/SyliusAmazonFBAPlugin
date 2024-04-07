<?php

/*
 * This file is part of the Odiseo Amazon FBA Plugin package, a commercial software.
 * Only users who have purchased a valid license and accept to the terms of the License Agreement can install
 * and use this program.
 *
 * Copyright (c) 2018-2024 - Pablo D'amico
 */

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Utils;

final class Marketplace
{
    private static array $countries = [
        'CA' => [
            'name' => 'Canada',
            'id' => 'A2EUQ1WTGCTBG2',
            'region' => Regions::NORTH_AMERICA,
            'url' => 'https://sellercentral.amazon.ca',
        ],
        'US' => [
            'name' => 'United States of America',
            'id' => 'ATVPDKIKX0DER',
            'region' => Regions::NORTH_AMERICA,
            'url' => 'https://sellercentral.amazon.com',
        ],
        'MX' => [
            'name' => 'Mexico',
            'id' => 'A1AM78C64UM0Y8',
            'region' => Regions::NORTH_AMERICA,
            'url' => 'https://sellercentral.amazon.com.mx',
        ],
        'BR' => [
            'name' => 'Brazil',
            'id' => 'A2Q3Y263D00KWC',
            'region' => Regions::NORTH_AMERICA,
            'url' => 'https://sellercentral.amazon.com.br',
        ],
        'ES' => [
            'name' => 'Spain',
            'id' => 'A1RKKUPIHCS9HS',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral-europe.amazon.com',
        ],
        'GB' => [
            'name' => 'United Kingdom',
            'id' => 'A1F83G8C2ARO7P',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral-europe.amazon.com',
        ],
        'FR' => [
            'name' => 'France',
            'id' => 'A13V1IB3VIYZZH',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral-europe.amazon.com',
        ],
        'BE' => [
            'name' => 'Belgium',
            'id' => 'AMEN7PMS3EDWL',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral.amazon.com.be',
        ],
        'NL' => [
            'name' => 'Netherlands',
            'id' => 'A1805IZSGTT6HS',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral.amazon.nl',
        ],
        'DE' => [
            'name' => 'Germany',
            'id' => 'A1PA6795UKMFR9',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral-europe.amazon.com',
        ],
        'IT' => [
            'name' => 'Italy',
            'id' => 'APJ6JRA9NG5V4',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral-europe.amazon.com',
        ],
        'SE' => [
            'name' => 'Sweden',
            'id' => 'A2NODRKZP88ZB9',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral.amazon.se',
        ],
        'PL' => [
            'name' => 'Poland',
            'id' => 'A1C3SOZRARQ6R3',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral.amazon.pl',
        ],
        'EG' => [
            'name' => 'Egypt',
            'id' => 'ARBP9OOSHTCHU',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral.amazon.eg',
        ],
        'TR' => [
            'name' => 'Turkey',
            'id' => 'A33AVAJ2PDY3EV',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral.amazon.com.tr',
        ],
        'SA' => [
            'name' => 'Saudi Arabia',
            'id' => 'A17E79C6D8DWNP',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral.amazon.sa',
        ],
        'AE' => [
            'name' => 'United Arab Emirates',
            'id' => 'A2VIGQ35RCS4UG',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral.amazon.ae',
        ],
        'IN' => [
            'name' => 'India',
            'id' => 'A21TJRUUN4KGV',
            'region' => Regions::EUROPE,
            'url' => 'https://sellercentral.amazon.in',
        ],
        'SG' => [
            'name' => 'Singapore',
            'id' => 'A19VAU5U5O7RUS',
            'region' => Regions::FAR_EAST,
            'url' => 'https://sellercentral.amazon.sg',
        ],
        'AU' => [
            'name' => 'Australia',
            'id' => 'A39IBJ37TRP1C6',
            'region' => Regions::FAR_EAST,
            'url' => 'https://sellercentral.amazon.com.au',
        ],
        'JP' => [
            'name' => 'Japan',
            'id' => 'A1VC38T7YXB528',
            'region' => Regions::FAR_EAST,
            'url' => 'https://sellercentral.amazon.co.jp',
        ],
    ];

    public static function getCountry(string $country): array
    {
        if (!\array_key_exists($country, self::$countries)) {
            throw new \BadMethodCallException('Call to undefined method ' . self::class . '::' . $country . '()');
        }

        return self::$countries[$country];
    }
}
