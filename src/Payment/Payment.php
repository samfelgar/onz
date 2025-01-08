<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Onz\Auth\Models\AuthResponse;
use Samfelgar\Onz\Payment\Models\CreatePaymentRequest;
use Samfelgar\Onz\Payment\Models\PaymentResponse;
use Samfelgar\Onz\Payment\Models\TransactionDetails;

class Payment
{
    public final const BASE_URI = 'httpS://secureapi.ecomovi-prod.onz.software';

    public function __construct(
        private readonly Client $client,
        private readonly AuthResponse $auth,
    ) {}

    private function uri(string $path): string
    {
        return \sprintf('%s/%s', self::BASE_URI, \ltrim($path, '/'));
    }

    /**
     * @throws GuzzleException
     */
    public function createPixPayment(CreatePaymentRequest $request): PaymentResponse
    {
        $response = $this->client->post($this->uri('/api/v2/pix/payments/dict'), [
            'headers' => [
                'authorization' => $this->auth->authorizationHeader(),
                'x-idempotency-key' => $request->idempotencyKey,
            ],
            'json' => $request,
        ]);
        return PaymentResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getTransactionByEndToEndId(string $endToEndId): TransactionDetails
    {
        $response = $this->client->get($this->uri("/api/v2/pix/payments/{$endToEndId}"), [
            'headers' => [
                'authorization' => $this->auth->authorizationHeader(),
            ],
        ]);
        return TransactionDetails::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getTransactionByIdempotencyKey(string $idempotencyKey): TransactionDetails
    {
        $response = $this->client->get($this->uri("/api/v2/pix/payments/idempotencyKey/{$idempotencyKey}"), [
            'headers' => [
                'authorization' => $this->auth->authorizationHeader(),
            ],
        ]);
        return TransactionDetails::fromResponse($response);
    }
}
