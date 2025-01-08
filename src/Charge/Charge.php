<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Onz\Charge\Auth\Auth;
use Samfelgar\Onz\Charge\Auth\Models\AuthResponse;
use Samfelgar\Onz\Charge\Models\ChargeRequest;
use Samfelgar\Onz\Charge\Models\ChargeResponse;
use Samfelgar\Onz\Charge\Models\PixQueryResponse;

class Charge
{
    private ?AuthResponse $auth = null;

    public function __construct(
        private readonly Client $client,
    ) {}

    public function authentication(): Auth
    {
        return new Auth($this->client);
    }

    public function setAuth(AuthResponse $auth): void
    {
        $this->auth = $auth;
    }

    private function assertAuthentication(): void
    {
        if ($this->auth === null) {
            throw new \RuntimeException('You must authenticate first');
        }
    }

    /**
     * @throws GuzzleException
     */
    public function createCharge(ChargeRequest $request): ChargeResponse
    {
        $this->assertAuthentication();
        $uri = "/cob/{$request->txId}";
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
        $this->assertAuthentication();
        $uri = "/cob/$txId";
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
        $this->assertAuthentication();
        $uri = "/pix/$endToEndId";
        $response = $this->client->get($uri, [
            'headers' => [
                'Authorization' => $this->auth->authorizationHeader(),
            ],
        ]);
        return PixQueryResponse::fromResponse($response);
    }
}
