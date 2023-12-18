<?php

namespace AdventOfCode\Year2023\Day02;

use JetBrains\PhpStorm\ArrayShape;

/**
 * @property $sets
 */
class Game
{
    /**
     * @var Set[]
     */
    private array $sets = [];

    public function __construct(public readonly int $id)
    {
    }

    public function addSet(Set $s): int
    {
        return array_push($this->sets, $s);
    }

    /**
     * @return Set[]
     */
    public function getSets(): array
    {
        return $this->sets;
    }

    /**
     * Evaluate whether this game was possible to perform given constrains of red/green/blue cubes.
     */
    public function isPossible(int $red, int $green, int $blue): bool
    {
        foreach ($this->sets as $set) {
            if (($set->red > $red) or ($set->green > $green) or ($set->blue > $blue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate minimum needed cubes for this game.
     */
    #[ArrayShape([
        Set::RED => 'int',
        Set::GREEN => 'int',
        Set::BLUE => 'int',
    ])]
    public function getMinCubes(): array
    {
        $minRed = 0;
        $minGreen = 0;
        $minBlue = 0;

        foreach ($this->sets as $set) {
            if ($set->red > $minRed) {
                $minRed = $set->red;
            }
            if ($set->green > $minGreen) {
                $minGreen = $set->green;
            }
            if ($set->blue > $minBlue) {
                $minBlue = $set->blue;
            }
        }

        return [
            Set::RED => $minRed,
            Set::GREEN => $minGreen,
            Set::BLUE => $minBlue,
        ];
    }
}
