<?php

namespace Samfelgar\Onz\Tests\Charge\Auth\Models;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Onz\Charge\Auth\Models\AuthResponse;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthResponse::class)]
class AuthResponseTest extends TestCase
{
    #[Test]
    public function itCanParseAResponse(): void
    {
        $json = <<<JSON
{
    "access_token": "eyJhbGciOiJSUzI1NiIsInR5cCIg",
    "expires_in": 300,
    "refresh_expires_in": 0,
    "token_type": "Bearer",
    "not-before-policy": 0,
    "scope": "email profile qrcodes"
}
JSON;

        $response = new Response(body: $json);
        $authResponse = AuthResponse::fromResponse($response);
        $this->assertInstanceOf(AuthResponse::class, $authResponse);
    }
}
