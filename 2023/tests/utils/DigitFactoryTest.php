<?php

namespace AdventOfCode\Year2023\Tests\utils;

use AdventOfCode\Year2023\exceptions\NotDigitException;
use AdventOfCode\Year2023\utils\DigitFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(DigitFactory::class)]
class DigitFactoryTest extends TestCase
{
    public static function parseProvider(): array
    {
        return [
            ['one', 1],
            ['two', 2],
            ['three', 3],
            ['four', 4],
            ['five', 5],
            ['six', 6],
            ['seven', 7],
            ['eight', 8],
            ['nine', 9],
            ['    nine             ', 9],
            ['    nIne             ', 9],
            ['    NINE             ', 9],
            ['    NiNe             ', 9],
        ];
    }

    /**
     * @throws NotDigitException
     */
    #[DataProvider('parseProvider')]
    public function testParse($s, $expected)
    {
        $this->assertSame($expected, DigitFactory::parse($s)::INT);
    }

    public function testParseException()
    {
        $this->expectException(NotDigitException::class);
        DigitFactory::parse('foo');
    }
}
