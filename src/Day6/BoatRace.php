<?php

declare(strict_types=1);

namespace App\Day6;

final class BoatRace
{
    public function part1(): int
    {
        $lines = iterator_to_array($this->readLine());
        $times = array_values(array_map(static fn(string $item): int => (int) $item, array_filter(explode(' ', $lines[0]), static fn(string $item): bool => $item !== '' && is_numeric($item))));
        $distances = array_values(array_map(static fn(string $item): int => (int) $item, array_filter(explode(' ', $lines[1]), static fn(string $item): bool => $item !== '' && is_numeric($item))));

        $possibleWinnings = [];
        $n = 0;
        foreach ($times as $tx => $timePerRace) {
            for ($i = 0; $i < $timePerRace; $i++) {
                if ($i === 0 || $i === $timePerRace - 1) {
                    continue;
                }

                $distance = ($timePerRace - ($i + 1)) * ($i + 1);
                if ($distance > $distances[$tx]) {
                    ++$possibleWinnings[$n];
                }
            }
            $n++;
        }

        return array_product($possibleWinnings);
    }

    public function part2(): int
    {
        $lines = iterator_to_array($this->readLine());
        $time = (int) implode(array_filter(str_split($lines[0]), static fn(string $char): bool => is_numeric($char)));
        $distance = (int) implode(array_filter(str_split($lines[1]), static fn(string $char): bool => is_numeric($char)));

        $possibleWinnings = 0;
        for ($i = 0; $i < $time; $i++) {
            if ($i === 0 || $i === $time - 1) {
                continue;
            }

            $possibleDistance = ($time - ($i + 1)) * ($i + 1);
            if ($possibleDistance > $distance) {
                $possibleWinnings++;
            }
        }

        return $possibleWinnings;
    }

    private function readLine(bool $control = false): \Generator
    {
        $fp = fopen(__DIR__ . '/../Data/' . ($control ? 'day6control.txt' : 'day6.txt'), 'rb');

        while (($line = fgets($fp)) !== false) {
            yield trim($line, "\r\n");
        }

        fclose($fp);
    }
}
