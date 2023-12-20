<?php

namespace AdventOfCode\Year2023;

use AdventOfCode\Year2023\Day04\ScratchcardFactory;
use AdventOfCode\Year2023\exceptions\ParseException;

// @codeCoverageIgnoreStart
require __DIR__.'/../vendor/autoload.php';
// @codeCoverageIgnoreEnd

class Day04 extends Base
{
    /**
     * {@inheritDoc}
     *
     * @throws ParseException
     */
    public function calculateFirstStar(): int
    {
        $sum = 0;

        foreach ($this->getLineData() as $line) {
            $line = trim($line);
            if (mb_strlen($line) > 0) {
                $card = ScratchcardFactory::parse($line);
                $sum += $card->getValue();
            }
        }

        return $sum;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ParseException
     */
    public function calculateSecondStar(): int
    {
        $cardMatchingNumbers = [];

        foreach ($this->getLineData() as $line) {
            $line = trim($line);
            if (mb_strlen($line) > 0) {
                $cardMatchingNumbers[] = ScratchcardFactory::parse($line)->getMatchingNumbersCount();
            }
        }

        $totalWon = 0;
        foreach (array_keys($cardMatchingNumbers) as $index) {
            $totalWon += $this->calculateTotalCards($cardMatchingNumbers, $index);
        }

        return $totalWon;
    }

    /**
     * Calculate number of totally won cards for start at $startIndex.
     *
     * @param  int[]  $cardsMatchingNumbers
     */
    public function calculateTotalCards(array &$cardsMatchingNumbers, int $startIndex = 0): int
    {
        $winningCount = $cardsMatchingNumbers[$startIndex];

        $total = 1;

        for ($i = 1; $i <= $winningCount; $i++) {
            $nextIndex = $startIndex + $i;
            if ($nextIndex < count($cardsMatchingNumbers)) {
                $total += $this->calculateTotalCards($cardsMatchingNumbers, $nextIndex);
            }
        }

        return $total;
    }
}

// @codeCoverageIgnoreStart
if (! debug_backtrace()) {
    $d = new Day04('inputs/04.txt');
    $d->execute();
}
// @codeCoverageIgnoreEnd
