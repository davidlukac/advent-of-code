<?php

namespace AdventOfCode\Year2023\Day03;

use AdventOfCode\Year2023\exceptions\InvalidSchematicsException;
use InvalidArgumentException;

class Schematic
{
    /**
     * @var SchematicNumber[]
     */
    private array $numbers = [];

    /**
     * @var Symbol[]
     */
    private array $symbols = [];

    /**
     * Two-dimensional array with references to actual SchematicPart that are occupying given position.
     */
    private array $occupancy = [];

    /**
     * @throws InvalidSchematicsException
     */
    public function addSchematicPart(SchematicPart $part): Schematic
    {
        $cls = get_class($part);
        switch ($cls) {
            case SchematicNumber::class:
                $this->numbers[] = &$part;
                break;

            case Symbol::class:
                $this->symbols[] = &$part;
                break;

            default:
                throw new InvalidArgumentException("Unknown type of part: {$cls}");
        }

        for ($i = 0; $i < mb_strlen($part->strval()); $i++) {
            $line = $part->position->line;
            $x = $part->position->x + $i;

            /** @var SchematicPart $occupant */
            $occupant = $this->occupancy[$line][$x] ?? null;

            if ($occupant) {
                $occupantCls = $occupant::class;
                $occupantValue = $occupant->strval();
                $msg = "Position at line {$line}:{$x} is already occupied by {$occupantCls}:{$occupantValue}!";
                throw new InvalidSchematicsException($msg);
            }

            $this->occupancy[$line][$x] = &$part;
        }

        return $this;
    }

    /**
     * @return SchematicNumber[]
     */
    public function getNumbers(): array
    {
        return $this->numbers;
    }

    /**
     * @return Symbol[]
     */
    public function getSymbols(): array
    {
        return $this->symbols;
    }

    /**
     * @return SchematicNumber[]
     */
    public function findEngineParts(): array
    {
        $engineParts = [];

        foreach ($this->symbols as $s) {
            $checkPositions = [
                [-1, -1],
                [-1, 0],
                [-1, 1],
                [0, -1],
                [0, 1],
                [1, -1],
                [1, 0],
                [1, 1],
            ];
            foreach ($checkPositions as $pos) {
                $occupant = $this->occupancy[$s->position->line + $pos[0]][$s->position->x + $pos[1]] ?? null;
                if (($occupant) and ($occupant instanceof SchematicNumber)) {
                    $occupant->setEnginePart();
                    $engineParts[$occupant->getId()] = $occupant;
                }
            }
        }

        $totalOccupants = 0;
        foreach ($this->occupancy as $line) {
            $totalOccupants += count($line);
        }

        return $engineParts;
    }

    /**
     * Merge provided partial Schematic into this Schematic onto given line number. The provided partial Schematic is
     * considered a single line regardless of real line values, so actual
     *
     * @throws InvalidSchematicsException
     */
    public function mergeSchematicPartial(Schematic $partial, int $lineNumber): Schematic
    {
        foreach ($partial->getNumbers() as $number) {
            $newNumber = new SchematicNumber(
                $number->value,
                new Position($lineNumber, $number->position->x),
                $number->isEnginePart(),
            );
            $this->addSchematicPart($newNumber);
        }

        foreach ($partial->getSymbols() as $symbol) {
            $newSymbol = new Symbol($symbol->value, new Position($lineNumber, $symbol->position->x));
            $this->addSchematicPart($newSymbol);
        }

        return $this;
    }
}
