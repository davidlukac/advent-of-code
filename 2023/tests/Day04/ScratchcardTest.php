<?php

namespace AdventOfCode\Year2023\Tests\Day04;

use AdventOfCode\Year2023\Day04\Scratchcard;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Scratchcard::class)]
class ScratchcardTest extends TestCase
{
    public static function valueData(): array
    {
        return [
            [[41, 48, 83, 86, 17], [83, 86,  6, 31, 17,  9, 48, 53], 8, 4],
            [[13, 32, 20, 16, 61], [61, 30, 68, 82, 17, 32, 24, 19], 2, 2],
            [[1, 21, 53, 59, 44], [69, 82, 63, 72, 16, 21, 14, 1], 2, 2],
            [[41, 92, 73, 84, 69], [59, 84, 76, 51, 58, 5, 54, 83], 1, 1],
            [[87, 83, 26, 28, 32], [88, 30, 70, 12, 93, 22, 82, 36], 0, 0],
            [[31, 18, 13, 56, 72], [74, 77, 10, 23, 35, 67, 36, 11], 0, 0],
        ];
    }

    #[DataProvider('valueData')]
    public function testGetValue(array $winningNumbers, array $numbers, int $value, int $matchingNumbers)
    {
        $c = new Scratchcard(1, $winningNumbers, $numbers);
        $this->assertSame($value, $c->getValue());
        $this->assertSame($matchingNumbers, $c->getMatchingNumbersCount());
    }
}
