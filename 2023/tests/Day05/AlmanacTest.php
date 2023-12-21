<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\Almanac;
use AdventOfCode\Year2023\Day05\Map;
use AdventOfCode\Year2023\Day05\Range;
use AdventOfCode\Year2023\exceptions\InvalidMapTypeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Almanac::class)]
class AlmanacTest extends TestCase
{
    public static function seedsData(): array
    {
        return [
            [[]],
            [[1, 2, 3]],
        ];
    }

    #[DataProvider('seedsData')]
    public function testSetSeeds(array $seeds)
    {
        $a = new Almanac();
        $actual = $a->getSeeds();
        $this->assertSame([], $actual);

        $a->setSeeds($seeds);
        $actual = $a->getSeeds();
        $this->assertSame($seeds, $actual);
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
                $m->addMapRange(new Range(...$range));
            }
            $a->addMap($type, $m);
        }

        foreach ($maps as $type => $ranges) {
            $m = new Map();
            foreach ($ranges as $range) {
                $m->addMapRange(new Range(...$range));
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
                [1], [
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
                $m->addMapRange(new Range(...$range));
            }
            $a->addMap($type, $m);
        }

        foreach ($findLocationCases as $case) {
            $this->assertSame($case[1], $a->findLocation($case[0]));
        }

        $this->assertSame($lowestLocation, $a->findLowestLocation());
    }
}
