<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Onz\Payment\Auth\Auth;
use Samfelgar\Onz\Payment\Auth\Models\AuthResponse;
use Samfelgar\Onz\Payment\Models\CreatePaymentRequest;
use Samfelgar\Onz\Payment\Models\PaymentResponse;
use Samfelgar\Onz\Payment\Models\TransactionDetails;

class Payment
{
    private ?AuthResponse $auth = null;

    public function __construct(
        private readonly Client $client,
    ) {}

    public function authentication(): Auth
    {
        return new Auth($this->client);
    }

    public function setAuth(AuthResponse $auth): void
    {
        $this->auth = $auth;
    }

    private function assertAuthentication(): void
    {
        if ($this->auth === null) {
            throw new \RuntimeException('You must authenticate first');
        }
    }

    /**
     * @throws GuzzleException
     */
    public function createPixPayment(CreatePaymentRequest $request): PaymentResponse
    {
        $this->assertAuthentication();
        $response = $this->client->post('/api/v2/pix/payments/dict', [
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
        $this->assertAuthentication();
        $response = $this->client->get("/api/v2/pix/payments/{$endToEndId}", [
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
        $this->assertAuthentication();
        $response = $this->client->get("/api/v2/pix/payments/idempotencyKey/{$idempotencyKey}", [
            'headers' => [
                'authorization' => $this->auth->authorizationHeader(),
            ],
        ]);
        return TransactionDetails::fromResponse($response);
    }
}
