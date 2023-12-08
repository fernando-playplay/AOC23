<?php

declare(strict_types=1);

namespace App\Day4;

final class Scratchcards
{
    public function part1(): int
    {
        $lineGenerator = $this->readLine();
        $sum = 0;
        return $sum;
    }

    public function part2(): int
    {
        $lineGenerator = $this->readLine();
        $sum = 0;
        return $sum;
    }

    private function readLine(bool $control = false): \Generator
    {
        $fp = fopen(__DIR__ . '/../Data/' . ($control ? 'day4control.txt' : 'day4.txt'), 'rb');

        while (($line = fgets($fp)) !== false) {
            yield trim($line, "\r\n");
        }

        fclose($fp);
    }
}
