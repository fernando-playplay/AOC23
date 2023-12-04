<?php

declare(strict_types=1);

namespace App\Day2;

/**
 * You play several games and record the information from each game (your puzzle input).
 * Each game is listed with its ID number (like the 11 in Game 11: ...) followed by a
 * semicolon-separated list of subsets of cubes that were revealed from the bag (like 3 red, 5 green, 4 blue).
 * The Elf would first like to know which games would have been possible if the bag contained only 12 red cubes, 13 green cubes, and 14 blue cubes?
 */
final class CubeConundrum
{
    private const RED = 12;
    private const GREEN = 13;
    private const BLUE = 14;

    public function part1(): int
    {
        $lineGenerator = $this->readLine();
        $sum = 0;
        $game = 1;
        foreach ($lineGenerator as $line) {
            $redBallsMatches = [];
            $greenBallsMatches = [];
            $blueBallsMatches = [];

            // get all reds, if reds > 12, continue
            preg_match_all('/\d+ red/', $line, $redBallsMatches);
            $tooManyRedBalls = array_filter(
                array_map(static fn(string $match): int => (int) preg_replace('/\D/', '', $match), $redBallsMatches[0]),
                static fn(int $nbBalls): bool => $nbBalls > self::RED,
            );

            if ($tooManyRedBalls !== []) {
                $game++;
                continue;
            }

            // get all greens, if greens > 13, continue
            preg_match_all('/\d+ green/', $line, $greenBallsMatches);
            $tooManyGreenBalls = array_filter(
                array_map(static fn(string $match): int => (int) preg_replace('/\D/', '', $match), $greenBallsMatches[0]),
                static fn(int $nbBalls): bool => $nbBalls > self::GREEN,
            );

            if ($tooManyGreenBalls !== []) {
                $game++;
                continue;
            }

            // get all blues, if blues > 14, continue
            preg_match_all('/\d+ blue/', $line, $blueBallsMatches);
            $tooManyBlueBalls = array_filter(
                array_map(static fn(string $match): int => (int) preg_replace('/\D/', '', $match), $blueBallsMatches[0]),
                static fn(int $nbBalls): bool => $nbBalls > self::BLUE,
            );

            if ($tooManyBlueBalls !== []) {
                $game++;
                continue;
            }

            $sum += $game;
            $game++;
        }

        return $sum;
    }

    public function part2(): int
    {
        $lineGenerator = $this->readLine();
        $sum = 0;
        foreach ($lineGenerator as $line) {
            $redBallsMatches = [];
            $greenBallsMatches = [];
            $blueBallsMatches = [];

            preg_match_all('/\d+ red/', $line, $redBallsMatches);
            $maxRedValue = max(array_map(static fn(string $match): int => (int) preg_replace('/\D/', '', $match), $redBallsMatches[0]));
            preg_match_all('/\d+ green/', $line, $greenBallsMatches);
            $maxGreenValue = max(array_map(static fn(string $match): int => (int) preg_replace('/\D/', '', $match), $greenBallsMatches[0]));
            preg_match_all('/\d+ blue/', $line, $blueBallsMatches);
            $maxBlueValue = max(array_map(static fn(string $match): int => (int) preg_replace('/\D/', '', $match), $blueBallsMatches[0]));

            $sum += $maxBlueValue * $maxGreenValue * $maxRedValue;
        }

        return $sum;
    }

    private function readLine(): \Generator
    {
        $fp = fopen(__DIR__ . '/../Data/day2.txt', 'rb');

        while (($line = fgets($fp)) !== false) {
            yield preg_replace('/Game \d+: /', '', trim($line, "\r\n"));
        }

        fclose($fp);
    }
}
