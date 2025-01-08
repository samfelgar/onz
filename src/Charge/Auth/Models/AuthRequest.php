<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Auth\Models;

readonly class AuthRequest
{
    public function __construct(
        public string $clientId,
        public string $clientSecret,
    ) {}

    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
        ];
    }
}
