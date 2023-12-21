<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\Range;
use OutOfRangeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Range::class)]
class RangeTest extends TestCase
{
    public static function constructData(): array
    {
        return [
            [[50, 98, 2]],
            [[52, 50, 48]],
        ];
    }

    #[DataProvider('constructData')]
    public function testConstruct(array $range)
    {
        $r = new Range(...$range);
        $this->assertSame($range[0], $r->sourceStart);
        $this->assertSame($range[1], $r->destStart);
        $this->assertSame($range[2], $r->rangeLength);
    }

    public static function mapData(): array
    {
        // array $construct, array $cases
        return [
            [[1, 11, 10], [[1, 11], [5, 15]]],
            [[11, 1, 10], [[11, 1], [15, 5]]],
            [[-1, -11, 10], [[-1, -11], [1, -9]]],
            [[-11, -21, 10], [[-9, -19]]],
        ];
    }

    #[DataProvider('mapData')]
    public function testMap(array $construct, array $cases)
    {
        $r = new Range(...$construct);
        foreach ($cases as $case) {
            $this->assertSame($case[1], $r->get($case[0]));
        }
    }

    public static function sourceRangeStrData(): array
    {
        return [
            [[1, 11, 10], '1:10'],
            [[11, 1, 10], '11:20'],
            [[-1, -11, 10], '-1:8'],
            [[-11, -21, 2], '-11:-10'],
        ];
    }

    #[DataProvider('sourceRangeStrData')]
    public function testSourceRangeStr(array $construct, string $expected)
    {
        $r = new Range(...$construct);
        $this->assertSame($expected, $r->getSourceRangeStr());
    }

    public static function outOfBoundsData(): array
    {
        return [
            [[1, 11, 10], [0, 11]],
            [[-10, 11, 2], [-11, -12]],
        ];
    }

    #[DataProvider('outOfBoundsData')]
    public function testMapException(array $construct, array $cases)
    {
        $r = new Range(...$construct);

        foreach ($cases as $case) {
            try {
                $r->get($case);
                $this->fail('Exception was not thrown!');
            } catch (OutOfRangeException $e) {
                $this->assertInstanceOf(OutOfRangeException::class, $e);
            }
        }
    }

    #[DataProvider('outOfBoundsData')]
    public function testMapDefault(array $construct, array $cases)
    {
        $r = new Range(...$construct);

        foreach ($cases as $case) {
            $this->assertSame(null, $r->getDefault($case));
        }
    }
}
