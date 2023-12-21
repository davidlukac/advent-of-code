<?php

namespace AdventOfCode\Year2023\Day05;

use OutOfRangeException;

class Range
{
    private readonly int $diff;

    private readonly int $sourceEnd;

    public function __construct(
        public readonly int $sourceStart,
        public readonly int $destStart,
        public readonly int $rangeLength,
    ) {
        $this->diff = $this->destStart - $this->sourceStart;
        $this->sourceEnd = $this->sourceStart + $this->rangeLength - 1;
    }

    /**
     * Map source ID to destination ID.
     *
     * @throws OutOfRangeException
     */
    public function get(int $source): int
    {
        if (($source < $this->sourceStart) or ($source > $this->sourceEnd)) {
            throw new OutOfRangeException();
        }

        return $source + $this->diff;
    }

    /**
     * Map source ID to destination ID. Return null if index is out of bounds.
     */
    public function getDefault(int $source): ?int
    {
        $destination = $source + $this->diff;

        if (($source < $this->sourceStart) or ($source > $this->sourceEnd)) {
            $destination = null;
        }

        return $destination;
    }

    /**
     * String representation of source range as 'SOURCE_START:SOURCE_END'.
     */
    public function getSourceRangeStr(): string
    {
        return "{$this->sourceStart}:{$this->sourceEnd}";
    }
}
