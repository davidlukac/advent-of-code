<?php

namespace AdventOfCode\Year2023\Day05;

/**
 * Default {@see Mapping} maps sources to targets 1:1, i.e. 1 -> 1, 2 -> 2, etc.
 */
class DefaultMapping extends Mapping
{
    public function __construct(int $sourceStart, int $rangeLength)
    {
        parent::__construct($sourceStart, $sourceStart, $rangeLength);
    }
}
