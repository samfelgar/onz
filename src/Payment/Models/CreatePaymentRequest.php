<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

use Samfelgar\PixValidator\PixInterface;
use Webmozart\Assert\Assert;

readonly class CreatePaymentRequest implements \JsonSerializable
{
    /**
     * @param string[] $ispbDeny
     */
    public function __construct(
        public PixInterface $pix,
        public string $idempotencyKey,
        public ?string $creditorDocument,
        public ?string $description,
        public PaymentFlow $paymentFlow,
        public int $expirationInSeconds,
        public PaymentAmount $paymentAmount,
        public array $ispbDeny = [],
        public Priority $priority = Priority::Normal,
    ) {
        if ($this->priority === Priority::High && $this->creditorDocument === null) {
            throw new \InvalidArgumentException('The creditor document must be set for high priority');
        }

        Assert::range($this->expirationInSeconds, 1, 10800);
        Assert::allString($this->ispbDeny);
    }

    public function jsonSerialize(): array
    {
        $data = [
            'pixKey' => $this->pix->value(),
            'creditorDocument' => $this->creditorDocument,
            'priority' => $this->priority->value,
            'description' => $this->description,
            'paymentFlow' => $this->paymentFlow->value,
            'expiration' => $this->expirationInSeconds,
            'payment' => [
                'currency' => $this->paymentAmount->currency,
                'amount' => $this->paymentAmount->amount->toFloat(),
            ],
        ];

        if ($this->ispbDeny !== []) {
            $data['ispbDeny'] = $this->ispbDeny;
        }

        return \array_filter($data, static fn(mixed $value): bool => $value !== null);
    }
}
