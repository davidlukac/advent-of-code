<?php

namespace AdventOfCode\Year2023\Tests\Day02;

use AdventOfCode\Year2023\Day02\Game;
use AdventOfCode\Year2023\Day02\Set as S;
use JetBrains\PhpStorm\ArrayShape;
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
            $game->addSet(new S(...$set));
        }
        $this->assertSame($possible, $game->isPossible($red, $green, $blue));
    }

    public function testAddSet()
    {
        $game = new Game(1);
        $game->addSet(new S(1, 2, 3));
        $game->addSet(new S(4, 5, 6));
        $this->assertEquals($game->getSets(), [new S(1, 2, 3), new S(4, 5, 6)]);
    }

    #[ArrayShape([
        0 => 'int',
        1 => 'array<Set::class>',
        2 => 'int[]',
    ])]
    public static function minCubesData(): array
    {
        return [
            [1, [new S(4, blue: 3), new S(1, 2, 6), new S(green: 2)], [4, 2, 6]],
            [2, [new S(green: 2, blue: 1), new S(1, 3, 4), new S(green: 1, blue: 1)], [1, 3, 4]],
            [3, [new S(20, 8, 6), new S(4, 13, 5), new S(1, 5)], [20, 13, 6]],
            [4, [new S(3, 1, 6), new S(6, 3), new S(14, 3, 15)], [14, 3, 15]],
            [5, [new S(6, 3, 1), new S(1, 2, 2)], [6, 3, 2]],
        ];
    }

    #[DataProvider('minCubesData')]
    public function testGetMinCubes(int $id, array $sets, array $min)
    {
        $g = new Game($id);
        foreach ($sets as $set) {
            $g->addSet($set);
        }
        $expected = array_combine(['red', 'green', 'blue'], $min);
        $this->assertSame($expected, $g->getMinCubes());
    }
}
