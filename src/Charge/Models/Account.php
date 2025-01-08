<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

readonly class Account
{
    public function __construct(
        public string $account,
        public string $branch,
        public string $ispb,
    ) {}
}
