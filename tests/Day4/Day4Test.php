<?php

declare(strict_types=1);

namespace Tests\Day4;

use App\Day4\Scratchcards;
use PHPUnit\Framework\TestCase;

final class Day4Test extends TestCase
{
    public function testItPassesPart1(): void
    {
        $this->assertSame(27845, (new Scratchcards())->part1());
    }

    public function testItPassesPart2(): void
    {
        $this->assertSame(9496801, (new Scratchcards())->part2());
    }
}
