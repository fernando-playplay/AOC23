<?php

declare(strict_types=1);

namespace Tests\Day1;

use App\Day1\Trebuchet;
use PHPUnit\Framework\TestCase;

final class Day1Test extends TestCase
{
    public function testItCalibrates(): void
    {
        $this->assertSame(54597, (new Trebuchet())->sumOfCalibrationValues());
    }

    public function testItCalibratesPart2(): void
    {
        $this->assertSame(54504, (new Trebuchet())->sumOfCalibrationValuesWithText());
    }
}
