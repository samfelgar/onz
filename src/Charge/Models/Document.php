<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

interface Document
{
    public function key(): string;

    public function value(): string;
}
