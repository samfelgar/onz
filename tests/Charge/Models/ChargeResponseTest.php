<?php

namespace Samfelgar\Onz\Tests\Charge\Models;

use Brick\Math\BigDecimal;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Onz\Charge\Models\ChargeResponse;

#[CoversClass(ChargeResponse::class)]
class ChargeResponseTest extends TestCase
{
    #[Test]
    #[DataProvider('itCanParseAResponseProvider')]
    public function itCanParseAResponse(string $json, float $amount, bool $canChange): void
    {
        $response = new Response(body: $json);
        $charge = ChargeResponse::fromResponse($response);
        $this->assertInstanceOf(ChargeResponse::class, $charge);

        $this->assertEquals(BigDecimal::of($amount)->toScale(2), $charge->amount->original);
        $this->assertEquals($canChange, $charge->amount->canChange);
    }

    public static function itCanParseAResponseProvider(): array
    {
        return [
            [
                <<<'JSON'
                    {
                        "revisao": 0,
                        "loc": {
                            "id": 1123155,
                            "location": "pix.ecomovi.com.br/qr/v3/at/e83177d3-4e3b-45dd-b2dd-381bc3d1c325",
                            "tipoCob": "cob",
                            "criacao": "2025-01-08T22:19:06.304Z"
                        },
                        "location": "pix.ecomovi.com.br/qr/v3/at/e83177d3-4e3b-45dd-b2dd-381bc3d1c325",
                        "calendario": {
                            "criacao": "2025-01-08T22:19:06Z",
                            "expiracao": 3599
                        },
                        "devedor": {},
                        "valor": {
                            "original": "1.00",
                            "modalidadeAlteracao": 0
                        },
                        "chave": "bc03a3b8-e9da-4cfe-9a04-ac1fc1dd6204",
                        "txid": "e41f42bc291a4346986695579da75bd5",
                        "status": "ATIVA",
                        "infoAdicionais": [],
                        "pixCopiaECola": "00020126860014br.gov.bcb.pix2564pix.ecomovi.com.br/qr/v3/at/e83177d3-4e3b-45dd-b2dd-381bc3d1c3255204000053039865802BR5925ONE_TWO_COMUNICACAO_E_SER6009SAO_PAULO62070503***6304043C"
                    }
                    JSON,
                1,
                false,
            ],
            [
                <<<JSON
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
                    JSON,
                37,
                true,
            ],
        ];
    }
}
