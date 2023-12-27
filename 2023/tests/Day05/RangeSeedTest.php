<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\RangeSeed;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeSeed::class)]
class RangeSeedTest extends TestCase
{
    public static function hasData(): array
    {
        return [
            [1, 10, 0, false],
            [1, 10, 1, true],
            [1, 10, 2, true],
            [1, 10, 10, true],
            [1, 10, 11, false],
        ];

    }

    #[DataProvider('hasData')]
    public function testHas(int $start, int $length, int $test, bool $expectedHas)
    {
        $s = RangeSeed::fromLength($start, $length);
        $this->assertSame($expectedHas, $s->contains($test));
    }
}
