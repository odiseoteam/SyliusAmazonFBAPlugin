<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Api;

use GuzzleHttp\Client;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Odiseo\SyliusAmazonFBAPlugin\Provider\EnabledAmazonFBAConfigurationProvider;
use Odiseo\SyliusAmazonFBAPlugin\Utils\Marketplace;
use Odiseo\SyliusAmazonFBAPlugin\Utils\Regions;
use Psr\Http\Message\ResponseInterface;

class AmazonFBAClient
{
    private AmazonFBAConfigurationInterface $amazonFBAConfiguration;

    private Client $client;

    public function __construct(
        private EnabledAmazonFBAConfigurationProvider $enabledAmazonFBAConfigurationProvider,
    ) {
        $amazonFBAConfiguration = $this->enabledAmazonFBAConfigurationProvider->getConfiguration();
        if (!$amazonFBAConfiguration instanceof AmazonFBAConfigurationInterface) {
            throw new \Exception(
                sprintf('The "%s" has not been found', AmazonFBAConfigurationInterface::class),
            );
        }

        $this->amazonFBAConfiguration = $amazonFBAConfiguration;

        $this->client = new Client();
    }

    public function getMarketplaceId(): string
    {
        /** @var string $countryCode */
        $countryCode = $this->amazonFBAConfiguration->getCountryCode();

        $country = Marketplace::getCountry($countryCode);

        return $country['id'];
    }

    public function query(string $endpoint, array $queryParameters = []): ResponseInterface
    {
        $endpoint = $this->getSpApiEndpoint($endpoint);

        $accessToken = $this->generateToken();

        return $this->client->request(
            'GET',
            $endpoint,
            [
                'headers' => [
                    'x-amz-access-token' => $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'query' => $queryParameters,
            ],
        );
    }

    public function request(string $endpoint, string $method = 'POST', array $requestBody = []): ResponseInterface
    {
        $endpoint = $this->getSpApiEndpoint($endpoint);

        $accessToken = $this->generateToken();

        return $this->client->request(
            $method,
            $endpoint,
            [
                'headers' => [
                    'x-amz-access-token' => $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ],
        );
    }

    private function generateToken(): string
    {
        $endpoint = 'https://api.amazon.com/auth/o2/token';

        $response = $this->client->request(
            'POST',
            $endpoint,
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->amazonFBAConfiguration->getRefreshToken(),
                    'client_id' => $this->amazonFBAConfiguration->getClientId(),
                    'client_secret' => $this->amazonFBAConfiguration->getClientSecret(),
                ],
            ],
        );

        /** @var array $contents */
        $contents = json_decode($response->getBody()->getContents(), true);

        return $contents['access_token'];
    }

    private function getSpApiEndpoint(string $endpointAction): string
    {
        /** @var string $countryCode */
        $countryCode = $this->amazonFBAConfiguration->getCountryCode();

        $country = Marketplace::getCountry($countryCode);
        $region = Regions::getRegion($country['region']);

        $baseUrl = $this->amazonFBAConfiguration->isSandbox() ? $region['sandbox_url'] : $region['production_url'];

        return $baseUrl . '/' . $endpointAction;
    }
}
