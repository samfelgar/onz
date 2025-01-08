<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

enum Status: string
{
    case Canceled = 'CANCELED';
    case Processing = 'PROCESSING';
    case Liquidated = 'LIQUIDATED';
    case Refunded = 'REFUNDED';
    case PartiallyRefunded = 'PARTIALLY_REFUNDED';
}
