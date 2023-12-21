<?php

namespace AdventOfCode\Year2023\Day05;

class Map
{
    /** @var Range[] */
    private array $ranges = [];

    /**
     * Add a Range to this Map.
     */
    public function addMapRange(Range $range): Map
    {
        $this->ranges[$range->getSourceRangeStr()] = $range;

        return $this;
    }

    /**
     * Map source index to destination index with Ranges registered with this Map. If no mapping is found in the Ranges
     * destination maps to source ID.
     */
    public function get(int $source): int
    {
        $destination = $source;

        foreach ($this->ranges as $range) {
            $found = $range->getDefault($source);
            if ($found) {
                $destination = $found;
            }
        }

        return $destination;
    }
}
