<?php

declare(strict_types=1);

namespace Tests\Day3;

use App\Day1\Trebuchet;
use App\Day2\CubeConundrum;
use App\Day3\GearRatios;
use PHPUnit\Framework\TestCase;

final class Day3Test extends TestCase
{
    public function testItPassesPart1(): void
    {
        $this->assertSame(519444, (new GearRatios())->part1());
    }

    public function testItPassesPart2(): void
    {
        $this->assertSame(467835, (new GearRatios())->part2());
    }
}
