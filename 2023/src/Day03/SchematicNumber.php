<?php

namespace AdventOfCode\Year2023\Day03;

class SchematicNumber extends SchematicPart
{
    public function __construct(
        public readonly int $value,
        public readonly Position $position,
        private ?bool $isEnginePart = null
    ) {
    }

    public function setEnginePart(?bool $isEnginePart = true): SchematicNumber
    {
        $this->isEnginePart = $isEnginePart;

        return $this;
    }

    public function unsetAsEnginePart(): SchematicNumber
    {
        $this->isEnginePart = false;

        return $this;
    }

    public function isEnginePart(): ?bool
    {
        return $this->isEnginePart;
    }

    /**
     * {@inheritDoc}
     */
    public function strval(): string
    {
        return strval($this->value);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return strval($this->value);
    }
}
