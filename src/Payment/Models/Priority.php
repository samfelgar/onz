<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

enum Priority: string
{
    case High = 'HIGH';
    case Normal = 'NORM';
}
