<?php

namespace Samfelgar\Onz\Tests\Payment\Models;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Onz\Payment\Models\TransactionDetails;

#[CoversClass(TransactionDetails::class)]
class TransactionDetailsTest extends TestCase
{
    #[Test]
    public function itCanParseAResponse(): void
    {
        $json = <<<JSON
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
              }
            }
            JSON;

        $response = new Response(body: $json);
        $transactionDetails = TransactionDetails::fromResponse($response);
        $this->assertInstanceOf(TransactionDetails::class, $transactionDetails);
    }
}
