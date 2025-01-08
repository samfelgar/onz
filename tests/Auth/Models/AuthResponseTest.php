<?php

namespace Samfelgar\Onz\Tests\Auth\Models;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Onz\Auth\Models\AuthResponse;
use PHPUnit\Framework\TestCase;

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
