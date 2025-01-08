<?php

namespace Samfelgar\Onz\Tests\Charge\Models;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Onz\Charge\Models\Cpf;
use PHPUnit\Framework\TestCase;

#[CoversClass(Cpf::class)]
class CpfTest extends TestCase
{
    #[Test]
    #[DataProvider('itRemovesNonNumericalCharactersProvider')]
    public function itRemovesNonNumericalCharacters(string $cpf, string $expected): void
    {
        $document = new Cpf($cpf);
        $this->assertEquals($expected, $document->value());
    }

    public static function itRemovesNonNumericalCharactersProvider(): array
    {
        return [
            ['755.726.861-07', '75572686107'],
            ['327.629.773-63', '32762977363'],
            ['260.278.586-56', '26027858656'],
        ];
    }

    #[Test]
    #[DataProvider('itValidatedTheStringLengthProvider')]
    public function itValidatedTheStringLength(string $document): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Cpf($document);
    }

    public static function itValidatedTheStringLengthProvider(): array
    {
        return [
            ['755.726.861-0'],
            ['327.629.773'],
            ['260.278-56'],
        ];
    }
}
