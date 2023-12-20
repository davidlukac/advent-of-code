<?php

namespace AdventOfCode\Year2023\Day04;

class Scratchcard
{
    private readonly int $value;

    private readonly int $matchingNumber;

    /**
     * @param  int[]  $winningNumbers
     * @param  int[]  $numbers
     */
    public function __construct(
        public readonly int $id,
        public readonly array $winningNumbers,
        public readonly array $numbers,
    ) {
        $this->matchingNumber = count(array_intersect($this->numbers, $this->winningNumbers));
        $this->value = pow(2, $this->matchingNumber - 1);
    }

    /**
     * Calculate value of the card.
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Count matching numbers.
     */
    public function getMatchingNumbersCount(): int
    {
        return $this->matchingNumber;
    }
}
