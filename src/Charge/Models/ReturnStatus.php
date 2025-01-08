<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Charge\Models;

enum ReturnStatus: string
{
    case Processing = 'EM_PROCESSAMENTO';
    case Returned = 'DEVOLVIDO';
    case NotSettled = 'NAO_REALIZADO';
}
