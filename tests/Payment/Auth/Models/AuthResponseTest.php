<?php

namespace Samfelgar\Onz\Tests\Payment\Auth\Models;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Onz\Payment\Auth\Models\AuthResponse;

#[CoversClass(AuthResponse::class)]
class AuthResponseTest extends TestCase
{
    #[Test]
    public function itCanParseAResponse(): void
    {
        $json = <<<JSON
            {
              "tokenType": "string",
              "expiresAt": 0,
              "refreshExpiresIn": 0,
              "notBeforePolicy": 0,
              "accessToken": "string",
              "scope": "pix.read"
            }
            JSON;

        $response = new Response(body: $json);
        $authResponse = AuthResponse::fromResponse($response);
        $this->assertInstanceOf(AuthResponse::class, $authResponse);
    }
}
