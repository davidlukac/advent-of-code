<?php

namespace AdventOfCode\Year2023\utils;

use AdventOfCode\Year2023\exceptions\NotDigitException;
use AdventOfCode\Year2023\utils\digits\Digit;
use AdventOfCode\Year2023\utils\digits\Eight;
use AdventOfCode\Year2023\utils\digits\Five;
use AdventOfCode\Year2023\utils\digits\Four;
use AdventOfCode\Year2023\utils\digits\Nine;
use AdventOfCode\Year2023\utils\digits\One;
use AdventOfCode\Year2023\utils\digits\Seven;
use AdventOfCode\Year2023\utils\digits\Six;
use AdventOfCode\Year2023\utils\digits\Three;
use AdventOfCode\Year2023\utils\digits\Two;

class DigitFactory
{
    /**
     * Convert string representation of one - nine digits into integer.
     *
     * @throws NotDigitException
     */
    public static function parse(string $s): Digit
    {
        $sanitized = strtolower(trim($s));

        switch ($sanitized) {
            case One::STR: case One::INT_STR:
                return new One;
            case Two::STR: case Two::INT_STR:
                return new Two;
            case Three::STR: case Three::INT_STR:
                return new Three;
            case Four::STR: case Four::INT_STR:
                return new Four;
            case Five::STR: case Five::INT_STR:
                return new Five;
            case Six::STR: case Six::INT_STR:
                return new Six;
            case Seven::STR: case Seven::INT_STR:
                return new Seven;
            case Eight::STR: case Eight::INT_STR:
                return new Eight;
            case Nine::STR: case Nine::INT_STR:
                return new Nine;
        }

        throw new NotDigitException("String '{$s}' is not a 1-9 digit!");
    }
}
