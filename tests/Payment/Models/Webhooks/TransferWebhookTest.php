<?php

namespace Samfelgar\Onz\Tests\Payment\Models\Webhooks;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Onz\Payment\Models\Webhooks\TransferWebhook;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransferWebhook::class)]
class TransferWebhookTest extends TestCase
{
    #[Test]
    public function itCanParseARequest(): void
    {
        $json = <<<'JSON'
{
  "data": {
    "id": 0,
    "idempotencyKey": "string",
    "endToEndId": "string",
    "pixKey": "string",
    "transactionType": "PIX",
    "status": "CANCELED",
    "errorCode": "AB03",
    "creditDebitType": "CREDIT",
    "localInstrument": "MANU",
    "createdAt": "2019-08-24T14:15:22Z",
    "creditorAccount": {
      "ispb": "string",
      "issuer": "string",
      "number": "string",
      "accountType": "SLRY",
      "document": "string",
      "name": "string"
    },
    "debtorAccount": {
      "ispb": "string",
      "issuer": "string",
      "number": "string",
      "accountType": "SLRY",
      "document": "string",
      "name": "string"
    },
    "remittanceInformation": "string",
    "txId": "string",
    "payment": {
      "currency": "BRL",
      "amount": 0.1
    },
    "refunds": [
      {
        "endToEndId": "string",
        "status": "CANCELED",
        "errorCode": "AB03",
        "pixRefundAmount": {
          "currency": "BRL",
          "amount": 0.1
        }
      }
    ]
  },
  "type": "TRANSFER"
}
JSON;

        $request = new Request('post', '/', body: $json);
        $this->assertInstanceOf(TransferWebhook::class, TransferWebhook::fromRequest($request));
    }
}
