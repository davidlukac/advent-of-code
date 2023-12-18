<?php

namespace AdventOfCode\Year2023;

use Generator;
use SplFileObject;

abstract class Base
{
    /**
     * @param  string  $f Path to input file.
     */
    public function __construct(private readonly string $f)
    {
    }

    /**
     * Open file and read line by line yielding a Generator.
     */
    public function getLineData(): Generator
    {
        $file = new SplFileObject($this->f, 'r');

        while (! $file->eof()) {
            yield trim($file->fgets());
        }

        $file = null;
    }

    /**
     * Generic function to execute logic of given day and print result to console. Override as needed.
     */
    public function execute(): void
    {
        print_r('First star: '.$this->calculateFirstStar().PHP_EOL);
        print_r('Second star: '.$this->calculateSecondStar().PHP_EOL);
    }

    /**
     * Calculate int result for the first star.
     *
     * Should be called from 'execute()'.
     *
     * @TODO: Generalize result into a class.
     */
    abstract public function calculateFirstStar(): int;

    /**
     * Calculate int result for the second star.
     *
     * Should be called from 'execute()'.
     *
     * @TODO: Generalize result into a class.
     */
    abstract public function calculateSecondStar(): int;
}
