<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

use Webmozart\Assert\Assert;

class Cpf implements Document
{
    private readonly string $cpf;

    public function __construct(
        string $cpf,
    ) {
        $cpf = \preg_replace('/\D/', '', $cpf);
        Assert::length($cpf, 11);
        $this->cpf = $cpf;
    }

    public function key(): string
    {
        return 'cpf';
    }

    public function value(): string
    {
        return $this->cpf;
    }
}
