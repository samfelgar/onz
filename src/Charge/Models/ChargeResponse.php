<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

use Brick\Math\BigDecimal;
use Psr\Http\Message\ResponseInterface;

readonly class ChargeResponse
{
    public function __construct(
        public string $txId,
        public \DateTimeImmutable $createdAt,
        public int $expirationInSeconds,
        public int $revision,
        public string $location,
        public string $status,
        public ?Payer $payer,
        public Amount $amount,
        public string $pixKey,
        public ?string $payerRequestText,
        public ?string $emv,
    ) {}

    public static function fromArray(array $data): ChargeResponse
    {
        $createdAt = null;
        $dateFormats = [
            \DateTimeInterface::ATOM,
            \DateTimeInterface::RFC3339_EXTENDED,
        ];

        foreach ($dateFormats as $format) {
            $_date = \DateTimeImmutable::createFromFormat(
                $format,
                $data['calendario']['criacao'],
            );

            if ($_date !== false) {
                $createdAt = $_date;
                break;
            }
        }

        if ($createdAt === null) {
            throw new \InvalidArgumentException('Invalid creation date');
        }

        $payer = null;
        if (!empty($data['devedor'])) {
            $payer = new Payer(
                isset($data['devedor']['cnpj']) ? new Cnpj($data['devedor']['cnpj']) : new Cpf($data['devedor']['cpf']),
                $data['devedor']['nome'],
                \array_map(static function (array $account): Account {
                    return new Account(
                        $account['numero'],
                        $account['agencia'],
                        $account['ispb'],
                    );
                }, $data['devedor']['contas'] ?? []),
            );
        }

        return new ChargeResponse(
            $data['txid'],
            $createdAt,
            $data['calendario']['expiracao'],
            $data['revisao'],
            $data['location'],
            $data['status'],
            $payer,
            new Amount(BigDecimal::of($data['valor']['original']), $data['valor']['modalidadeAlteracao'] === 1),
            $data['chave'],
            $data['solicitacaoPagador'] ?? null,
            $data['pixCopiaECola'] ?? null,
        );
    }

    public static function fromResponse(ResponseInterface $response): ChargeResponse
    {
        $data = \json_decode((string)$response->getBody(), true);
        return self::fromArray($data);
    }
}
