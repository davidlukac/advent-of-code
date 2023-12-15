<?php

namespace AdventOfCode\Year2023;

use AdventOfCode\Year2023\exceptions\NotDigitException;
use AdventOfCode\Year2023\utils\DigitFactory;

require __DIR__.'/../vendor/autoload.php';

class Day01 extends Base
{
    /**
     * Extract first and last digit (\d) from given string and return them as two digit integer or return 0.
     */
    public function extractDigits(string $s): int
    {
        $matches = [];
        $result = 0;

        $matched = preg_match_all('/\d/', $s, $matches);

        if ($matched && $matched >= 1) {
            $result = (int) (reset($matches[0]).end($matches[0]));
        }

        return $result;
    }

    /**
     * Extract first and last digit as either \d or spelled ('one', 'two', 'three', ...) form. Overlapping strings are
     * valid as well. Otherwise, return 0.
     *
     * @param string $s
     * @return int
     */
    public function extractWordDigits(string $s): int
    {
        $matches = [];
        $result = 0;

        $matched = preg_match_all('/(?=(one|two|three|four|five|six|seven|eight|nine|\d))/', $s, $matches);

        if ($matched && $matched >= 1) {
            try {
                $firstDigit = DigitFactory::parse(reset($matches[1]))::INT;
                $lastDigit = DigitFactory::parse(end($matches[1]))::INT;
            } catch (NotDigitException $e) {
                exit('Digit parsing went wrong: '.$e);
            }

            $result = (int) "{$firstDigit}{$lastDigit}";
        }

        return $result;
    }

    public function calculateFirstStar(): int
    {
        $sum = 0;

        foreach ($this->getLineData() as $line) {
            $sum += $this->extractDigits($line);
        }

        return $sum;
    }

    public function calculateSecondStar(): int
    {
        $sum = 0;

        foreach ($this->getLineData() as $line) {
            $sum += $this->extractWordDigits($line);
        }

        return $sum;
    }
}

if (! debug_backtrace()) {
    $d = new Day01('inputs/01.txt');
    $d->execute();
}
