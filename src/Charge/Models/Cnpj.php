<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

use Webmozart\Assert\Assert;

class Cnpj implements Document
{
    private readonly string $cnpj;

    public function __construct(
        string $cnpj,
    ) {
        $cnpj = \preg_replace('/\D/', '', $cnpj);
        Assert::length($cnpj, 14);
        $this->cnpj = $cnpj;
    }

    public function key(): string
    {
        return 'cnpj';
    }

    public function value(): string
    {
        return $this->cnpj;
    }
}
