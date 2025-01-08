<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Auth\Models;

enum Scope: string
{
    case ChargeWrite = 'cob.write';
    case ChargeRead = 'cob.read';
    case ChargeDueDateWrite = 'cobv.write';
    case ChargeDueDateRead = 'cobv.read';
    case BatchChargeDueDateWrite = 'lotecobv.write';
    case BatchChargeDueDateRead = 'lotecobv.read';
    case PixWrite = 'pix.write';
    case PixRead = 'pix.read';
    case WebhookRead = 'webhook.read';
    case WebhookWrite = 'webhook.write';
    case PayloadLocationWrite = 'payloadlocation.write';
    case PayloadLocationRead = 'payloadlocation.read';
    case Email = 'email';
    case Profile = 'profile';
    case QrCodes = 'qrcodes';
}
