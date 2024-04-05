<?php

declare(strict_types=1);

namespace Odiseo\SyliusAmazonFBAPlugin\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Odiseo\SyliusAmazonFBAPlugin\Entity\AmazonFBAConfigurationInterface;
use Odiseo\SyliusAmazonFBAPlugin\Provider\EnabledAmazonFBAConfigurationProvider;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class AmazonFBAClient
{
    private const SP_API_SANDBOX_BASE_URL = 'https://sandbox.sellingpartnerapi-eu.amazon.com';
    private const SP_API_PRODUCTION_BASE_URL = 'https://sellingpartnerapi-eu.amazon.com/';
    private const API_SANDBOX_BASE_URL = 'https://api.sandbox.amazon.co.uk';
    private const API_PRODUCTION_BASE_URL = 'https://api.amazon.co.uk';

    private AmazonFBAConfigurationInterface $amazonFBAConfiguration;

    private Client $client;

    public function __construct(
        private \Redis $redis,
        private EnabledAmazonFBAConfigurationProvider $enabledAmazonFBAConfigurationProvider,
    ) {
        $amazonFBAConfiguration = $this->enabledAmazonFBAConfigurationProvider->getConfiguration();
        if (!$amazonFBAConfiguration instanceof AmazonFBAConfigurationInterface) {
            throw new NotFoundHttpException(
                sprintf('The "%s" has not been found', AmazonFBAConfigurationInterface::class),
            );
        }

        $this->amazonFBAConfiguration = $amazonFBAConfiguration;

        $this->client = new Client();
    }

    public function query(string $endpoint, array $queryParameters = []): ResponseInterface
    {
        $accessToken = $this->generateToken();
        $endpoint = $this->getSpApiEndpoint($endpoint);

        return $this->client->request(
            'GET',
            $endpoint,
            [
                'headers' => [
                    'x-amz-access-token' => $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'query' => $queryParameters,
            ]
        );
    }

    public function request(
        string $endpoint,
        string $method = 'POST',
        array $requestBody = [],
        bool $forceGenerateAccessToken = false
    ): ResponseInterface {
        $accessToken = $this->redis->get('app.amazon_api.access_token');
        if (!$accessToken || $forceGenerateAccessToken) {
            $accessToken = $this->generateToken();
            $this->redis->set('app.amazon_api.access_token', $accessToken);
        }

        $fullEndpoint = $this->getSpApiEndpoint($endpoint);

        try {
            return $this->client->request(
                $method,
                $fullEndpoint,
                [
                    'headers' => [
                        'x-amz-access-token' => $accessToken,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $requestBody,
                ]
            );
        } catch (ClientException $e) {
            if ($e->getCode() === 403 && !$forceGenerateAccessToken) {
                return $this->request($endpoint, $method, $requestBody, true);
            }

            throw $e;
        }
    }

    protected function generateToken(): string|false
    {
        $baseUrl = $this->amazonFBAConfiguration->isSandbox() ? self::API_SANDBOX_BASE_URL : self::API_PRODUCTION_BASE_URL;
        $endpoint = $baseUrl.'/'.'auth/o2/token';

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
                ]
            ]
        );
        $response = json_decode($response->getBody()->getContents(), true);

        return $response['access_token'];
    }

    private function getSpApiEndpoint(string $endpointAction): string
    {
        $baseUrl = $this->amazonFBAConfiguration->isSandbox() ? self::SP_API_SANDBOX_BASE_URL : self::SP_API_PRODUCTION_BASE_URL;

        return $baseUrl.'/'.$endpointAction;
    }
}
