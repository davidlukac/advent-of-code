<?php

namespace AdventOfCode\Year2023\Tests;

use AdventOfCode\Year2023\Base;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Base::class)]
class BaseTest extends TestCase
{
    private string|false $testFilePath;

    private $concreteBase;

    protected function setUp(): void
    {
        $this->testFilePath = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($this->testFilePath, "line 1\nline 2\nline 3");

        $this->concreteBase = new class($this->testFilePath) extends Base
        {
            public function calculateFirstStar(): int
            {
                return 123;
            }

            public function calculateSecondStar(): int
            {
                return 456;
            }
        };
    }

    public function testGetLineData()
    {
        $expectedLines = ['line 1', 'line 2', 'line 3'];
        $actualLines = [];

        foreach ($this->concreteBase->getLineData() as $line) {
            $actualLines[] = $line;
        }

        $this->assertEquals($expectedLines, $actualLines);
    }

    public function testExecute()
    {
        ob_start();
        $this->concreteBase->execute();
        $output = ob_get_clean();
        $this->assertEquals('First star: 123'.PHP_EOL.'Second star: 456'.PHP_EOL, $output);
    }

    protected function tearDown(): void
    {
        unlink($this->testFilePath);
    }
}
