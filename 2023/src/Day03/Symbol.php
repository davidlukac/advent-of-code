<?php

namespace AdventOfCode\Year2023\Day03;

use InvalidArgumentException as E;

class Symbol extends SchematicPart
{
    public function __construct(
        public readonly string $value,
        public readonly Position $position,
    ) {
        if ((! is_string($this->value)) or (mb_strlen($this->value) != 1)) {
            throw new E("Argument '{$this->value}' is not a single character!");
        }

        if (ctype_alnum($this->value) or ctype_space($this->value)) {
            throw new E("Argument '{$this->value}' must be non-alphanumeric and non-whitespace.");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function strval(): string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
