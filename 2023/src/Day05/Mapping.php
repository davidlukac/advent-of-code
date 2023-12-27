<?php

namespace AdventOfCode\Year2023\Day05;

use OutOfRangeException;

class Mapping
{
    private readonly int $diff;

    public readonly Range $sourceRange;

    public readonly Range $targetRange;

    public function __construct(int $sourceStart, int $targetStart, public readonly int $rangeLength)
    {
        $this->diff = $targetStart - $sourceStart;

        $this->sourceRange = new Range($sourceStart, $sourceStart + $this->rangeLength - 1);
        $this->targetRange = new Range($targetStart, $targetStart + $this->rangeLength - 1);
    }

    /**
     * Map source ID to target ID.
     *
     * @throws OutOfRangeException
     *
     * @see Mapping::getDefault()
     */
    public function get(int $source): int
    {
        if (! $this->sourceRange->contains($source)) {
            throw new OutOfRangeException();
        }

        return $source + $this->diff;
    }

    /**
     * Map source ID to target ID. Return null if index is out of bounds.
     *
     * @see Mapping::get()
     */
    public function getDefault(int $source): ?int
    {
        $target = null;

        if ($this->sourceRange->contains($source)) {
            $target = $source + $this->diff;
        }

        return $target;
    }

    /**
     * String representation of source range as '{@see Mapping::$sourceStart}:{@see Mapping::$sourceEnd}'.
     */
    public function getSourceRangeStr(): string
    {
        return "{$this->sourceRange->start}:{$this->sourceRange->end}";
    }

    /**
     * Check if given source ID is in the bounds of this {@see Mapping}.
     */
    public function contains(int $source): bool
    {
        return $this->sourceRange->contains($source);
    }

    public function __toString(): string
    {
        $str = 'Mapping ';
        $str .= "({$this->sourceRange->start}, {$this->sourceRange->end})->";
        $str .= "({$this->targetRange->start}, {$this->targetRange->end}) ";
        $str .= "(diff: {$this->diff}, length: {$this->rangeLength})";

        return $str;
    }
}
