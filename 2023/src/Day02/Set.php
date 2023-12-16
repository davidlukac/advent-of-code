<?php

namespace AdventOfCode\Year2023\Day02;

class Set
{
    public final const RED = 'red';

    public final const GREEN = 'green';

    public final const BLUE = 'blue';

    public final const COLORS = [self::RED, self::GREEN, self::BLUE];

    public function __construct(public readonly int $red, public readonly int $green, public readonly int $blue)
    {
    }
}
