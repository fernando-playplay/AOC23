<?php

declare(strict_types=1);

namespace App\Day4;

final class Scratchcards
{
    public function part1(): int
    {
        $lineGenerator = $this->readLine();
        $sum = 0;
        foreach ($lineGenerator as $line) {
            $everything = array_values(array_filter(explode(' ', $line), static fn ($value) => $value !== ''));
            $separatorPos = array_search('|', $everything, true);
            $winningNums = array_slice($everything, 0, $separatorPos);
            $myNums = array_slice($everything, $separatorPos);

            $winnings = count(array_intersect($myNums, $winningNums)) - 1;
            if ($winnings < 0) {
                continue;
            }

            $sum += 2 ** $winnings;
        }

        return $sum;
    }

    public function part2(): int
    {
        $lineGenerator = $this->readLine();
        $card = 0;
        $cardCount = [];
        foreach ($lineGenerator as $line) {
            $everything = array_values(array_filter(explode(' ', $line), static fn ($value) => $value !== ''));
            $separatorPos = array_search('|', $everything, true);
            $winningNums = array_slice($everything, 0, $separatorPos);
            $myNums = array_slice($everything, $separatorPos);
            $winnings = count(array_intersect($myNums, $winningNums));

            $card++;
            $cardCount[$card] = ($cardCount[$card] ?? 0) + 1;
            if ($winnings !== 0) {
                for ($i = $card + 1; $i <= $card + $winnings; $i++) {
                    $cardCount[$i] = ($cardCount[$i] ?? 0) + $cardCount[$card];
                }
            }
        }
        return array_sum($cardCount);
    }

    private function readLine(): \Generator
    {
        $fp = fopen(__DIR__ . '/../Data/day4.txt', 'rb');

        while (($line = fgets($fp)) !== false) {
            yield preg_replace('/Card\s+\d+:\s+/', '', trim($line, "\r\n"));
        }

        fclose($fp);
    }
}
