<?php

namespace AdventOfCode\Year2023\Tests\Day03;

use AdventOfCode\Year2023\Day03\SchematicFactory;
use AdventOfCode\Year2023\Day03\SchematicNumber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchematicFactory::class)]
class SchematicFactoryTest extends TestCase
{
    public final const TEST_LINES = [
        '467..114..',
        '...*......',
        '..35..633.',
        '......#...',
        '617*......',
        '.....+.58.',
        '..592.....',
        '......755.',
        '...$.*....',
        '.664.598..',
    ];

    public static function parseData(): array
    {
        //@formatter:off
        //  [numbers],      [symbols],  [n-positions],  [s-positions]
        $expectations = [
            [[467, 114],    [],         [0, 5],     []],
            [[],            ['*'],      [],         [3]],
            [[35, 633],     [],         [2, 6],     []],
            [[],            ['#'],      [],         [6]],
            [[617],         ['*'],      [0],        [3]],
            [[58],          ['+'],      [7],        [5]],
            [[592],         [],         [2],        []],
            [[755],         [],         [6],        []],
            [[],            ['$', '*'], [],         [3, 5]],
            [[664, 598],    [],         [1, 5],     []],
        ];
        //@formatter:on

        $combinedArray = [];

        foreach (self::TEST_LINES as $idx => $line) {
            $combinedArray[] = [$idx, $line, ...$expectations[$idx]];
        }

        return $combinedArray;
    }

    #[DataProvider('parseData')]
    public function testParse(
        int $lineIdx,
        string $line,
        array $expNumbers,
        array $expSymbols,
        array $expN_positions,
        array $expS_positions,
    ) {
        $schematic = SchematicFactory::parse($line, $lineIdx);

        $numberValues = [];
        $nPositions = [];

        /** @var SchematicNumber $number */
        foreach ($schematic->getNumbers() as $number) {
            $numberValues[] = $number->value;
            $nPositions[] = $number->position->x;
            $this->assertSame($lineIdx, $number->position->line);
        }

        $symbolValues = [];
        $sPositions = [];

        foreach ($schematic->getSymbols() as $symbol) {
            $symbolValues[] = $symbol->value;
            $sPositions[] = $symbol->position->x;
            $this->assertSame($lineIdx, $symbol->position->line);
        }

        $this->assertSame($expNumbers, $numberValues);
        $this->assertSame($expSymbols, $symbolValues);
        $this->assertSame($expN_positions, $nPositions);
        $this->assertSame($expS_positions, $sPositions);
    }
}
