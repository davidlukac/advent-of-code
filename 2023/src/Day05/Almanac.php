<?php

namespace AdventOfCode\Year2023\Day05;

use AdventOfCode\Year2023\exceptions\InvalidMapTypeException;
use JetBrains\PhpStorm\Pure;

class Almanac
{
    public const SEED_TO_SOIL_MAP = 'seed-to-soil';

    public const SOIL_TO_FERTILIZER_MAP = 'soil-to-fertilizer';

    public const FERTILIZER_TO_WATER_MAP = 'fertilizer-to-water';

    public const WATER_TO_LIGHT_MAP = 'water-to-light';

    public const LIGHT_TO_TEMPERATURE_MAP = 'light-to-temperature';

    public const TEMPERATURE_TO_HUMIDITY_MAP = 'temperature-to-humidity';

    public const HUMIDITY_TO_LOCATION_MAP = 'humidity-to-location';

    public const MAP_SEQUENCE = [
        self::SEED_TO_SOIL_MAP,
        self::SOIL_TO_FERTILIZER_MAP,
        self::FERTILIZER_TO_WATER_MAP,
        self::WATER_TO_LIGHT_MAP,
        self::LIGHT_TO_TEMPERATURE_MAP,
        self::TEMPERATURE_TO_HUMIDITY_MAP,
        self::HUMIDITY_TO_LOCATION_MAP,
    ];

    /** @var int[] */
    private array $seeds = [];

    /** @var RangeSeed[] */
    private array $seedRanges = [];

    /** @var Map[] */
    private array $maps = [];

    public function __construct()
    {
        /** Initialize empty {@see Map}s. */
        foreach (self::MAP_SEQUENCE as $mapType) {
            $this->maps[$mapType] = new Map();
        }
    }

    /**
     * Set seed IDs. Also create {@see RangeSeed}s (for part #2), sort them and remove overlaps.
     *
     * @param  int[]  $seeds
     *
     * @see Almanac::getSeeds()
     */
    public function setSeeds(array $seeds): Almanac
    {
        $this->seeds = $seeds;

        /** @var RangeSeed[] $seeds */
        $seedRanges = [];

        for ($i = 0; $i < count($this->seeds); $i += 2) {
            $start = $this->seeds[$i];
            $length = $this->seeds[$i + 1];
            $seedRanges[] = RangeSeed::fromLength($start, $length);
        }

        usort($seedRanges, function (RangeSeed $l, RangeSeed $r) {
            return $l->start <=> $r->start;
        });

        for ($i = 1; $i < count($seedRanges); $i++) {
            $previous = $seedRanges[$i - 1];
            $current = $seedRanges[$i];
            if ($current->start < $previous->end) {
                $newStart = $previous->end + 1;
                $newLength = $current->end - $newStart + 1;
                $seedRanges[$i] = RangeSeed::fromLength($newStart, $newLength);
            }
        }

        $this->seedRanges = $seedRanges;

        return $this;
    }

    /**
     * Get seed IDs.
     *
     * @see Almanac::setSeeds()
     *
     * @return int[]
     */
    public function getSeeds(): array
    {
        return $this->seeds;
    }

    /**
     * @return RangeSeed[]
     */
    public function getRangeSeeds(): array
    {
        return $this->seedRanges;
    }

    /**
     * Add/replace given {@see Map} to this {@see Almanac}. Checks if the {@see Map} is of valid type.
     *
     * @see Almanac::MAP_SEQUENCE
     * @see Almanac::getMap()
     *
     * @throws InvalidMapTypeException
     */
    public function addMap(string $type, Map $map): Almanac
    {
        if (! in_array($type, self::MAP_SEQUENCE)) {
            $mapTypesStr = implode(', ', self::MAP_SEQUENCE);
            throw new InvalidMapTypeException("Type '{$type}' is not one of [{$mapTypesStr}].");
        }

        $this->maps[$type] = $map;

        return $this;
    }

    /**
     * Get reference to {@see Map} object by type.
     *
     * @see Almanac::MAP_SEQUENCE
     * @see Almanac::addMap()
     *
     * @throws InvalidMapTypeException
     */
    public function &getMap(string $type): Map
    {
        if (! in_array($type, self::MAP_SEQUENCE)) {
            $mapTypesStr = implode(', ', self::MAP_SEQUENCE);
            throw new InvalidMapTypeException("Type '{$type}' is not one of [{$mapTypesStr}].");
        }

        return $this->maps[$type];
    }

    /**
     * Find location for any given seed ID.
     *
     * @see Almanac::findSeed()
     */
    public function findLocation(int $seed): int
    {
        $nextId = $seed;

        foreach (self::MAP_SEQUENCE as $step) {
            $nextId = $this->maps[$step]->get($nextId);
        }

        return $nextId;
    }

    /**
     * Find location {@see Range}(s) for given {@see RangeSeed}.
     *
     * @return Range[]
     */
    public function findLocationRanges(RangeSeed $seed): array
    {
        $nextRanges = [$seed];

        foreach (self::MAP_SEQUENCE as $step) {
            $nextRanges = $this->maps[$step]->mapRanges($nextRanges);
        }

        usort($nextRanges, function (Range $l, Range $r) {
            return $l->start <=> $r->start;
        });

        return $nextRanges;
    }

    /**
     * Find the lowest location ID for any of the seeds in this {@see Almanac}.
     *
     * Slow if used for large amount of seed IDs. Use {@see Almanac::findLowestLocationWithSeedRanges()} instead!
     */
    public function findLowestLocation(): int
    {
        $lowest = PHP_INT_MAX;

        foreach ($this->seeds as $seed) {
            $location = $this->findLocation($seed);
            if ($location < $lowest) {
                $lowest = $location;
            }
        }

        return $lowest;
    }

    /**
     * Check if given seed is in any of the {@see RangeSeed}s.
     */
    #[Pure]
    public function isSeedInRangeSeeds(int $seed): bool
    {
        foreach ($this->seedRanges as $rangeSeed) {
            if ($rangeSeed->contains($seed)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find the lowest location ID using {@see RangeSeed}s.
     */
    public function findLowestLocationWithSeedRanges(): int
    {
        $lowest = INF;

        foreach ($this->seedRanges as $seed) {
            $locationRanges = $this->findLocationRanges($seed);
            $low = reset($locationRanges)->start;
            if ($low < $lowest) {
                $lowest = $low;
            }
        }

        return $lowest;
    }
}
