<?php

namespace AdventOfCode\Year2023;

use AdventOfCode\Year2023\Day05\Almanac;
use AdventOfCode\Year2023\Day05\AlmanacFactory;
use AdventOfCode\Year2023\exceptions\NotFoundException;
use AdventOfCode\Year2023\exceptions\ParseException;

// @codeCoverageIgnoreStart
require __DIR__.'/../vendor/autoload.php';
// @codeCoverageIgnoreEnd

class Day05 extends Base
{
    /**
     * {@inheritDoc}
     *
     * @throws ParseException|NotFoundException
     */
    public function calculateFirstStar(): int
    {
        $almanac = new Almanac();
        $parsingStage = AlmanacFactory::STG_START;

        foreach ($this->getLineData() as $line) {
            $line = trim($line);
            if (mb_strlen($line) > 0) {
                $parsingStage = AlmanacFactory::parse($line, $almanac, $parsingStage);
            }
        }

        return $almanac->findLowestLocation();
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
    $d = new Day05('inputs/05.txt');
    $d->execute();
}
// @codeCoverageIgnoreEnd
