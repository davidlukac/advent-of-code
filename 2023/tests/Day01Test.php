<?php

declare(strict_types=1);

namespace AdventOfCode\Year2023\Tests;

use AdventOfCode\Year2023\Day01;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(Day01::class)]
final class Day01Test extends TestCase
{
    public final const LINES_ONE = [
        '1abc2' => 12,
        'pqr3stu8vwx' => 38,
        'a1b2c3d4e5f' => 15,
        'treb7uchet' => 77,
    ];

    public final const LINES_TWO = [
        'two1nine' => 29,
        'eightwothree' => 83,
        'abcone2threexyz' => 13,
        'xtwone3four' => 24,
        '4nineeightseven2' => 42,
        'zoneight234' => 14,
        '7pqrstsixteen' => 76,
        'one' => 11,
        '1' => 11,
        'foo1bar' => 11,
        '1one' => 11,
        '1one1' => 11,
        'foo1one1bar' => 11,
        'foo1two3fourbar' => 14,
        'eighthree' => 83,
        'sevenine' => 79,
    ];

    public static function extractDigitProvider(): array
    {
        return array_map(
            null,
            array_map('strval', array_keys(self::LINES_ONE)),
            array_values(self::LINES_ONE)
        );
    }

    #[DataProvider('extractDigitProvider')]
    public function testExtractDigits(string $line, int $expected): void
    {
        $d = new Day01('inputs/01.txt');
        $this->assertSame($expected, $d->extractDigits($line));
    }

    /**
     * @throws Exception
     */
    public function testCalculateFirstStar(): void
    {
        $d = $this->createPartialMock(Day01::class, ['getLineData']);
        $d->method('getLineData')
            ->willReturn((function () {
                foreach (array_keys(self::LINES_ONE) as $line) {
                    yield $line;
                }
            })());

        $res = $d->calculateFirstStar();

        $this->assertSame(142, $res);
    }

    public static function extractWordDigitsProvider(): array
    {
        return array_map(null, array_map('strval', array_keys(self::LINES_TWO)), array_values(self::LINES_TWO));
    }

    #[DataProvider('extractWordDigitsProvider')]
    public function testExtractWordDigits(string $line, int $expected): void
    {
        $d = new Day01('inputs/01.txt');
        $this->assertSame($expected, $d->extractWordDigits($line));
    }

    /**
     * @throws Exception
     */
    public function testCalculateSecondStar(): void
    {
        $d = $this->createPartialMock(Day01::class, ['getLineData']);
        $d->method('getLineData')
            ->willReturn((function () {
                foreach (array_keys(self::LINES_TWO) as $line) {
                    yield strval($line);
                }
            })());

        $this->assertSame(array_sum(self::LINES_TWO), $d->calculateSecondStar());
    }
}
