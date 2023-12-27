<?php

namespace AdventOfCode\Year2023\Tests\Day05;

use AdventOfCode\Year2023\Day05\DefaultMapping;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DefaultMapping::class)]
class DefaultMappingTest extends TestCase
{
    public function testConstruct()
    {
        $m = new DefaultMapping(1, 10);
        $this->assertSame($m->targetRange->start, $m->sourceRange->start);
        $this->assertSame($m->targetRange->end, $m->sourceRange->end);
        $this->assertSame($m->targetRange->length, $m->sourceRange->length);
    }
}
