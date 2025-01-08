<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

use Brick\Math\BigDecimal;

class ReturnInformation
{
    public function __construct(
        public string $id,
        public string $returnId,
        public BigDecimal $amount,
        public ReturnNature $nature,
        public ?string $description,
        public ?\DateTimeImmutable $requestedAt,
        public ?\DateTimeImmutable $settledAt,
        public ReturnStatus $status,
        public ?string $reason,
    ) {}

    public static function fromArray(array $data): ReturnInformation
    {
        $nature = ReturnNature::tryFrom($data['natureza'] ?? 'ORIGINAL') ?? ReturnNature::Original;
        $status = ReturnStatus::from($data['status']);

        $requestedAt = null;
        if (isset($data['horario']['solicitacao'])) {
            $requestedAt = \DateTimeImmutable::createFromFormat(
                \DateTimeInterface::RFC3339_EXTENDED,
                $data['horario']['solicitacao'],
            );
        }

        $settledAt = null;
        if (isset($data['horario']['liquidacao'])) {
            $settledAt = \DateTimeImmutable::createFromFormat(
                \DateTimeInterface::RFC3339_EXTENDED,
                $data['horario']['liquidacao'],
            );
        }

        return new ReturnInformation(
            $data['id'],
            $data['rtrId'],
            BigDecimal::of($data['valor']),
            $nature,
            $data['descricao'] ?? null,
            $requestedAt,
            $settledAt,
            $status,
            $data['motivo'] ?? null,
        );
    }
}
