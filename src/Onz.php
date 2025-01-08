<?php

declare(strict_types=1);

namespace Samfelgar\Onz;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Samfelgar\Onz\Charge\Charge;
use Samfelgar\Onz\Payment\Payment;

class Onz
{
    public function charge(Config $config): Charge
    {
        return new Charge($this->getClient($config));
    }

    public function payment(Config $config): Payment
    {
        return new Payment($this->getClient($config));
    }

    private function getClient(Config $config): Client
    {
        $handler = HandlerStack::create();

        if ($config->logger !== null) {
            $handler->push(Middleware::log($config->logger, new MessageFormatter(MessageFormatter::DEBUG)));
        }

        return new Client([
            'base_uri' => $config->baseUri,
            'cert' => $config->certificatePath,
            'ssl_key' => $config->keyPath,
            'handler' => $handler,
            'timeout' => 5,
        ]);
    }
}
