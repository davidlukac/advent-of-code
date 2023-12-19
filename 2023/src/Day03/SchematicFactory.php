<?php

namespace AdventOfCode\Year2023\Day03;

use AdventOfCode\Year2023\exceptions\InvalidSchematicsException;

class SchematicFactory
{
    /**
     * Create a partial schematic from given line.
     *
     * @throws InvalidSchematicsException
     */
    public static function parse(string $l, int $lineNumber): Schematic
    {
        $schematic = new Schematic();

        $matches = [];
        $matched = preg_match_all('/\d+/', $l, $matches, PREG_OFFSET_CAPTURE);

        if ($matched and ($matched > 0)) {
            foreach ($matches[0] as $match) {
                $schematic->addSchematicPart(new SchematicNumber($match[0], new Position($lineNumber, $match[1])));
            }
        }

        $matched = preg_match_all('/([^.\w\s])/', $l, $matches, PREG_OFFSET_CAPTURE);
        if ($matched and ($matched > 0)) {
            foreach ($matches[0] as $match) {
                $schematic->addSchematicPart(new Symbol($match[0], new Position($lineNumber, $match[1])));
            }
        }

        return $schematic;
    }
}
