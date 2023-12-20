<?php

namespace AdventOfCode\Year2023\Tests;

use AdventOfCode\Year2023\Day04;
use AdventOfCode\Year2023\exceptions\ParseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(Day04::class)]
class Day04Test extends TestCase
{
    public final const TEST_LINES = [
        'Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53',
        'Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19',
        'Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1',
        'Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83',
        'Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36',
        'Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11',
    ];

    public static function getData(): array
    {
        return [
            [self::TEST_LINES, 13],
        ];
    }

    /**
     * @throws Exception
     * @throws ParseException
     */
    #[DataProvider('getData')]
    public function testCalculateFirstStar(array $lines, int $expected)
    {
        $d = $this->createPartialMock(Day04::class, ['getLineData']);
        $d->method('getLineData')
            ->willReturn((function () use ($lines) {
                foreach ($lines as $line) {
                    yield $line;
                }
            })());

        $result = $d->calculateFirstStar();

        $this->assertSame($expected, $result);
    }

    public const TEST_LINES_2 = [
        'Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53',
        'Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19',
        'Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1',
        'Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83',
        'Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36',
        'Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11 ',
    ];

    public const TEST_LINES_3 = [
        'Card 1: 1 2 | 2 3',
        'Card 2: 1 2 | 3 4',
    ];

    public static function getDataTwo(): array
    {
        return [
            [self::TEST_LINES_2, 30],
            [self::TEST_LINES_3, 3],
        ];
    }

    /**
     * @throws Exception
     * @throws ParseException
     */
    #[DataProvider('getDataTwo')]
    public function testCalculateSecondStar(array $lines, int $expected)
    {
        $d = $this->createPartialMock(Day04::class, ['getLineData']);
        $d->method('getLineData')
            ->willReturn((function () use ($lines) {
                foreach ($lines as $line) {
                    yield $line;
                }
            })());

        $result = $d->calculateSecondStar();

        $this->assertSame($expected, $result);
    }
}
