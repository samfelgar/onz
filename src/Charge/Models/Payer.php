<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

use Webmozart\Assert\Assert;

readonly class Payer
{
    /**
     * @param Account[] $accounts
     */
    public function __construct(
        public Document $document,
        public string $name,
        public array $accounts,
    ) {
        Assert::allIsInstanceOf($this->accounts, Account::class);
    }
}
