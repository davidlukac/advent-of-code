<?php

namespace AdventOfCode\Year2023\Day05;

class Map
{
    /** @var Mapping[] */
    private array $mappings = [];

    private bool $mappingGapsFilled = false;

    /**
     * Add a {@see Mapping} to this {@see Map}. Sorts the {@see Mapping}s by '{@see Mapping::$start}' afterward.
     *
     * @see Mapping
     */
    public function addMapping(Mapping $mapping): Map
    {
        $this->mappings[$mapping->getSourceRangeStr()] = $mapping;
        $this->mappingGapsFilled = false;

        usort($this->mappings, function (Mapping $l, Mapping $r) {
            return $l->sourceRange->start <=> $r->sourceRange->start;
        });

        return $this;
    }

    /**
     * Map source index to target index with {@see Mapping}s registered with this {@see Map}. If no mapping is found
     * in the {@see Mapping}s, target maps to source ID.
     */
    public function get(int $source): int
    {
        $low = 0;
        $high = count($this->mappings) - 1;

        // Binary search for source to target mapping in the Ranges.
        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);
            $mapping = $this->mappings[$mid];

            $mappedValue = $mapping->getDefault($source);
            if ($mappedValue) {
                return $mappedValue;
            } elseif ($source < $mapping->sourceRange->start) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }

        // If not found in any mapping, return the input number itself (1-to-1 mapping).
        return $source;
    }

    /**
     * Fill {@see Mapping} gaps with {@see DefaultMapping}s, including 0->first mapping and
     * last mapping->{@see PHP_INT_MAX}-1.
     *
     * Assumes existing {@see Mapping}s are already sorted when {@see Map::addMapping()} is called.
     */
    private function fillMappingGaps(): void
    {
        /** Fill gaps between existing mappings with {@see DefaultMapping}. */
        for ($i = 1; $i < count($this->mappings); $i++) {
            $previous = $this->mappings[$i - 1];
            $current = $this->mappings[$i];
            if ($previous->sourceRange->end < ($current->sourceRange->start - 1)) {
                $fillSourceStart = $previous->sourceRange->end + 1;
                $fillLength = $current->sourceRange->start - $fillSourceStart;
                $fill = new DefaultMapping($fillSourceStart, $fillLength);
                array_splice($this->mappings, $i, 0, [$fill]);
            }
        }

        // Fill 0->first mapping and last mapping->INT_MAX.
        if (count($this->mappings) > 0) {
            $first = reset($this->mappings);
            $last = end($this->mappings);

            // Considering mapping only starting with zero.
            if ($first->sourceRange->start > 0) {
                $zeroToFirst = new DefaultMapping(0, $first->sourceRange->start);
                array_unshift($this->mappings, $zeroToFirst);
            }

            if ($last->sourceRange->end < PHP_INT_MAX) {
                $start = $last->sourceRange->end + 1;
                $length = PHP_INT_MAX - $start - 1;
                $lastToEnd = new DefaultMapping($start, $length);
                $this->mappings[] = $lastToEnd;
            }
        } else {
            $this->mappings[] = new DefaultMapping(0, PHP_INT_MAX);
        }

        $this->mappingGapsFilled = true;
    }

    /**
     * Map list of input {@see Range}s to output {@see Range}s.
     *
     * @param  Range[]  $sourceRanges
     * @return Range[]
     */
    public function mapRanges(array $sourceRanges): array
    {
        $result = [];

        foreach ($sourceRanges as $range) {
            $mappedRanges = $this->mapRange($range);
            array_push($result, ...$mappedRanges);
        }

        return $result;
    }

    /**
     * Find index of the {@see Mapping}, which contains given {@see Range} start. If the {@see Mapping}s only have one
     * mapping, it would be the default one ({@see DefaultMapping}) of 0->INT_MAX, so we can safely return 0 index.
     */
    private function findIndexOfMappingWithRangeStart(int $rangeStart): int
    {
        $low = 0;
        $high = count($this->mappings) - 1;

        // Binary search for source to target mapping in the Ranges.
        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);
            $midMapping = $this->mappings[$mid];

            if ($midMapping->contains($rangeStart)) {
                break;
            } elseif ($rangeStart < $midMapping->sourceRange->start) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }

        return $mid;
    }

    /**
     * Map source {@see Range} to target {@see Range}(s) using {@see Mapping}s of this {@see Map}.
     *
     * @return Range[]
     */
    public function mapRange(Range $sourceRange): array
    {
        if (! $this->mappingGapsFilled) {
            $this->fillMappingGaps();
        }

        /** @var Range[] $mappedRanges Mapped target ranges. */
        $mappedRanges = [];

        $startMappingIdx = $this->findIndexOfMappingWithRangeStart($sourceRange->start);
        $foundMapping = $this->mappings[$startMappingIdx];

        $mappedRangeStart = $foundMapping->get($sourceRange->start);

        if ($foundMapping->contains($sourceRange->end)) {
            $mappedRangeEnd = $foundMapping->get($sourceRange->end);
            $mappedRanges[] = new Range($mappedRangeStart, $mappedRangeEnd);
        } else {
            $endMappingIdx = $startMappingIdx;
            $currentMapping = $foundMapping;

            // Find which mapping contains $sourceRange end and add intermediate target ranges to result.
            while (! $currentMapping->contains($sourceRange->end)) {
                if ($mappedRangeStart == null) {
                    $mappedRangeStart = $currentMapping->targetRange->start;
                }
                // We haven't yet reached the last mapping so take whole mapping (or partial if it's the first one).
                $mappedRanges[] = new Range($mappedRangeStart, $currentMapping->targetRange->end);

                // Prepare the next loop.
                // - Reset mapped range start.
                // - Increase mapping index.
                // - Fetch the next mapping to check.
                $mappedRangeStart = null;
                $endMappingIdx++;
                $currentMapping = $this->mappings[$endMappingIdx];
            }

            // Add final range.
            $mappedRanges[] = new Range(
                $this->mappings[$endMappingIdx]->targetRange->start,
                $this->mappings[$endMappingIdx]->get($sourceRange->end));
        }

        return $mappedRanges;
    }
}
