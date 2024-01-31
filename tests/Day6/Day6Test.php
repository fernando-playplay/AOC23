<?php

declare(strict_types=1);

namespace Tests\Day6;

use App\Day6\BoatRace;
use PHPUnit\Framework\TestCase;

final class Day6Test extends TestCase
{
    public function testItPassesPart1(): void
    {
        $this->assertSame(219849, (new BoatRace())->part1());
    }

    public function testItPassesPart2(): void
    {
        $this->assertSame(29432455, (new BoatRace())->part2());
    }
}
