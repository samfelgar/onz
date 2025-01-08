<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

use Brick\Math\BigDecimal;

readonly class PaymentAmount
{
    public function __construct(
        public BigDecimal $amount,
        public string $currency = 'BRL',
    ) {}
}
