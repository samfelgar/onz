<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

use Brick\Math\BigDecimal;

class Amount
{
    public function __construct(
        public readonly BigDecimal $original,
        public readonly bool $canChange,
    ) {}
}
