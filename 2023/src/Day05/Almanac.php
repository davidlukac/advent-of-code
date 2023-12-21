<?php

namespace AdventOfCode\Year2023\Day05;

use AdventOfCode\Year2023\exceptions\InvalidMapTypeException;

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

    /** @var Map[] */
    private array $maps = [];

    public function __construct()
    {
        foreach (self::MAP_SEQUENCE as $mapType) {
            $this->maps[$mapType] = new Map();
        }
    }

    /**
     * Set seed IDs.
     *
     * @param  int[]  $seeds
     */
    public function setSeeds(array $seeds): Almanac
    {
        $this->seeds = $seeds;

        return $this;
    }

    /**
     * Get seed IDs.
     *
     * @return int[]
     */
    public function getSeeds(): array
    {
        return $this->seeds;
    }

    /**
     * Add/replace given Map to this Almanac. Checks if the map is of valid type.
     *
     * @throws InvalidMapTypeException
     *
     * @see self::MAP_SEQUENCE
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
     * Get reference to Map object by type.
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
     */
    public function findLocation(int $seed): int
    {
        $nextId = $seed;
        foreach (self::MAP_SEQUENCE as $step) {
            $map = $this->maps[$step] ?? new Map();
            $nextId = $map->get($nextId);
        }

        return $nextId;
    }

    /**
     * Find the lowest location ID for any of the seeds in this Almanac.
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
}
