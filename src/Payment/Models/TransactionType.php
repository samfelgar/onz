<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

enum TransactionType: string
{
    case Credit = 'CREDIT';
    case Debit = 'DEBIT';
}
