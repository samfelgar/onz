<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Auth\Models;

use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;

readonly class AuthResponse
{
    /**
     * @param Scope[] $scopes
     */
    public function __construct(
        public string $tokenType,
        public int $expiresIn,
        public int $refreshExpiresIn,
        public int $notBeforePolicy,
        public string $accessToken,
        public array $scopes,
    ) {
        Assert::allIsInstanceOf($this->scopes, Scope::class);
    }

    public static function fromArray(array $data): AuthResponse
    {
        $scopes = \array_map(static fn(string $scope): Scope => Scope::from($scope), \explode(' ', $data['scope']));

        return new AuthResponse(
            $data['token_type'],
            $data['expires_in'],
            $data['refresh_expires_in'],
            $data['not-before-policy'],
            $data['access_token'],
            $scopes,
        );
    }

    public static function fromResponse(ResponseInterface $response): AuthResponse
    {
        $data = \json_decode((string)$response->getBody(), true);
        return self::fromArray($data);
    }

    public function authorizationHeader(): string
    {
        return \sprintf('Bearer %s', $this->accessToken);
    }
}
