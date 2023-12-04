<?php

declare(strict_types=1);

namespace Tests\Day2;

use App\Day1\Trebuchet;
use App\Day2\CubeConundrum;
use PHPUnit\Framework\TestCase;

final class Day2Test extends TestCase
{
    public function testItPassesPart1(): void
    {
        $this->assertSame(2685, (new CubeConundrum())->part1());
    }

    public function testItPassesPart2(): void
    {
        $this->assertSame(83707, (new CubeConundrum())->part2());
    }
}
