<?php

namespace Samfelgar\Onz\Tests\Charge\Models;

use Brick\Math\BigDecimal;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Onz\Charge\Models\ChargeResponse;
use PHPUnit\Framework\TestCase;

#[CoversClass(ChargeResponse::class)]
class ChargeResponseTest extends TestCase
{
    #[Test]
    public function itCanParseAResponse(): void
    {
        $json = <<<JSON
{
  "calendario": {
    "criacao": "2020-09-09T20:15:00.358Z",
    "expiracao": 3600
  },
  "txid": "7978c0c97ea847e78e8849634473c1f1",
  "revisao": 0,
  "loc": {
    "id": 789,
    "location": "pix.example.com/qr/9d36b84fc70b478fb95c12729b90ca25",
    "tipoCob": "cob"
  },
  "location": "pix.example.com/qr/9d36b84fc70b478fb95c12729b90ca25",
  "status": "ATIVA",
  "devedor": {
    "cnpj": "12345678000195",
    "nome": "Empresa de Serviços SA"
  },
  "valor": {
    "original": "37.00",
    "modalidadeAlteracao": 1
  },
  "chave": "7d9f0335-8dcc-4054-9bf9-0dbd61d36906",
  "solicitacaoPagador": "Serviço realizado.",
  "infoAdicionais": [
    {
      "nome": "Campo 1",
      "valor": "Informação Adicional1 do PSP-Recebedor"
    },
    {
      "nome": "Campo 2",
      "valor": "Informação Adicional2 do PSP-Recebedor"
    }
  ]
}
JSON;

        $response = new Response(body: $json);
        $charge = ChargeResponse::fromResponse($response);
        $this->assertInstanceOf(ChargeResponse::class, $charge);

        $this->assertNotNull($charge->payer);
        $this->assertEquals(BigDecimal::of(37)->toScale(2), $charge->amount->original);
        $this->assertTrue($charge->amount->canChange);
    }
}
