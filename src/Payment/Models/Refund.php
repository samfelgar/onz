<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

readonly class Refund
{
    public function __construct(
        public string $endToEndId,
        public Status $status,
        public ?string $errorCode,
        public PaymentAmount $refundAmount,
    ) {}
}
