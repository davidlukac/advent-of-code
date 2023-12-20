<?php

namespace AdventOfCode\Year2023\Day04;

use AdventOfCode\Year2023\exceptions\ParseException;

class ScratchcardFactory
{
    /**
     * @throws ParseException
     */
    public static function parse(string $line): Scratchcard
    {
        $parseExceptionMsg = "Line '{$line}' is not in format 'Card N: M N O | A B C'!";
        $sanitized = mb_strtolower(trim($line));

        $parts = explode(':', $sanitized);
        if (count($parts) != 2) {
            throw new ParseException($parseExceptionMsg);
        }

        $matched = preg_match('/card\s+(\d+)/', $parts[0], $matches);
        if (! $matched) {
            throw new ParseException($parseExceptionMsg);
        }

        $id = intval($matches[1]);

        $parts = explode('|', $parts[1]);
        if (count($parts) != 2) {
            throw new ParseException($parseExceptionMsg);
        }

        $convertedNumbers = [];

        foreach ($parts as $part) {
            $convertedNumbers[] = self::convertNumbers($part);
        }

        return new Scratchcard($id, ...$convertedNumbers);
    }

    /**
     * Convert a string with positive integer numbers separated by one or more spaces into an array.
     *
     * @return int[]
     */
    private static function convertNumbers(string $numbers): array
    {
        $numberArray = preg_split('/\s+/', trim($numbers));

        return array_map('intval', $numberArray);
    }
}
