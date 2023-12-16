<?php

namespace AdventOfCode\Year2023\Tests\Day02;

use AdventOfCode\Year2023\Day02\GameFactory;
use AdventOfCode\Year2023\exceptions\ParseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(GameFactory::class)]
final class GameFactoryTest extends TestCase
{
    private const LINES_ONE = [
        'Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green',
        'Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue',
        'Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red',
        'Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red',
        'Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green',
    ];

    public static function parseData(): array
    {
        return array_map(null, self::LINES_ONE, [1, 2, 3, 4, 5]);
    }

    /**
     * @throws ParseException
     */
    #[DataProvider('parseData')]
    public function testParseGameId(string $line, int $expectedId)
    {
        $this->assertSame($expectedId, GameFactory::parse($line)->id);
    }
}
