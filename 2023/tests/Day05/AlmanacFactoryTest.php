<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\Almanac;
use AdventOfCode\Year2023\Day05\AlmanacFactory;
use AdventOfCode\Year2023\Day05\AlmanacFactory as AF;
use AdventOfCode\Year2023\exceptions\InvalidMapTypeException;
use AdventOfCode\Year2023\exceptions\ParseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AlmanacFactory::class)]
class AlmanacFactoryTest extends TestCase
{
    public static function parseData(): array
    {
        // string $line, string $inputStage, string $expectedStage, int[] $expectedSeeds
        return [
            ['', AF::STG_START, AF::STG_UNKNOWN, []],
            ['', AF::STG_UNKNOWN, AF::STG_UNKNOWN, []],
            ['', AF::STG_MAPS, AF::STG_UNKNOWN, []],
            ['foo', AF::STG_START, AF::STG_UNKNOWN, []],
            ['  SeEds: 1 2 3 4', AF::STG_START, AF::STG_MAPS, [1, 2, 3, 4]],
            ['  SeEds:    1  234   2 3      333     444     ', AF::STG_START, AF::STG_MAPS, [1, 234, 2, 3, 333, 444]],
        ];
    }

    /**
     * @throws ParseException
     * @throws InvalidMapTypeException
     */
    #[DataProvider('parseData')]
    public function testParse(string $line, string $stage, string $expectedStage, $expectedSeeds)
    {
        $a = new Almanac();
        $newStage = AF::parse($line, $a, $stage);
        $this->assertSame($expectedStage, $newStage);
        $this->assertSame($expectedSeeds, $a->getSeeds());
    }

    public static function parseMapData(): array
    {
        // string $line, string $inputStage, string $expectedStage, int[] $expectedSeeds
        return [
            ['foo-to-bar map:', AF::STG_START, AF::STG_UNKNOWN],
            ['seed-to-soil map:', AF::STG_MAPS, Almanac::SEED_TO_SOIL_MAP],
            ['   seed-to-soil    map   :   ', AF::STG_MAPS, Almanac::SEED_TO_SOIL_MAP],
        ];
    }

    /**
     * @throws ParseException
     * @throws InvalidMapTypeException
     */
    #[DataProvider('parseMapData')]
    public function testParseMap(string $line, string $stage, string $expectedStage)
    {
        $a = new Almanac();
        $newStage = AF::parse($line, $a, $stage);
        $this->assertSame($expectedStage, $newStage);
    }

    public static function parseMapDataData(): array
    {
        // string $line, string $inputStage, string $expectedStage, array $cases
        return [
            ['1 1 2', AF::STG_START, AF::STG_UNKNOWN, []],
            ['1 1 2', AF::STG_UNKNOWN, AF::STG_UNKNOWN, []],
            ['1 1 2', AF::STG_MAPS, AF::STG_UNKNOWN, []],
            ['1 1 2', Almanac::SEED_TO_SOIL_MAP, Almanac::SEED_TO_SOIL_MAP, [[1, 1], [0, 0]]],
            ['  1     11 2    ', Almanac::SOIL_TO_FERTILIZER_MAP, Almanac::SOIL_TO_FERTILIZER_MAP, [[11, 1], [10, 10]]],
        ];
    }

    /**
     * @throws ParseException
     * @throws InvalidMapTypeException
     */
    #[DataProvider('parseMapDataData')]
    public function testParseMapData(string $line, string $stage, string $expectedStage, array $cases)
    {
        $a = new Almanac();
        $newStage = AF::parse($line, $a, $stage);
        $this->assertSame($expectedStage, $newStage);
        foreach ($cases as $case) {
            $this->assertSame($case[1], $a->getMap($stage)->get($case[0]));
        }
    }

    public static function parseExceptionData(): array
    {
        return [
            [' seeds:'],
            [' seeds:a b c'],
            [' seeds:1 b c'],
        ];
    }

    /**
     * @throws ParseException
     * @throws InvalidMapTypeException
     */
    #[DataProvider('parseExceptionData')]
    public function testParseException(string $line)
    {
        $this->expectException(ParseException::class);
        $a = new Almanac();
        AF::parse($line, $a, AF::STG_START);
    }
}
