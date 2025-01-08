<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

readonly class Account
{
    public function __construct(
        public string $ispb,
        public string $issuer,
        public string $number,
        public string $accountType,
        public string $document,
        public string $name,
    ) {}

    public static function fromArray(array $data): Account
    {
        return new Account(
            $data['ispb'],
            $data['issuer'],
            $data['number'],
            $data['accountType'],
            $data['document'],
            $data['name'],
        );
    }
}
