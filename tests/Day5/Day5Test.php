<?php

declare(strict_types=1);

namespace Tests\Day5;

use App\Day5\SeedLocations;
use PHPUnit\Framework\TestCase;

final class Day5Test extends TestCase
{
    public function testItPassesPart1(): void
    {
        $this->assertSame(579439039, (new SeedLocations())->part1());
    }

    public function testItPassesPart2(): void
    {
        $this->assertSame(46, (new SeedLocations())->part2());
    }
}
