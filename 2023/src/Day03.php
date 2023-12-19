<?php

namespace AdventOfCode\Year2023;

use AdventOfCode\Year2023\Day03\Schematic;
use AdventOfCode\Year2023\Day03\SchematicFactory;
use AdventOfCode\Year2023\Day03\SchematicNumber;
use AdventOfCode\Year2023\exceptions\InvalidSchematicsException;

// @codeCoverageIgnoreStart
require __DIR__.'/../vendor/autoload.php';
// @codeCoverageIgnoreEnd

class Day03 extends Base
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidSchematicsException
     */
    public function calculateFirstStar(): int
    {
        $sum = 0;
        $lineNumber = 0;
        $schematic = new Schematic();

        foreach ($this->getLineData() as $line) {
            $schematic->mergeSchematicPartial(SchematicFactory::parse($line, $lineNumber), $lineNumber);
            $lineNumber++;
        }



        foreach ($schematic->findEngineParts() as $enginePart) {
            $sum += $enginePart->value;
        }

        return $sum;
    }

    /**
     * {@inheritDoc}
     */
    public function calculateSecondStar(): int
    {
        return 0;
    }
}

// @codeCoverageIgnoreStart
if (! debug_backtrace()) {
    $d = new Day03('inputs/03.txt');
    $d->execute();
}
// @codeCoverageIgnoreEnd
