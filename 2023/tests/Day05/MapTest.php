<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\Map;
use AdventOfCode\Year2023\Day05\Mapping;
use AdventOfCode\Year2023\Day05\Range;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Map::class)]
class MapTest extends TestCase
{
    public static function getData(): array
    {
        // array $ranges,
        // array $cases,
        // array $reverseCases,
        return [
            [
                [[1, 11, 10], [31, 101, 2]],
                [[1, 11], [31, 101], [10, 20], [32, 102]],
            ],
            [
                [[1, 11, 10], [31, 101, 2]],
                [[0, 0], [11, 11], [30, 30], [33, 33]],
            ],
        ];
    }

    #[DataProvider('getData')]
    public function testGet(array $ranges, array $cases)
    {
        $m = new Map();
        foreach ($ranges as $range) {
            $m->addMapping(new Mapping(...$range));
        }

        foreach ($cases as $case) {
            $this->assertSame($case[1], $m->get($case[0]));
        }
    }

    public static function mapRangeData(): array
    {
        // Mapping[] $mappings
        // Range $source
        // Range[] $expected
        return [
            [
                [],
                [11, 15],
                [[11, 15]],
            ],
            [
                [[11, 111, 10], [31, 131, 10]],
                [11, 15],
                [[111, 115]],
            ],
            [
                [[11, 111, 10], [31, 131, 10]],
                [1, 3],
                [[1, 3]],
            ],
            [
                [[11, 111, 10], [31, 131, 10]],
                [10, 12],
                [[10, 10], [111, 112]],
            ],
            [
                [[11, 111, 10], [31, 131, 10]],
                [19, 32],
                [[119, 120], [21, 30], [131, 132]],
            ],
        ];
    }

    #[DataProvider('mapRangeData')]
    public function testMapRange(array $mappings, array $source, array $expected)
    {
        $m = new Map();

        foreach ($mappings as $mapping) {
            $m->addMapping(new Mapping(...$mapping));
        }

        $mapped = $m->mapRange(new Range(...$source));

        $this->assertSame(count($expected), count($mapped));

        for ($i = 0; $i < count($expected); $i++) {
            $expectedRange = new Range(...$expected[$i]);
            $this->assertEquals($expectedRange, $mapped[$i]);
        }
    }

    public static function mapRangesData(): array
    {
        // Mapping[] $mappings
        // Range $source
        // Range[] $expected
        return [
            [
                [],
                [[11, 15], [50, 55]],
                [[11, 15], [50, 55]],
            ],
            [
                [[11, 111, 10], [31, 131, 10]],
                [[11, 15], [32, 35]],
                [[111, 115], [132, 135]],
            ],
            [
                [[11, 111, 10], [31, 131, 10]],
                [[1, 3], [21, 25], [150, 155]],
                [[1, 3], [21, 25], [150, 155]],
            ],
            [
                [[11, 111, 10], [31, 131, 10]],
                [[10, 12], [38, 42]],
                [[10, 10], [111, 112], [138, 140], [41, 42]],
            ],
            [
                [[11, 111, 10], [31, 131, 10]],
                [[19, 32]],
                [[119, 120], [21, 30], [131, 132]],
            ],
        ];
    }

    #[DataProvider('mapRangesData')]
    public function testMapRanges(array $mappings, array $sources, array $expected)
    {
        $m = new Map();

        foreach ($mappings as $mapping) {
            $m->addMapping(new Mapping(...$mapping));
        }

        $sourceRanges = [];

        foreach ($sources as $source) {
            $sourceRanges[] = new Range(...$source);
        }

        $mappedRanges = $m->mapRanges($sourceRanges);

        $this->assertSame(count($expected), count($mappedRanges));

        for ($i = 0; $i < count($expected); $i++) {
            $expectedRange = new Range(...$expected[$i]);
            $this->assertEquals($expectedRange, $mappedRanges[$i]);
        }
    }
}
