<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models\Webhooks;

use Psr\Http\Message\RequestInterface;
use Samfelgar\Onz\Payment\Models\Status;
use Samfelgar\Onz\Payment\Models\TransactionDetails;

class TransferWebhook
{
    public function __construct(
        public readonly ?TransactionDetails $transactionDetails,
        public readonly WebhookType $type,
        public readonly Status $status,
        public readonly ?string $idempotencyKey,
        public readonly ?string $endToEndId,
        public readonly ?string $message,
    ) {}

    public static function fromRequest(RequestInterface $request): TransferWebhook
    {
        $parsed = \json_decode((string)$request->getBody(), true);

        $rawStatus = $parsed['data']['status'] ?? null;

        if ($rawStatus === null) {
            throw new \InvalidArgumentException('invalid request');
        }

        $status = Status::from($rawStatus);

        $details = null;

        if ($status !== Status::Rejected) {
            $details = TransactionDetails::fromArray($parsed['data']);
        }

        return new TransferWebhook(
            $details,
            WebhookType::from($parsed['type']),
            $status,
            $parsed['data']['idempotencyKey'],
            $parsed['data']['endToEndId'],
            $parsed['data']['message'] ?? null,
        );
    }
}
