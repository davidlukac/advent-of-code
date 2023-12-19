<?php

namespace AdventOfCode\Year2023\Day03;

class Gear
{
    public final const SYMBOL = '*';

    public function __construct(
        public readonly Symbol $gear,
        public readonly SchematicNumber $part_1,
        public readonly SchematicNumber $part_2
    ) {
    }

    /**
     * Calculate gear ration for two engine parts connected by a gear.
     */
    public function getRatio(): int
    {
        return $this->part_1->value * $this->part_2->value;
    }
}
