<?php

namespace AdventOfCode\Year2023\Tests\Day03;

use AdventOfCode\Year2023\Day03\Position;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Position::class)]
class PositionTest extends TestCase
{
    public function testPosition()
    {
        $p = new Position(1, 2);
        $this->assertSame(1, $p->line);
        $this->assertSame(2, $p->x);
    }
}
