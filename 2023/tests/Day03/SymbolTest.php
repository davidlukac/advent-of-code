<?php

namespace AdventOfCode\Year2023\Tests\Day03;

use AdventOfCode\Year2023\Day03\Position;
use AdventOfCode\Year2023\Day03\Symbol;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Symbol::class)]
class SymbolTest extends TestCase
{
    public static function exceptionData(): array
    {
        return [
            [123, "Argument '123' is not a single character!"],
            ['---', "Argument '---' is not a single character!"],
            ['a', "Argument 'a' must be non-alphanumeric and non-whitespace."],
            ['1', "Argument '1' must be non-alphanumeric and non-whitespace."],
        ];
    }

    #[DataProvider('exceptionData')]
    public function testConstructExceptions($input, $exceptionMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);
        new Symbol($input, new Position(1, 2));
    }

    public function testSymbol()
    {
        $s = new Symbol('-', new Position(1, 1));
        $this->assertSame('-', $s->strval());
        $this->assertSame('-', $s->__toString());
    }
}
