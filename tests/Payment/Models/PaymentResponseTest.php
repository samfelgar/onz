<?php

namespace Samfelgar\Onz\Tests\Payment\Models;

use Brick\Math\BigDecimal;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Onz\Payment\Models\PaymentResponse;

#[CoversClass(PaymentResponse::class)]
class PaymentResponseTest extends TestCase
{
    #[Test]
    #[DataProvider('itCanParseAResponseProvider')]
    public function itCanParseAResponse(string $json, float $amount): void
    {
        $response = new Response(body: $json);
        $paymentResponse = PaymentResponse::fromResponse($response);
        $this->assertInstanceOf(PaymentResponse::class, $paymentResponse);
        $this->assertEquals(BigDecimal::of($amount), $paymentResponse->paymentAmount->amount);
    }

    public static function itCanParseAResponseProvider(): array
    {
        return [
            [
                '{
  "endToEndId": "string",
  "eventDate": "2019-08-24T14:15:22Z",
  "id": 0,
  "payment": {
    "currency": "BRL",
    "amount": 0.1
  },
  "type": "string"
}',
                .1,
            ],
            [
                '{"endToEndId":"E330535802025010903562129126f6b8","eventDate":"2025-01-09T03:56:21.291+00:00","status":"PENDING","id":1448625,"payment":{"currency":"BRL","amount":0.5},"type":"QUEUED"}',
                .5,
            ],
        ];
    }
}
