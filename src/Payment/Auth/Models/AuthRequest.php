<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Auth\Models;

use Webmozart\Assert\Assert;

readonly class AuthRequest implements \JsonSerializable
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

    public function jsonSerialize(): array
    {
        $scopeValues = \array_map(static fn(Scope $scope): string => $scope->value, $this->scopes);

        return [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'grantType' => 'client_credentials',
            'scope' => \implode(' ', $scopeValues),
        ];
    }
}
