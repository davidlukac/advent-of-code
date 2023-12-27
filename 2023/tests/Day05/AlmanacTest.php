<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\Almanac;
use AdventOfCode\Year2023\Day05\Map;
use AdventOfCode\Year2023\Day05\Mapping;
use AdventOfCode\Year2023\Day05\Range;
use AdventOfCode\Year2023\Day05\RangeSeed;
use AdventOfCode\Year2023\exceptions\InvalidMapTypeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Almanac::class)]
class AlmanacTest extends TestCase
{
    public static function seedsData(): array
    {
        // int[] $seeds
        // [int $seed, bool $isSeedInRangeSeed] $seedTests
        // expected RangeSeed[]
        return [
            [
                [],
                [[1, false]],
                [],
            ],
            [
                [1, 2, 3, 4],
                [[1, true], [2, true], [0, false], [3, true], [6, true], [7, false]],
                [RangeSeed::fromLength(1, 2), RangeSeed::fromLength(3, 4)],
            ],
            [
                [5, 10, 1, 10],
                [[4, true]],
                [RangeSeed::fromLength(1, 10), RangeSeed::fromLength(11, 4)],
            ],
        ];
    }

    #[DataProvider('seedsData')]
    public function testSetSeeds(array $seeds, array $seedTests, array $expectedRangeSeeds)
    {
        $a = new Almanac();
        $actual = $a->getSeeds();
        $this->assertSame([], $actual);

        $a->setSeeds($seeds);
        $actual = $a->getSeeds();
        $this->assertSame($seeds, $actual);

        foreach ($seedTests as $case) {
            $this->assertSame($case[1], $a->isSeedInRangeSeeds($case[1]));
        }

        $this->assertEquals($expectedRangeSeeds, $a->getRangeSeeds());
    }

    public static function mapData(): array
    {
        return [
            [
                [
                    Almanac::SEED_TO_SOIL_MAP => [[1, 10, 10], [31, 51, 2]],
                    Almanac::FERTILIZER_TO_WATER_MAP => [[21, 13, 2], [55, 60, 4]],
                ],
            ],
        ];
    }

    /**
     * @throws InvalidMapTypeException
     */
    #[DataProvider('mapData')]
    public function testMap(array $maps)
    {
        $a = new Almanac();
        foreach ($maps as $type => $ranges) {
            $m = new Map();
            foreach ($ranges as $range) {
                $m->addMapping(new Mapping(...$range));
            }
            $a->addMap($type, $m);
        }

        foreach ($maps as $type => $ranges) {
            $m = new Map();
            foreach ($ranges as $range) {
                $m->addMapping(new Mapping(...$range));
            }
            $this->assertEquals($m, $a->getMap($type));
        }
    }

    public static function mapExceptionData(): array
    {
        $mapTypesStr = implode(', ', Almanac::MAP_SEQUENCE);

        return [
            ['foo', '', "Type 'foo' is not one of [{$mapTypesStr}]."],
            [Almanac::SOIL_TO_FERTILIZER_MAP, 'foo', "Type 'foo' is not one of [{$mapTypesStr}]."],
        ];
    }

    #[DataProvider('mapExceptionData')]
    public function testMapType(string $mapType, string $getMapType, string $expectedExcMsg)
    {
        $this->expectException(InvalidMapTypeException::class);
        $this->expectExceptionMessage($expectedExcMsg);
        $a = new Almanac();
        $a->addMap($mapType, new Map());
        $a->getMap($getMapType);
    }

    public static function findLocationData(): array
    {
        // int[] $seeds, Map[] $maps with ranges, array $cases
        return [
            [
                [1, 2], [
                    Almanac::SEED_TO_SOIL_MAP => [[1, 10, 10], [31, 51, 2]],
                    Almanac::FERTILIZER_TO_WATER_MAP => [[21, 13, 2], [55, 60, 4]],
                ], [
                    [1, 10], [31, 51], [20, 20], [55, 60],
                ],
                10,
            ],
        ];
    }

    /**
     * @throws InvalidMapTypeException
     */
    #[DataProvider('findLocationData')]
    public function testFindLocation(array $seeds, array $maps, array $findLocationCases, int $lowestLocation)
    {
        $a = new Almanac();
        $a->setSeeds($seeds);

        foreach ($maps as $type => $ranges) {
            $m = new Map();
            foreach ($ranges as $range) {
                $m->addMapping(new Mapping(...$range));
            }
            $a->addMap($type, $m);
        }

        foreach ($findLocationCases as $case) {
            $this->assertSame($case[1], $a->findLocation($case[0]));
        }

        $this->assertSame($lowestLocation, $a->findLowestLocation());
    }

    public static function findLocationRangesData(): array
    {
        $seeds = [1, 2, 10, 2];
        $maps = [
            Almanac::SEED_TO_SOIL_MAP => [[1, 11, 2], [31, 51, 2]],
            Almanac::FERTILIZER_TO_WATER_MAP => [[21, 11, 2], [71, 21, 2]],
        ];

        // int[] $seeds,
        // Map[] $maps with ranges (source -> target, length),
        // case: input range -> output ranges[]
        // int $lowestLocation
        return [
            [
                $seeds,
                $maps,
                [
                    [1, 2],
                    [[11, 12]],
                ],
                10,
            ],
            [
                $seeds,
                $maps,
                [
                    [32, 33],
                    [[33, 33], [52, 52]],
                ],
                10,
            ],
        ];
    }

    /**
     * @throws InvalidMapTypeException
     */
    #[DataProvider('findLocationRangesData')]
    public function testFindLocationRanges(array $seeds, array $maps, array $findLocationCase, int $lowestLocation)
    {
        $a = new Almanac();
        $a->setSeeds($seeds);

        foreach ($maps as $type => $ranges) {
            $m = new Map();
            foreach ($ranges as $range) {
                $m->addMapping(new Mapping(...$range));
            }
            $a->addMap($type, $m);
        }

        $locationRanges = $a->findLocationRanges(new RangeSeed(...$findLocationCase[0]));

        $this->assertSame(count($findLocationCase[1]), count($locationRanges));

        for ($i = 0; $i < count($findLocationCase[1]); $i++) {
            $this->assertEquals(new Range(...$findLocationCase[1][$i]), $locationRanges[$i]);
        }

        $this->assertSame($lowestLocation, $a->findLowestLocationWithSeedRanges());
    }
}
