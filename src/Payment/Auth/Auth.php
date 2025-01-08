<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Onz\Payment\Auth\Models\AuthRequest;
use Samfelgar\Onz\Payment\Auth\Models\AuthResponse;

class Auth
{
    public function __construct(
        private readonly Client $client,
    ) {}

    /**
     * @throws GuzzleException
     */
    public function authenticate(AuthRequest $request): AuthResponse
    {
        $response = $this->client->post('/api/v2/oauth/token', [
            'json' => $request,
        ]);

        return AuthResponse::fromResponse($response);
    }
}
