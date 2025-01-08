<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

enum ReturnNature: string
{
    case Original = 'ORIGINAL';
    case Retrieval = 'RETIRADA';
    case Operational = 'MED_OPERACIONAL';
    case Fraud = 'MED_FRAUDE';
}
