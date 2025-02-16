<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

use Brick\Math\BigDecimal;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;

readonly class PixQueryResponse
{
    /**
     * @param ReturnInformation[] $returns
     */
    public function __construct(
        public string $endToEndId,
        public string $txId,
        public BigDecimal $amount,
        public \DateTimeImmutable $datetime,
        public ?string $payerInfo,
        public ?Payer $payer,
        public array $returns,
    ) {
        Assert::allIsInstanceOf($this->returns, ReturnInformation::class);
    }

    public static function fromArray(array $data): PixQueryResponse
    {
        $datetime = \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $data['horario']);

        $returns = [];

        if (isset($data['devolucoes']) && \array_is_list($data['devolucoes'])) {
            $returns = \array_map(static function (array $returnInformation): ReturnInformation {
                return ReturnInformation::fromArray($returnInformation);
            }, $data['devolucoes']);
        }

        if (isset($data['devolucoes']) && !\array_is_list($data['devolucoes'])) {
            $returns[] = ReturnInformation::fromArray($data['devolucoes']);
        }

        $getPayer = static function (?array $payer): ?Payer {
            if ($payer === null) {
                return null;
            }

            $document = null;

            if (isset($payer['cpf'])) {
                $document = new Cpf($payer['cpf']);
            } elseif (isset($payer['cnpj'])) {
                $document = new Cnpj($payer['cnpj']);
            }

            if ($document === null) {
                return null;
            }

            return new Payer($document, $payer['nome'], []);
        };

        $payer = $getPayer($data['pagador'] ?? null);

        return new PixQueryResponse(
            $data['endToEndId'],
            $data['txid'],
            BigDecimal::of($data['valor']),
            $datetime,
            $data['infoPagador'] ?? null,
            $payer,
            $returns,
        );
    }

    public static function fromResponse(ResponseInterface $response): PixQueryResponse
    {
        $data = \json_decode((string)$response->getBody(), true);
        return self::fromArray($data);
    }

    public static function fromRequest(RequestInterface $request): PixQueryResponse
    {
        $parsed = \json_decode((string)$request->getBody(), true);
        $data = $parsed['pix'] ?? $parsed;
        return self::fromArray($data);
    }

    public function hasReturns(): bool
    {
        return $this->returns !== [];
    }
}
