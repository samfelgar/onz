<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Auth\Models;

enum Scope: string
{
    case PixRead = 'pix.read';
    case PixWrite = 'pix.write';
    case BilletsRead = 'billets.read';
    case BilletsWrite = 'billets.write';
    case WebhookRead = 'webhook.read';
    case WebhookWrite = 'webhook.write';
    case Transactions = 'transactions';
    case Account = 'account.read';
    case InfractionsRead = 'infractions.read';
    case InfractionsWrite = 'infractions.write';
    case ApiAccount = 'api-account';
}
