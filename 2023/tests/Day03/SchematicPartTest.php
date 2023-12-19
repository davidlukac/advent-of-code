<?php

namespace AdventOfCode\Year2023\Tests\Day03;

use AdventOfCode\Year2023\Day03\Position;
use AdventOfCode\Year2023\Day03\SchematicPart;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchematicPart::class)]
class SchematicPartTest extends TestCase
{
    public function testGetId()
    {
        $concreteSchematicPart = new class(new Position(1, 1)) extends SchematicPart
        {
            public function __construct(public readonly Position $position)
            {

            }

            public function strval(): string
            {
                return '';
            }

            public function __toString(): string
            {
                return '';
            }
        };

        $this->assertSame('1:1', $concreteSchematicPart->getId());
    }
}
