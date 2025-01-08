<?php

namespace Samfelgar\Onz\Tests\Charge\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Onz\Charge\Models\Cnpj;

#[CoversClass(Cnpj::class)]
class CnpjTest extends TestCase
{
    #[Test]
    #[DataProvider('itRemovesNonNumericalCharactersProvider')]
    public function itRemovesNonNumericalCharacters(string $cnpj, string $expected): void
    {
        $document = new Cnpj($cnpj);
        $this->assertEquals($expected, $document->value());
    }

    public static function itRemovesNonNumericalCharactersProvider(): array
    {
        return [
            ['48.466.858/0001-72', '48466858000172'],
            ['47.837.419/0001-66', '47837419000166'],
            ['75.221.570/0001-00', '75221570000100'],
        ];
    }

    #[Test]
    #[DataProvider('itValidatedTheStringLengthProvider')]
    public function itValidatedTheStringLength(string $document): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Cnpj($document);
    }

    public static function itValidatedTheStringLengthProvider(): array
    {
        return [
            ['48.466.858/0001'],
            ['47.837.419'],
            ['75.221'],
            ['75.221.570/0001-000'],
        ];
    }
}
