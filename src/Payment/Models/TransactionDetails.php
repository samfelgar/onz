<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

use Brick\Math\BigDecimal;
use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;

readonly class TransactionDetails
{
    /**
     * @param Refund[] $refunds
     */
    public function __construct(
        public int $id,
        public string $idempotencyKey,
        public string $endToEndId,
        public string $pixKey,
        public PaymentMethod $paymentMethod,
        public Status $status,
        public ?string $errorCode,
        public TransactionType $type,
        public string $localInstrument,
        public \DateTimeImmutable $createdAt,
        public ?Account $creditor,
        public ?Account $debtor,
        public ?string $remittanceInformation,
        public string $txId,
        public PaymentAmount $paymentAmount,
        public array $refunds,
    ) {
        Assert::allIsInstanceOf($this->refunds, Refund::class);
    }

    public static function fromArray(array $data): TransactionDetails
    {
        $createdAt = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $data['createdAt']);

        $refunds = \array_map(static function (array $data): Refund {
            return new Refund(
                $data['endToEndId'],
                Status::from($data['status']),
                $data['errorCode'] ?? null,
                new PaymentAmount(
                    BigDecimal::of($data['pixRefundAmount']['amount']),
                    $data['pixRefundAmount']['currency'],
                ),
            );
        }, $data['refunds'] ?? []);

        return new TransactionDetails(
            $data['id'],
            $data['idempotencyKey'],
            $data['endToEndId'],
            $data['pixKey'],
            PaymentMethod::from($data['transactionType']),
            Status::from($data['status']),
            $data['errorCode'] ?? null,
            TransactionType::from($data['creditDebitType']),
            $data['localInstrument'],
            $createdAt,
            isset($data['creditorAccount']) ? Account::fromArray($data['creditorAccount']) : null,
            isset($data['debtorAccount']) ? Account::fromArray($data['debtorAccount']) : null,
            $data['remittanceInformation'] ?? null,
            $data['txId'],
            new PaymentAmount(
                BigDecimal::of($data['payment']['amount']),
                $data['payment']['currency'],
            ),
            $refunds,
        );
    }

    public static function fromResponse(ResponseInterface $response): TransactionDetails
    {
        $parsed = \json_decode((string)$response->getBody(), true);
        $data = $parsed['data'] ?? $parsed;
        return self::fromArray($data);
    }
}
