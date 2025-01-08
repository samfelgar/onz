<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models\Webhooks;

enum WebhookType: string
{
    case Transfer = 'TRANSFER';
    case Receive = 'RECEIVE';
    case Refund = 'REFUND';
    case CashOut = 'CASHOUT';
    case Infraction = 'INFRACTION';
}
