<?php

namespace AdventOfCode\Year2023\Day03;

abstract class SchematicPart
{
    public readonly Position $position;

    /**
     * Provide string representation of schematic part value.
     */
    abstract public function strval(): string;

    /**
     * Nice printable string representation.
     */
    abstract public function __toString(): string;

    /**
     * Identify part by its position, which must be unique as only one part can occupy the space.
     */
    public function getId(): string
    {
        return "{$this->position->line}:{$this->position->x}";
    }
}
