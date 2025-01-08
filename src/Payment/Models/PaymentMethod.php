<?php

declare(strict_types=1);

namespace Samfelgar\Onz\Payment\Models;

enum PaymentMethod: string
{
    case Pix = 'PIX';
    case Billet = 'BILLET';
}
