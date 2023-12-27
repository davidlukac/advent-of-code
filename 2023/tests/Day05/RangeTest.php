<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\Range;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Range::class)]
class RangeTest extends TestCase
{
    public static function data(): array
    {
        // int $start, int $end, int $length
        return [
            [1, 10, 10, 'Range 1->10 (length: 10)'],
            [10, 1, -10, 'Range 10->1 (length: -10)'],
            [-10, -1, 10, 'Range -10->-1 (length: 10)'],
            [-1, -10, -10, 'Range -1->-10 (length: -10)'],
            [-5, 4, 10, 'Range -5->4 (length: 10)'],
            [4, -5, -10, 'Range 4->-5 (length: -10)'],
            [1, 1, 1, 'Range 1->1 (length: 1)'],
            [-1, -1, 1, 'Range -1->-1 (length: 1)'],
        ];
    }

    #[DataProvider('data')]
    public function testFromLength(int $start, int $expectedEnd, int $length, string $representation)
    {
        $r = Range::fromLength($start, $length);
        $this->assertSame($expectedEnd, $r->end);
        $this->assertSame($representation, $r->__toString());
    }

    #[DataProvider('data')]
    public function testConstruct(int $start, int $end, int $expectedLength, string $representation)
    {
        $r = new Range($start, $end);
        $this->assertSame($expectedLength, $r->length);
        $this->assertSame($representation, $r->__toString());
    }

    public function testFailedFromLength()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Length can not be zero!');
        Range::fromLength(1, 0);
    }

    public static function containsData(): array
    {
        return [
            [10, 20, 9, false],
            [10, 20, 10, true],
            [10, 20, 15, true],
            [10, 20, 20, true],
            [10, 20, 21, false],
            [-10, -20, -9, false],
            [-10, -20, -10, true],
            [-10, -20, -15, true],
            [-10, -20, -20, true],
            [-10, -20, -21, false],
            [-5, 4, -6, false],
            [-5, 4, -5, true],
            [-5, 4, 0, true],
            [-5, 4, 4, true],
            [-5, 4, 5, false],
            [4, -5, -6, false],
            [4, -5, -5, true],
            [4, -5, 0, true],
            [4, -5, 4, true],
            [4, -5, 5, false],
        ];
    }

    #[DataProvider('containsData')]
    public function testContains(int $start, int $end, int $test, bool $expectedResult)
    {
        $r = new Range($start, $end);
        $this->assertSame($expectedResult, $r->contains($test));
    }
}
