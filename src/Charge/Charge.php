<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Onz\Auth\Models\AuthResponse;
use Samfelgar\Onz\Charge\Models\ChargeRequest;
use Samfelgar\Onz\Charge\Models\ChargeResponse;
use Samfelgar\Onz\Charge\Models\PixQueryResponse;

class Charge
{
    public const BASE_URI = 'https://api.pix.ecomovi.com.br';

    public function __construct(
        private readonly Client $client,
        private readonly AuthResponse $auth,
    ) {}

    private function uri(string $path): string
    {
        return \sprintf('%s/%s', self::BASE_URI, \ltrim($path, '/'));
    }

    /**
     * @throws GuzzleException
     */
    public function createCharge(ChargeRequest $request): ChargeResponse
    {
        $uri = $this->uri("/cob/{$request->txId}");
        $response = $this->client->put($uri, [
            'headers' => [
                'Authorization' => $this->auth->authorizationHeader(),
            ],
            'json' => $request,
        ]);

        return ChargeResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getChargeByTxId(string $txId, int $revision = 0): ChargeResponse
    {
        $uri = $this->uri("/cob/$txId");
        $response = $this->client->get($uri, [
            'headers' => [
                'Authorization' => $this->auth->authorizationHeader(),
            ],
            'query' => [
                'revisao' => $revision,
            ],
        ]);

        return ChargeResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getChargeByEndToEndId(string $endToEndId): PixQueryResponse
    {
        $uri = $this->uri("/pix/$endToEndId");
        $response = $this->client->get($uri, [
            'headers' => [
                'Authorization' => $this->auth->authorizationHeader(),
            ],
        ]);
        return PixQueryResponse::fromResponse($response);
    }
}
