<?php

namespace AdventOfCode\Year2023\Tests\Day02;

use AdventOfCode\Year2023\Day02\SetFactory;
use AdventOfCode\Year2023\exceptions\ParseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SetFactory::class)]
class SetFactoryTest extends TestCase
{
    public static function setDataProvider(): array
    {
        return [
            ['3 blue, 4 red', 4, 0, 3],
            ['1 red, 2 green, 6 blue', 1, 2, 6],
            ['2 green', 0, 2, 0],
            ['1 blue, 2 green', 0, 2, 1],
            ['8 green, 6 blue, 20 red', 20, 8, 6],
        ];
    }

    /**
     * @throws ParseException
     */
    #[DataProvider('setDataProvider')]
    public function testParseSet(string $line, int $red, int $green, int $blue)
    {
        $set = SetFactory::parse($line);
        $this->assertSame($red, $set->red);
        $this->assertSame($green, $set->green);
        $this->assertSame($blue, $set->blue);
    }

    public static function parseExceptionData(): array
    {
        return [
            ['asd', "String 'asd' can not be parsed into count and color!"],
            ['3 green, 1 violet', "Color 'violet' is not valid. Valid options are: 'red, green, blue'."],
        ];
    }

    #[DataProvider('parseExceptionData')]
    public function testParseExceptions(string $line, string $exceptionMessage)
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage($exceptionMessage);
        SetFactory::parse($line);
    }
}
