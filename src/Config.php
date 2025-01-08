<?php

declare(strict_types=1);

namespace Samfelgar\Onz;

use Psr\Log\LoggerInterface;

readonly class Config
{
    public function __construct(
        public string $baseUri,
        public string $certificatePath,
        public string $keyPath,
        public ?LoggerInterface $logger = null,
    ) {}
}
