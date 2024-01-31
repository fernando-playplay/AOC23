<?php

declare(strict_types=1);

namespace Tests\Day7;

use App\Day7\CamelCards;
use PHPUnit\Framework\TestCase;

final class Day7Test extends TestCase
{
    public function testItPassesPart1(): void
    {
        $this->assertSame(6440, (new CamelCards())->part1());
    }

    public function testItPassesPart2(): void
    {
        $this->assertSame(0, (new CamelCards())->part2());
    }
}
