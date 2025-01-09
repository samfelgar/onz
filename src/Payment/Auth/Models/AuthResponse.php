<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Auth\Models;

use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;

readonly class AuthResponse
{
    /**
     * @param Scope[] $scopes
     */
    public function __construct(
        public string $tokenType,
        public int $expiresAt,
        public int $refreshExpiresIn,
        public ?int $notBeforePolicy,
        public string $accessToken,
        public array $scopes,
    ) {
        Assert::allIsInstanceOf($this->scopes, Scope::class);
    }

    public static function fromArray(array $data): AuthResponse
    {
        $scopes = \array_map(static fn(string $scope): Scope => Scope::from($scope), \explode(' ', $data['scope']));

        return new AuthResponse(
            $data['tokenType'],
            $data['expiresAt'],
            $data['refreshExpiresIn'],
            $data['notBeforePolicy'] ?? null,
            $data['accessToken'],
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
