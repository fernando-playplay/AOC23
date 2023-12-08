<?php

declare(strict_types=1);

namespace Tests\Day5;

use App\Day5\SeedLocations;
use PHPUnit\Framework\TestCase;

final class Day5Test extends TestCase
{
    public function testItPassesPart1(): void
    {
        $this->assertSame(35, (new SeedLocations())->part1());
    }

    public function testItPassesPart2(): void
    {
        $this->assertSame(0, (new SeedLocations())->part2());
    }
}
