<?php

namespace AdventOfCode\Year2023\Day05;

use InvalidArgumentException;

class Range
{
    public readonly int $length;

    public function __construct(public readonly int $start, public readonly int $end)
    {
        if ($this->start <= $this->end) {
            $this->length = $this->end - $this->start + 1;
        } else {
            $this->length = -($this->start - $this->end + 1);
        }
    }

    /**
     * Construct {@see Range} from start index and length.
     */
    public static function fromLength(int $start, int $length): Range
    {
        if ($length > 0) {
            return new static($start, $start + $length - 1);
        } elseif ($length < 0) {
            return new static($start, $start + $length + 1);
        } else {
            throw new InvalidArgumentException('Length can not be zero!');
        }
    }

    /**
     * Check if given source is within this range.
     */
    public function contains(int $needle): bool
    {
        $contains = true;

        if ($this->start <= $this->end) {
            if (($needle < $this->start) or ($needle > $this->end)) {
                $contains = false;
            }
        } else {
            if (($needle > $this->start) or ($needle < $this->end)) {
                $contains = false;
            }
        }

        return $contains;
    }

    public function __toString(): string
    {
        return "Range {$this->start}->{$this->end} (length: {$this->length})";
    }
}
