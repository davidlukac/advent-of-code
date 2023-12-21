<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\Map;
use AdventOfCode\Year2023\Day05\Range;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Map::class)]
class MapTest extends TestCase
{
    public static function getData(): array
    {
        // array $ranges, array $cases
        return [
            [[[1, 11, 10], [31, 101, 2]], [[1, 11], [31, 101], [10, 20], [32, 102]]],
            [[[1, 11, 10], [31, 101, 2]], [[0, 0], [11, 11], [30, 30], [33, 33]]],
        ];
    }

    #[DataProvider('getData')]
    public function testGet(array $ranges, array $cases)
    {
        $m = new Map();
        foreach ($ranges as $range) {
            $m->addMapRange(new Range(...$range));
        }
        foreach ($cases as $case) {
            $this->assertSame($case[1], $m->get($case[0]));
        }
    }
}
