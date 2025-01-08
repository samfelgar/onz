<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Auth\Models;

use Webmozart\Assert\Assert;

readonly class AuthRequest
{
    /**
     * @param Scope[] $scopes
     */
    public function __construct(
        public string $clientId,
        public string $clientSecret,
        public array $scopes,
    ) {
        Assert::allIsInstanceOf($this->scopes, Scope::class);
    }

    public function toArray(): array
    {
        $scopeValues = \array_map(static fn(Scope $scope): string => $scope->value, $this->scopes);

        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => \implode(' ', $scopeValues),
        ];
    }
}
