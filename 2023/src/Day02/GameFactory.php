<?php

namespace AdventOfCode\Year2023\Day02;

use AdventOfCode\Year2023\exceptions\ParseException;

class GameFactory
{
    /**
     * Parse and construct Game with Set(s).
     *
     * @throws ParseException
     */
    public static function parse(string $l): Game
    {
        $sanitized = strtolower(trim($l));
        $parts = explode(':', $sanitized);

        if (count($parts) != 2) {
            throw new ParseException("Line '{$l}' does not contain two parts separated by ':'!");
        }

        $matched = preg_match('/^game\s*(\d+)/', trim($parts[0]), $matches);

        if ((! $matched) or ($matched != 1)) {
            throw new ParseException("Line '{$l}' does not contain 'Game' with ID as expected!");
        }

        $game = new Game((int) $matches[1]);

        $setStrings = explode(';', $parts[1]);
        foreach ($setStrings as $setString) {
            $game->addSet(SetFactory::parse($setString));
        }

        return $game;
    }
}
