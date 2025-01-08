<?php

namespace Samfelgar\Onz\Tests\Payment\Models;

use Brick\Math\BigDecimal;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Onz\Payment\Models\PaymentResponse;
use PHPUnit\Framework\TestCase;

#[CoversClass(PaymentResponse::class)]
class PaymentResponseTest extends TestCase
{
    #[Test]
    public function itCanParseAResponse(): void
    {
        $json = <<<JSON
{
  "endToEndId": "string",
  "eventDate": "2019-08-24T14:15:22Z",
  "id": 0,
  "payment": {
    "currency": "BRL",
    "amount": 0.1
  },
  "type": "string"
}
JSON;

        $response = new Response(body: $json);
        $paymentResponse = PaymentResponse::fromResponse($response);
        $this->assertInstanceOf(PaymentResponse::class, $paymentResponse);
        $this->assertEquals(BigDecimal::of(.1), $paymentResponse->paymentAmount->amount);
        $this->assertEquals('2019-08-24 14:15:22', $paymentResponse->eventDate->format('Y-m-d H:i:s'));
    }
}
