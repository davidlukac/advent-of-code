<?php

namespace AdventOfCode\Year2023\Tests\Day02;

use AdventOfCode\Year2023\Day02\Game;
use AdventOfCode\Year2023\Day02\Set;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Game::class)]
class GameTest extends TestCase
{
    public static function isPossibleData(): array
    {
        return [
            [[[1, 1, 1]], 1, 1, 1, true],
            [[[1, 1, 1], [2, 2, 2]], 1, 1, 1, false],
        ];
    }

    #[DataProvider('isPossibleData')]
    public function testIsPossible(array $sets, int $red, $green, $blue, $possible)
    {
        $game = new Game(1);
        foreach ($sets as $set) {
            $game->addSet(new Set(...$set));
        }
        $this->assertSame($possible, $game->isPossible($red, $green, $blue));
    }

    public function testAddSet()
    {
        $game = new Game(1);
        $game->addSet(new Set(1, 2, 3));
        $game->addSet(new Set(4, 5, 6));
        $this->assertEquals($game->getSets(), [new Set(1, 2, 3), new Set(4, 5, 6)]);
    }
}
