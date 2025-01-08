<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models\Webhooks;

use Psr\Http\Message\RequestInterface;
use Samfelgar\Onz\Payment\Models\TransactionDetails;

class TransferWebhook
{
    public function __construct(
        public readonly TransactionDetails $transactionDetails,
        public readonly WebhookType $type,
    ) {}

    public static function fromRequest(RequestInterface $request): TransferWebhook
    {
        $parsed = \json_decode((string)$request->getBody(), true);

        return new TransferWebhook(
            TransactionDetails::fromArray($parsed['data']),
            WebhookType::from($parsed['type']),
        );
    }
}
