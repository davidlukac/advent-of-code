<?php

namespace AdventOfCode\Year2023\Tests;

use AdventOfCode\Year2023\Day03;
use AdventOfCode\Year2023\exceptions\InvalidSchematicsException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(Day03::class)]
class Day03Test extends TestCase
{
    public final const TEST_LINES = [
        '467..114..',
        '...*......',
        '..35..633.',
        '......#...',
        '617*......',
        '.....+.58.',
        '..592.....',
        '......755.',
        '...$.*....',
        '.664.598..',
    ];

    public final const TEST_LINES_2 = [
        '....32',
        '5..-..',
        '.205..',
    ];

    public final const TEST_LINES_3 = [
        '12.......*..',
        '+.........34',
        '.......-12..',
        '..78........',
        '..*....60...',
        '78.........9',
        '.5.....23..$',
        '8...90*12...',
        '............',
        '2.2......12.',
        '.*.........*',
        '1.1..503+.56',
    ];

    public final const TEST_LINES_4 = [
        '12.......*..',
        '+.........34',
        '.......-12..',
        '..78........',
        '..*....60...',
        '78..........',
        '.......23...',
        '....90*12...',
        '............',
        '2.2......12.',
        '.*.........*',
        '1.1.......56',
    ];

    public final const TEST_LINES_5 = [
        '....................',
        '..-52..52-..52..52..',
        '..................-.',
    ];

    public final const TEST_LINES_6 = [
        '.......................*......*',
        '...910*...............233..189.',
        '2......391.....789*............',
        '...................983.........',
        '0........106-...............226',
        '.%............................$',
        '...*......$812......812..851...',
        '.99.711.............+.....*....',
        '...........................113.',
        '28*.....411....%...............',
    ];

    public static function getData(): array
    {
        return [
            [self::TEST_LINES, 4361],
            [self::TEST_LINES_2, 237],
            [self::TEST_LINES_3, 925],
            [self::TEST_LINES_4, 413],
            [self::TEST_LINES_5, 156],
            [self::TEST_LINES_6, 7253],
        ];
    }

    /**
     * @throws Exception|InvalidSchematicsException
     */
    #[DataProvider('getData')]
    public function testCalculateFirstStar(array $lines, int $sum)
    {
        $d = $this->createPartialMock(Day03::class, ['getLineData']);
        $d->method('getLineData')
            ->willReturn((function () use ($lines) {
                foreach ($lines as $line) {
                    yield $line;
                }
            })());

        $res = $d->calculateFirstStar();

        $this->assertSame($sum, $res);
    }

    //    public function testCalculateSecondStar()
    //    {
    //
    //    }
}
