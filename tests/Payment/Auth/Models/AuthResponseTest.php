<?php

namespace Samfelgar\Onz\Tests\Payment\Auth\Models;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Onz\Payment\Auth\Models\AuthResponse;

#[CoversClass(AuthResponse::class)]
class AuthResponseTest extends TestCase
{
    #[Test]
    #[DataProvider('itCanParseAResponseProvider')]
    public function itCanParseAResponse(string $json): void
    {
        $response = new Response(body: $json);
        $authResponse = AuthResponse::fromResponse($response);
        $this->assertInstanceOf(AuthResponse::class, $authResponse);
    }

    public static function itCanParseAResponseProvider(): array
    {
        return [
            [
                <<<JSON
            {
              "tokenType": "string",
              "expiresAt": 0,
              "refreshExpiresIn": 0,
              "notBeforePolicy": 0,
              "accessToken": "string",
              "scope": "pix.read"
            }
            JSON
            ],
            [
                '{"accessToken":"token","expiresAt":1736394756,"refreshExpiresIn":0,"tokenType":"Bearer","scope":"api-account pix.write pix.read"}'
            ]
        ];
    }
}
