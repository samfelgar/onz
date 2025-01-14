<?php

namespace Samfelgar\Onz\Tests\Charge\Models;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Onz\Charge\Models\PixQueryResponse;
use Samfelgar\Onz\Charge\Models\ReturnStatus;

#[CoversClass(PixQueryResponse::class)]
class PixQueryResponseTest extends TestCase
{
    #[Test]
    #[DataProvider('itCanParseAResponseProvider')]
    public function itCanParseAResponse(string $json): void
    {
        $response = new Response(body: $json);
        $this->assertInstanceOf(PixQueryResponse::class, PixQueryResponse::fromResponse($response));
    }

    public static function itCanParseAResponseProvider(): array
    {
        return [
            [
                <<<JSON
                    {
                      "endToEndId": "E12345678202009091221abcdef12345",
                      "txid": "cd1fe328c875481285a6f233ae41b662",
                      "valor": "100.00",
                      "horario": "2020-09-10T13:03:33.902Z",
                      "infoPagador": "Reforma da casa",
                      "devolucoes": [
                        {
                          "id": "000AAA111",
                          "rtrId": "D12345678202009091000abcde123456",
                          "valor": "11.00",
                          "horario": {
                            "solicitacao": "2020-09-10T13:03:33.902Z"
                          },
                          "status": "EM_PROCESSAMENTO"
                        }
                      ]
                    }
                    JSON,
            ],

            [
                <<<JSON
                    {
                      "endToEndId": "E12345678202009091221ghijk78901234",
                      "txid": "5b933948f3224266b1050ac54319e775",
                      "valor": "200.00",
                      "horario": "2020-09-10T13:03:33.902Z",
                      "infoPagador": "Revisão do carro"
                    }
                    JSON,
            ],

            [
                <<<JSON
                    {
                      "endToEndId": "E88631478202009091221ghijk78901234",
                      "txid": "82433415910c47e5adb6ac3527cca160",
                      "valor": "200.00",
                      "componentesValor": {
                        "original": {
                          "valor": "180.00"
                        },
                        "saque": {
                          "valor": "20.00",
                          "modalidadeAgente": "AGPSS",
                          "prestadorDeServicoDeSaque": "12345678"
                        }
                      },
                      "horario": "2020-09-10T13:03:33.902Z",
                      "infoPagador": "Saque Pix"
                    }
                    JSON,

            ],

        ];
    }

    #[Test]
    #[DataProvider('itCanParseARequestProvider')]
    public function itCanParseARequest(string $json): void
    {
        $response = new Request('POST', '/test', body: $json);
        $this->assertInstanceOf(PixQueryResponse::class, PixQueryResponse::fromRequest($response));
    }

    public static function itCanParseARequestProvider(): array
    {
        return [
            [
                <<<JSON
                    {
                      "pix": {
                        "txid": "971122d8f37211eaadc10242ac120002",
                        "valor": "110.00",
                        "horario": "2020-09-09T20:15:00.358Z",
                        "pagador": {
                          "cpf": "01234567890",
                          "nome": "Nome Pagador"
                        },
                        "endToEndId": "E12345678202009091221abcdef12345"
                      }
                    }
                    JSON,
            ],

            [
                <<<JSON
                    {
                      "pix": {
                        "txid": "c3e0e7a4e7f1469a9f782d3d4999343c",
                        "valor": "110.00",
                        "horario": "2020-09-09T20:15:00.358Z",
                        "pagador": {
                          "cpf": "01234567890",
                          "nome": "Nome Pagador"
                        },
                        "devolucoes": {
                          "id": "123ABC",
                          "rtrId": "D12345678202009091221abcdf098765",
                          "valor": "10.00",
                          "status": "DEVOLVIDO",
                          "horario": {
                            "liquidacao": "2020-09-09T20:15:00.358Z",
                            "solicitacao": "2020-09-09T20:15:00.358Z"
                          },
                          "natureza": "ORIGINAL"
                        },
                        "endToEndId": "E12345678202009091221abcdef12345"
                      }
                    }
                    JSON,
            ],

            [
                '{"endToEndId":"E22896431202501141959otgY0FXcngz","valor":"688.00","horario":"2025-01-14T20:00:18.079Z","txid":"fa54e10a296240c098b36ab855649a14","pagador":{"nome":"Wagner Bargmann","cpf":"00317813064"}}'
            ]
        ];
    }

    #[Test]
    #[DataProvider('itParsesRequestsProvider')]
    public function itParsesRequests(string $json): void
    {
        $request = new Request('post', '/test', body: $json);
        $pix = PixQueryResponse::fromRequest($request);
        $this->assertCount(1, $pix->returns);
        $this->assertTrue($pix->hasReturns());

        $returnInformation = $pix->returns[0];
        $this->assertEquals(ReturnStatus::Returned, $returnInformation->status);
    }

    public static function itParsesRequestsProvider(): array
    {
        return [
            [
                '{
  "pix": {
    "txid": "c3e0e7a4e7f1469a9f782d3d4999343c",
    "valor": "110.00",
    "horario": "2020-09-09T20:15:00.358Z",
    "pagador": {
      "cpf": "01234567890",
      "nome": "Nome Pagador"
    },
    "devolucoes": {
      "id": "123ABC",
      "rtrId": "D12345678202009091221abcdf098765",
      "valor": "10.00",
      "status": "DEVOLVIDO",
      "horario": {
        "liquidacao": "2020-09-09T20:15:00.358Z",
        "solicitacao": "2020-09-09T20:15:00.358Z"
      },
      "natureza": "ORIGINAL"
    },
    "endToEndId": "E12345678202009091221abcdef12345"
  }
}',
            ],
        ];
    }
}
