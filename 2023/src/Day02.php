<?php

namespace AdventOfCode\Year2023;

use AdventOfCode\Year2023\Day02\GameFactory;
use AdventOfCode\Year2023\exceptions\ParseException;

require __DIR__.'/../vendor/autoload.php';

class Day02 extends Base
{
    private int $redThreshold;

    private int $greenThreshold;

    private int $blueThreshold;

    /**
     * Set threshold for calculating which games are possible.
     */
    public function setThresholds(int $red, int $green, int $blue): void
    {
        $this->redThreshold = $red;
        $this->greenThreshold = $green;
        $this->blueThreshold = $blue;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ParseException
     */
    public function calculateFirstStar(): int
    {
        $sum = 0;

        foreach ($this->getLineData() as $line) {
            if (strlen(trim($line)) > 0) {
                $game = GameFactory::parse($line);
                if ($game->isPossible($this->redThreshold, $this->greenThreshold, $this->blueThreshold)) {
                    $sum += $game->id;
                }
            }
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

if (! debug_backtrace()) {
    $d = new Day02('inputs/02.txt');
    $d->setThresholds(12, 13, 14);
    $d->execute();
}
