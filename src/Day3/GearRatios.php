<?php

declare(strict_types=1);

namespace App\Day3;

/**
 * You play several games and record the information from each game (your puzzle input).
 * Each game is listed with its ID number (like the 11 in Game 11: ...) followed by a
 * semicolon-separated list of subsets of cubes that were revealed from the bag (like 3 red, 5 green, 4 blue).
 * The Elf would first like to know which games would have been possible if the bag contained only 12 red cubes, 13 green cubes, and 14 blue cubes?
 */
final class GearRatios
{
    public function part1(): int
    {
        $lineGenerator = $this->readLine();
        $sum = 0;
        $rows = [];
        foreach ($lineGenerator as $line) {
            $rows[] = $line;
        }
        // for each line, get the numbers & their positions
        // check if there's a symbol to the left or to the right of the numbers
        // check if there's a symbol in the previous line at the positions of the numbers
        // check if there's a symbol in the next line at the positions of the numbers
        // if there's a symbol in the previous line or the next line, add the number to the sum
        $iMax = count($rows);
        foreach ($rows as $i => $row) {
            $numbersAndPositions = [];
            preg_match_all('/\d+/', $row, $numbersAndPositions, PREG_OFFSET_CAPTURE);

            foreach ($numbersAndPositions[0] as $numberAndPosition) {
                [$number, $position] = $numberAndPosition;

                // no need to check to the left if col pos is 0
                if ($position !== 0 && $row[$position - 1] !== '.') {
                    $sum += (int) $number;
                    continue;
                }
                // try to check to the right
                if (isset($row[$position + strlen($number)]) && $row[$position + strlen($number)] !== '.') {
                    $sum += (int) $number;
                    continue;
                }
                // check in the previous line
                if ($i !== 0) {
                    $previousChars = substr($rows[$i - 1], $position === 0 ? 0 : $position - 1, strlen($number) + 2);
                    if (preg_match('/[^\d.]/', $previousChars) === 1) {
                        $sum += (int) $number;
                        continue;
                    }
                }
                // check in the next line
                if ($i !== $iMax - 1) {
                    $nextChars = substr($rows[$i + 1], $position === 0 ? 0 : $position - 1, strlen($number) + 2);
                    if (preg_match('/[^\d.]/', $nextChars) === 1) {
                        $sum += (int) $number;
                    }
                }
            }
        }

        return $sum;
    }

    public function part2(): int
    {
        $lineGenerator = $this->readLine(true);
        $sum = 0;

        return $sum;
    }

    private function readLine(bool $useControl = false): \Generator
    {
        $fp = fopen(__DIR__ . '/../Data/'. ($useControl ? 'day3control.txt' : 'day3.txt'), 'rb');

        while (($line = fgets($fp)) !== false) {
            yield trim($line, "\r\n");
        }

        fclose($fp);
    }
}
