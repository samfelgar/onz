<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Onz\Charge\Auth\Models\AuthRequest;
use Samfelgar\Onz\Charge\Auth\Models\AuthResponse;

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
        $response = $this->client->post('/oauth/token', [
            'form_params' => $request->toArray(),
        ]);

        return AuthResponse::fromResponse($response);
    }
}
