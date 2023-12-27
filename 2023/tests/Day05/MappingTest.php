<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\Mapping;
use AdventOfCode\Year2023\Day05\Range;
use OutOfRangeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Mapping::class)]
class MappingTest extends TestCase
{
    public static function constructData(): array
    {
        // array $construct, string $representation
        return [
            [[50, 98, 2], 'Mapping (50, 51)->(98, 99) (diff: 48, length: 2)'],
            [[52, 50, 48], 'Mapping (52, 99)->(50, 97) (diff: -2, length: 48)'],
        ];
    }

    #[DataProvider('constructData')]
    public function testConstruct(array $construct, string $representation)
    {
        $m = new Mapping(...$construct);
        $this->assertSame($construct[0], $m->sourceRange->start);
        $this->assertSame($construct[1], $m->targetRange->start);
        $this->assertSame($construct[2], $m->rangeLength);
        $this->assertEquals(new Range($construct[0], $construct[0] + $construct[2] - 1), $m->sourceRange);
        $this->assertEquals(new Range($construct[1], $construct[1] + $construct[2] - 1), $m->targetRange);
        $this->assertSame($representation, $m->__toString());
    }

    public static function mapData(): array
    {
        // array $construct - > ($sourceStart, $targetStart, $length), array $cases -> ($source -> $expectedTarget)
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
        $r = new Mapping(...$construct);
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
        $r = new Mapping(...$construct);
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
        $r = new Mapping(...$construct);

        foreach ($cases as $case) {
            try {
                $r->get($case);
                $this->fail('Exception was not thrown!');
            } catch (OutOfRangeException $e) {
                $this->assertInstanceOf(OutOfRangeException::class, $e);
            }
        }
    }

    public static function getDefaultData(): array
    {
        // int[] $mappingConstruct
        // ($source, $target)[]
        return [
            [
                [1, 11, 10],
                [[1, 11], [10, 20], [0, null], [11, null]],
            ],
        ];
    }

    #[DataProvider('getDefaultData')]
    public function testMapDefault(array $construct, array $cases)
    {
        $r = new Mapping(...$construct);

        foreach ($cases as $case) {
            $this->assertSame($case[1], $r->getDefault($case[0]));
        }
    }

    public static function containsData(): array
    {
        return [
            [1, 10, 2, 1, true],
            [1, 10, 2, 2, true],
            [1, 10, 2, 0, false],
            [1, 10, 2, 3, false],
        ];
    }

    #[DataProvider('containsData')]
    public function testContains(int $sourceStart, int $targetStart, int $length, int $test, $expectedContains)
    {
        $r = new Mapping($sourceStart, $targetStart, $length);
        $this->assertSame($expectedContains, $r->contains($test));
    }
}
