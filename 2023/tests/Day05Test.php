<?php

namespace AdventOfCode\Year2023\Tests;

use AdventOfCode\Year2023\Day05;
use AdventOfCode\Year2023\exceptions\ParseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(Day05::class)]
class Day05Test extends TestCase
{
    public final const TEST_LINES_1 = [
        'seeds: 79 14 55 13',
        '',
        'seed-to-soil map:',
        '50 98 2',
        '52 50 48',
        '',
        'soil-to-fertilizer map:',
        '0 15 37',
        '37 52 2',
        '39 0 15',
        '',
        'fertilizer-to-water map:',
        '49 53 8',
        '0 11 42',
        '42 0 7',
        '57 7 4',
        '',
        'water-to-light map:',
        '88 18 7',
        '18 25 70',
        '',
        'light-to-temperature map:',
        '45 77 23',
        '81 45 19',
        '68 64 13',
        '',
        'temperature-to-humidity map:',
        '0 69 1',
        '1 0 69',
        '',
        'humidity-to-location map:',
        '60 56 37',
        '56 93 4',
    ];

    public static function firstData(): array
    {
        return [
            [self::TEST_LINES_1, 35],
        ];
    }

    /**
     * @throws Exception|ParseException
     */
    #[DataProvider('firstData')]
    public function testCalculateFirstStar(array $lines, $expectedResult)
    {
        $d = $this->createPartialMock(Day05::class, ['getLineData']);
        $d->method('getLineData')
            ->willReturn((function () use ($lines) {
                foreach ($lines as $line) {
                    yield $line;
                }
            })());

        $result = $d->calculateFirstStar();

        $this->assertSame($expectedResult, $result);
    }

    public function testCalculateSecondStar()
    {

    }
}
