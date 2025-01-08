<?php

declare(strict_types=1);

namespace Samfelgar\Onz;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Samfelgar\Onz\Auth\Auth;
use Samfelgar\Onz\Auth\Models\AuthResponse;
use Samfelgar\Onz\Charge\Charge;
use Samfelgar\Onz\Payment\Payment;

class Onz
{
    public function __construct(
        private readonly Client $client,
    ) {}

    public static function instance(Config $config): Onz
    {
        $handler = HandlerStack::create();

        if ($config->logger !== null) {
            $handler->push(Middleware::log($config->logger, new MessageFormatter(MessageFormatter::DEBUG)));
        }

        $client = new Client([
            'cert' => $config->certificatePath,
            'ssl_key' => $config->keyPath,
            'handler' => $handler,
            'timeout' => 5,
        ]);

        return new Onz($client);
    }

    public function authentication(): Auth
    {
        return new Auth($this->client);
    }

    public function charge(AuthResponse $auth): Charge
    {
        return new Charge($this->client, $auth);
    }

    public function payment(AuthResponse $auth): Payment
    {
        return new Payment($this->client, $auth);
    }
}
