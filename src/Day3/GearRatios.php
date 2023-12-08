<?php

declare(strict_types=1);

namespace App\Day3;

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
        $lineGenerator = $this->readLine();
        $sum = 0;
        // too many special cases, rethink strategy 😅
        $symbols = [];
        $digits = [];
        $numbers = [];
        // locate everything
        foreach ($lineGenerator as $rowIx => $row) {
            foreach (str_split($row) as $charIx => $char) {
                // skip points
                if ($char === '.') {
                    continue;
                }

                // locate symbols
                if ($char === '*') {
                    $symbols[$rowIx][$charIx] = $charIx;
                    continue;
                }

                // make sure the first digit of number is added
                if ($charIx && is_numeric($row[$charIx - 1])) {
                    $digits[count($digits) - 1] .= $char;
                } elseif (is_numeric($char)) {
                    $digits[] = $char;
                }

                // map of the numbers positions row by row
                $numbers[$rowIx][$charIx] = count($digits) - 1;
            }
        }

        // calculate the sum
        foreach ($symbols as $rowIx => $symbolsByRow) {
            foreach ($symbolsByRow as $symbolPos) {
                $toRatio = [];
                // search row behind
                if (isset($numbers[$rowIx - 1][$symbolPos - 1])) {
                    $toRatio[] = $digits[$numbers[$rowIx - 1][$symbolPos - 1]];
                }
                if (isset($numbers[$rowIx - 1][$symbolPos])) {
                    $toRatio[] = $digits[$numbers[$rowIx - 1][$symbolPos]];
                }
                if (isset($numbers[$rowIx - 1][$symbolPos + 1])) {
                    $toRatio[] = $digits[$numbers[$rowIx - 1][$symbolPos + 1]];
                }

                // search same row, left & right
                if (isset($numbers[$rowIx][$symbolPos - 1])) {
                    $toRatio[] = $digits[$numbers[$rowIx][$symbolPos - 1]];
                }
                if (isset($numbers[$rowIx][$symbolPos + 1])) {
                    $toRatio[] = $digits[$numbers[$rowIx][$symbolPos + 1]];
                }

                // search row ahead
                if (isset($numbers[$rowIx + 1][$symbolPos - 1])) {
                    $toRatio[] = $digits[$numbers[$rowIx + 1][$symbolPos - 1]];
                }
                if (isset($numbers[$rowIx + 1][$symbolPos])) {
                    $toRatio[] = $digits[$numbers[$rowIx + 1][$symbolPos]];
                }
                if (isset($numbers[$rowIx + 1][$symbolPos + 1])) {
                    $toRatio[] = $digits[$numbers[$rowIx + 1][$symbolPos + 1]];
                }

                // remove duplicates
                $toRatio = array_unique($toRatio);
                if (count($toRatio) === 2) {
                    $sum += (int) array_product($toRatio);
                }
            }
        }

        return $sum;
    }

    private function readLine(): \Generator
    {
        $fp = fopen(__DIR__ . '/../Data/day3.txt', 'rb');

        while (($line = fgets($fp)) !== false) {
            yield trim($line, "\r\n");
        }

        fclose($fp);
    }
}
