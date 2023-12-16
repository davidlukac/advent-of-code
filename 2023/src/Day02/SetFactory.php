<?php

namespace AdventOfCode\Year2023\Day02;

use AdventOfCode\Year2023\exceptions\ParseException;

class SetFactory
{
    /**
     * Parse single set string in format '<DIG> <COLOR>, <DIG> <COLOR>, ...'. Colors are validated against Set class.
     *
     * @throws ParseException
     */
    public static function parse($s): Set
    {
        $sanitized = strtolower(trim($s));
        $parts = explode(',', $sanitized);

        $values = [
            Set::RED => 0,
            Set::GREEN => 0,
            Set::BLUE => 0,
        ];

        $validColors = implode(', ', Set::COLORS);

        foreach ($parts as $part) {
            $subParts = explode(' ', trim($part));

            if (count($subParts) != 2) {
                throw new ParseException("String '{$sanitized}' can not be parsed into count and color!");
            }

            $color = $subParts[1];

            if (! in_array($color, Set::COLORS)) {
                throw new ParseException("Color '{$color}' is not valid. Valid options are: '{$validColors}'.");
            }
            $values[$color] = intval($subParts[0]);
        }

        return new Set(red: $values[Set::RED], green: $values[Set::GREEN], blue: $values[Set::BLUE]);
    }
}
