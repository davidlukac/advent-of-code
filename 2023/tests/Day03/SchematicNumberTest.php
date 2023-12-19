<?php

namespace AdventOfCode\Year2023\Tests\Day03;

use AdventOfCode\Year2023\Day03\Position;
use AdventOfCode\Year2023\Day03\SchematicNumber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchematicNumber::class)]
class SchematicNumberTest extends TestCase
{
    public function testNumber()
    {
        $n = new SchematicNumber(123, new Position(1, 1));
        $this->assertSame('123', $n->strval());
        $this->assertSame('123', $n->__toString());
        $this->assertSame(null, $n->isEnginePart());
        $n->setEnginePart();
        $this->assertSame(true, $n->isEnginePart());
        $n->unsetAsEnginePart();
        $this->assertSame(false, $n->isEnginePart());
    }
}
