<?php

namespace AdventOfCode\Year2023\Tests\Day04;

use AdventOfCode\Year2023\Day04\ScratchcardFactory;
use AdventOfCode\Year2023\exceptions\ParseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ScratchcardFactory::class)]
class ScratchcardFactoryTest extends TestCase
{
    public static function parseData(): array
    {
        return [
            ['Card    1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53', 1,
                [41, 48, 83, 86, 17], [83, 86, 6, 31, 17, 9, 48, 53]],
            ['Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19', 2,
                [13, 32, 20, 16, 61], [61, 30, 68, 82, 17, 32, 24, 19]],
            ['Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1', 3,
                [1, 21, 53, 59, 44], [69, 82, 63, 72, 16, 21, 14, 1]],
            ['Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83', 4,
                [41, 92, 73, 84, 69], [59, 84, 76, 51, 58, 5, 54, 83]],
            ['Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36', 5,
                [87, 83, 26, 28, 32], [88, 30, 70, 12, 93, 22, 82, 36]],
            ['Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11', 6,
                [31, 18, 13, 56, 72], [74, 77, 10, 23, 35, 67, 36, 11]],
        ];
    }

    /**
     * @throws ParseException
     */
    #[DataProvider('parseData')]
    public function testParse(string $l, int $id, array $winningNumbers, array $numbers)
    {
        $card = ScratchcardFactory::parse($l);
        self::assertSame($id, $card->id);
        self::assertSame($winningNumbers, $card->winningNumbers);
        self::assertSame($numbers, $card->numbers);
    }

    public static function exceptionData(): array
    {
        return [
            ['', "Line '' is not in format 'Card N: M N O | A B C'!"],
            ['card :', "Line 'card :' is not in format 'Card N: M N O | A B C'!"],
            ['cord :', "Line 'cord :' is not in format 'Card N: M N O | A B C'!"],
            ['card    : 1 |', "Line 'card    : 1 |' is not in format 'Card N: M N O | A B C'!"],
            ['card    : | 1', "Line 'card    : | 1' is not in format 'Card N: M N O | A B C'!"],
            ['card 1:x', "Line 'card 1:x' is not in format 'Card N: M N O | A B C'!"],
        ];
    }

    /**
     * @throws ParseException
     */
    #[DataProvider('exceptionData')]
    public function testParseExceptions(string $line, string $exceptionMsg)
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage($exceptionMsg);

        ScratchcardFactory::parse($line);
    }
}
