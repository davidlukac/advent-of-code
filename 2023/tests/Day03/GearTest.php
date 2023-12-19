<?php

namespace AdventOfCode\Year2023\Tests\Day03;

use AdventOfCode\Year2023\Day03\Gear;
use AdventOfCode\Year2023\Day03\Position;
use AdventOfCode\Year2023\Day03\SchematicNumber;
use AdventOfCode\Year2023\Day03\Symbol;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Gear::class)]
class GearTest extends TestCase
{
    public function testGetRatio()
    {
        $g = new Gear(
            new Symbol('*', new Position(1, 1)),
            new SchematicNumber(2, new Position(1, 0)),
            new SchematicNumber(3, new Position(1, 2)),
        );
        $this->assertSame(6, $g->getRatio());
    }
}
