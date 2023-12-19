<?php

namespace AdventOfCode\Year2023\Tests\Day03;

use AdventOfCode\Year2023\Day03\Position;
use AdventOfCode\Year2023\Day03\Schematic;
use AdventOfCode\Year2023\Day03\SchematicNumber;
use AdventOfCode\Year2023\Day03\SchematicPart;
use AdventOfCode\Year2023\Day03\Symbol;
use AdventOfCode\Year2023\exceptions\InvalidSchematicsException;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Schematic::class)]
class SchematicTest extends TestCase
{
    /**
     * @throws InvalidSchematicsException
     */
    public function testAddSchematicPart()
    {
        $s = new Schematic();
        $s->addSchematicPart(new SchematicNumber(123, new Position(3, 5)));
        $s->addSchematicPart(new SchematicNumber(456, new Position(6, 2)));
        $s->addSchematicPart(new Symbol('-', new Position(2, 7)));
        $s->addSchematicPart(new Symbol('=', new Position(5, 4)));

        $this->assertEquals([
            new SchematicNumber(123, new Position(3, 5)),
            new SchematicNumber(456, new Position(6, 2)),
        ], $s->getNumbers());

        $this->assertEquals([
            new Symbol('-', new Position(2, 7)),
            new Symbol('=', new Position(5, 4)),
        ], $s->getSymbols());
    }

    /**
     * @throws InvalidSchematicsException
     */
    public function testAddSchematicPartException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown type of part: ');

        $s = new Schematic();
        $s->addSchematicPart(new class() extends SchematicPart
        {
            public function strval(): string
            {
                return '';
            }

            public function __toString(): string
            {
                return '';
            }
        });
    }

    /**
     * @throws InvalidSchematicsException
     */
    public function testOverlappingOccupants()
    {
        $s = new Schematic();
        $s->addSchematicPart(new SchematicNumber(12, new Position(0, 0)));

        $this->expectException(InvalidSchematicsException::class);
        $numberCls = SchematicNumber::class;
        $this->expectExceptionMessage("Position at line 0:1 is already occupied by {$numberCls}:12!");

        $s->addSchematicPart(new SchematicNumber(34, new Position(0, 1)));
    }

    /**
     * @throws InvalidSchematicsException
     */
    public function testMergeSchematicPartial()
    {
        $s = new Schematic();
        $s->addSchematicPart(new SchematicNumber(456, new Position(2, 5)));
        $s->addSchematicPart(new Symbol('*', new Position(2, 10)));

        $partial = new Schematic();
        $partial->addSchematicPart(new SchematicNumber(123, new Position(0, 0)));
        $partial->addSchematicPart(new Symbol('-', new Position(1, 8)));

        $s->mergeSchematicPartial($partial, 2);

        $this->assertEquals([
            new SchematicNumber(456, new Position(2, 5)),
            new SchematicNumber(123, new Position(2, 0)),
        ], $s->getNumbers());

        $this->assertEquals([
            new Symbol('*', new Position(2, 10)),
            new Symbol('-', new Position(2, 8)),
        ], $s->getSymbols());

    }

    /**
     * @throws InvalidSchematicsException
     */
    public function testFindEngineParts()
    {
        /**
         *   .12345.
         *   .12-34.
         *   234.56.
         *   *......
         *   .55.9..
         *   ......+
         *   ......0
         */
        $s = new Schematic();
        $s->addSchematicPart(new SchematicNumber(12345, new Position(0, 1)));
        $s->addSchematicPart(new SchematicNumber(12, new Position(1, 1)));
        $s->addSchematicPart(new SchematicNumber(34, new Position(1, 4)));
        $s->addSchematicPart(new SchematicNumber(234, new Position(2, 0)));
        $s->addSchematicPart(new SchematicNumber(56, new Position(2, 4)));
        $s->addSchematicPart(new SchematicNumber(55, new Position(4, 1)));
        $s->addSchematicPart(new SchematicNumber(9, new Position(4, 4)));
        $s->addSchematicPart(new SchematicNumber(0, new Position(6, 6)));

        $s->addSchematicPart(new Symbol('-', new Position(1, 3)));
        $s->addSchematicPart(new Symbol('*', new Position(3, 0)));
        $s->addSchematicPart(new Symbol('+', new Position(5, 6)));

        $this->assertEquals([
            '0:1' => new SchematicNumber(12345, new Position(0, 1), true),
            '1:1' => new SchematicNumber(12, new Position(1, 1), true),
            '1:4' => new SchematicNumber(34, new Position(1, 4), true),
            '2:0' => new SchematicNumber(234, new Position(2, 0), true),
            '2:4' => new SchematicNumber(56, new Position(2, 4), true),
            '4:1' => new SchematicNumber(55, new Position(4, 1), true),
            '6:6' => new SchematicNumber(0, new Position(6, 6), true),
        ], $s->findEngineParts());
    }

    public static function findEnginePartsTwoData(): array
    {
        //@formatter:off
        return [
            [1, 0, 0, '-', 1, 1],
            [1, 0, 1, '-', 1, 1],
            [1, 0, 2, '-', 1, 1],
            [1, 1, 0, '-', 1, 1],
            [1, 1, 2, '-', 1, 1],
            [1, 2, 0, '-', 1, 1],
            [1, 2, 1, '-', 1, 1],
            [1, 2, 2, '-', 1, 1],
            [123, 0, 0, '-', 1, 3],
            [123, 0, 2, '-', 1, 3],
            [123, 0, 4, '-', 1, 3],
            [123, 1, 0, '-', 1, 3],
            [123, 1, 4, '-', 1, 3],
            [123, 2, 0, '-', 1, 3],
            [123, 2, 2, '-', 1, 3],
            [123, 2, 4, '-', 1, 3],
        ];
        //@formatter:on
    }

    /**
     * @throws InvalidSchematicsException
     */
    #[DataProvider('findEnginePartsTwoData')]
    public function testFindEnginePartsTwo(int $n, int $l, int $x, string $s, int $sl, int $sx)
    {
        $sch = new Schematic();
        $sch->addSchematicPart(new SchematicNumber($n, new Position($l, $x)));
        $sch->addSchematicPart(new Symbol($s, new Position($sl, $sx)));

        $this->assertEquals([
            "{$l}:{$x}" => new SchematicNumber($n, new Position($l, $x), true),
        ], $sch->findEngineParts());
    }
}
