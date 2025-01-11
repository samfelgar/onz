<?php

namespace Samfelgar\Onz\Tests\Payment\Models\Webhooks;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Onz\Payment\Models\Webhooks\TransferWebhook;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransferWebhook::class)]
class TransferWebhookTest extends TestCase
{
    #[Test]
    #[DataProvider('itCanParseARequestProvider')]
    public function itCanParseARequest(string $json): void
    {
        $request = new Request('post', '/', body: $json);
        $this->assertInstanceOf(TransferWebhook::class, TransferWebhook::fromRequest($request));
    }

    public static function itCanParseARequestProvider(): array
    {
        return [
            [
                '{
    "data": {
        "id": 6750828,
        "txId": "83ff72b1ccfc4bbd9d2b1b4f6d46cac2",
        "pixKey": "bc03a3b8-e9da-4cfe-9a04-ac1fc1dd6204",
        "status": "LIQUIDATED",
        "payment": {
            "amount": "1.00",
            "currency": "BRL"
        },
        "refunds": [],
        "createdAt": "2025-01-11T00:34:31.314+00:00",
        "errorCode": null,
        "endToEndId": "E00416968202501110034KOJECGjkSiZ",
        "ticketData": {},
        "webhookType": "RECEIVE",
        "debtorAccount": {
            "ispb": "00416968",
            "name": "SAMUEL FELIPE GARCIA",
            "issuer": "0000",
            "number": "0000",
            "document": "03701407100",
            "accountType": "CACC"
        },
        "idempotencyKey": null,
        "creditDebitType": "CREDIT",
        "creditorAccount": {
            "ispb": "33053580",
            "name": "ONE TWO COMUNICACAO E SERVICOS DIGITAIS LTDA",
            "issuer": "0000",
            "number": "0000",
            "document": "23169607000103",
            "accountType": "CACC"
        },
        "localInstrument": "QRDN",
        "transactionType": "PIX",
        "remittanceInformation": null
    },
    "type": "RECEIVE"
}',
            ],

            [
                '{
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
}'
            ]
        ];
    }
}
