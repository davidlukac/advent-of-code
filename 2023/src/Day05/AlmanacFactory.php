<?php

namespace AdventOfCode\Year2023\Day05;

use AdventOfCode\Year2023\exceptions\InvalidMapTypeException;
use AdventOfCode\Year2023\exceptions\ParseException;

class AlmanacFactory
{
    public const STG_START = 'start';

    public const STG_MAPS = 'maps';

    public const STG_UNKNOWN = 'unknown';

    /**
     * @throws ParseException|InvalidMapTypeException
     */
    public static function parse(string $line, Almanac &$almanac, string $stage): string
    {
        $sanitized = mb_strtolower(trim($line));

        // Starting stage - parsing seeds.
        if (($stage == self::STG_START) and (mb_strlen($sanitized) > 0) and str_starts_with($sanitized, 'seeds:')) {
            $parts = explode(':', $sanitized);

            $seedIdsStr = preg_split('/\s+/', trim($parts[1]));
            preg_match_all('/(\d+)/', trim($parts[1]), $matches);

            if (count($matches[1]) != count($seedIdsStr)) {
                throw new ParseException("Line '{$line}' was not in format 'seeds: ID1 ID2 ID3'!");
            }

            $seedIds = array_map('intval', $seedIdsStr);

            $almanac->setSeeds($seedIds);

            return self::STG_MAPS;
        }

        // Initializing map parsing - line 'XXX-to-YYY map:'.
        if (
            (($stage == self::STG_MAPS) or preg_match('/[a-z]+-to-[a-z]+/', $stage)) and
            (mb_strlen($sanitized) > 0) and
            preg_match('/^[a-z]+-to-[a-z]+\s+map\s*:$/', $sanitized)
        ) {
            preg_match('/[a-z]+-to-[a-z]+/', $sanitized, $matches);
            $type = $matches[0];
            $almanac->addMap($type, new Map());

            return $type;
        }

        // Parsing range for current map stage.
        if (
            preg_match('/[a-z]+-to-[a-z]+/', $stage) and
            (mb_strlen($sanitized) > 0) and
            preg_match('/^\d+\s+\d+\s+\d+$/', $sanitized)
        ) {
            preg_match('/^(\d+)\s+(\d+)\s+(\d+)$/', $sanitized, $matches);
            [$destination, $source, $range] = array_map('intval', array_slice($matches, 1));
            $almanac->getMap($stage)->addMapRange(new Range($source, $destination, $range));

            return $stage;
        }

        return self::STG_UNKNOWN;
    }
}
