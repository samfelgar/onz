<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

enum PaymentFlow: string
{
    case Instant = 'INSTANT';
    case ApprovalRequired = 'APPROVAL_REQUIRED';
}
