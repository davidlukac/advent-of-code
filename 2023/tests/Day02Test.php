<?php

namespace AdventOfCode\Year2023\Tests;

use AdventOfCode\Year2023\Day02;
use AdventOfCode\Year2023\exceptions\ParseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(Day02::class)]
class Day02Test extends TestCase
{
    public final const TEST_LINES = [
        'Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green',
        'Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue',
        'Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red',
        'Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red',
        'Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green',
    ];

    public function testSetThresholds()
    {
        $thresholds = [1, 2, 3];
        $d = new Day02('foo');
        $d->setThresholds(...$thresholds);
        $this->assertSame(['red' => 1, 'green' => 2, 'blue' => 3], $d->getThresholds());

    }

    /**
     * @throws ParseException
     * @throws Exception
     */
    public function testCalculateFirstStar()
    {
        $d = $this->createPartialMock(Day02::class, ['getLineData']);
        $d->method('getLineData')
            ->willReturn((function () {
                foreach (self::TEST_LINES as $line) {
                    yield $line;
                }
            })());
        $d->setThresholds(12, 13, 14);

        $res = $d->calculateFirstStar();

        $this->assertSame(8, $res);
    }

    /**
     * @throws ParseException
     * @throws Exception
     */
    public function testCalculateSecondStar()
    {
        $d = $this->createPartialMock(Day02::class, ['getLineData']);
        $d->method('getLineData')
            ->willReturn((function () {
                foreach (self::TEST_LINES as $line) {
                    yield $line;
                }
            })());

        $res = $d->calculateSecondStar();

        $this->assertSame(2286, $res);
    }
}
