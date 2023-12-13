<?php

declare(strict_types=1);

namespace App\Day5;

/**
 * The almanac starts by listing which seeds need to be planted: seeds 79, 14, 55, and 13.
 * The rest of the almanac contains a list of maps which describe how to convert numbers from a source category into numbers in a destination category. That is, the section that starts with seed-to-soil map: describes how to convert a seed number (the source) to a soil number (the destination). This lets the gardener and his team know which soil to use with which seeds, which water to use with which fertilizer, and so on.
 * Rather than list every source number and its corresponding destination number one by one, the maps describe entire ranges of numbers that can be converted. Each line within a map contains three numbers: the destination range start, the source range start, and the range length.
 * Consider again the example seed-to-soil map:
 *
 * 50 98 2
 * 52 50 48
 * The first line has a destination range start of 50, a source range start of 98, and a range length of 2. This line means that the source range starts at 98 and contains two values: 98 and 99. The destination range is the same length, but it starts at 50, so its two values are 50 and 51. With this information, you know that seed number 98 corresponds to soil number 50 and that seed number 99 corresponds to soil number 51.
 *
 * The second line means that the source range starts at 50 and contains 48 values: 50, 51, ..., 96, 97. This corresponds to a destination range starting at 52 and also containing 48 values: 52, 53, ..., 98, 99. So, seed number 53 corresponds to soil number 55.
 *
 * Any source numbers that aren't mapped correspond to the same destination number. So, seed number 10 corresponds to soil number 10.
 *
 * So, the entire list of seed numbers and their corresponding soil numbers looks like this:
 *
 * seed  soil
 * 0     0
 * 1     1
 * ...   ...
 * 48    48
 * 49    49
 * 50    52
 * 51    53
 * ...   ...
 * 96    98
 * 97    99
 * 98    50
 * 99    51
 * With this map, you can look up the soil number required for each initial seed number:
 *
 * Seed number 79 corresponds to soil number 81.
 * Seed number 14 corresponds to soil number 14.
 * Seed number 55 corresponds to soil number 57.
 * Seed number 13 corresponds to soil number 13.
 * The gardener and his team want to get started as soon as possible, so they'd like to know the closest location that needs a seed. Using these maps, find the lowest location number that corresponds to any of the initial seeds. To do this, you'll need to convert each seed number through other categories until you can find its corresponding location number. In this example, the corresponding types are:
 *
 * Seed 79, soil 81, fertilizer 81, water 81, light 74, temperature 78, humidity 78, location 82.
 * Seed 14, soil 14, fertilizer 53, water 49, light 42, temperature 42, humidity 43, location 43.
 * Seed 55, soil 57, fertilizer 57, water 53, light 46, temperature 82, humidity 82, location 86.
 * Seed 13, soil 13, fertilizer 52, water 41, light 34, temperature 34, humidity 35, location 35.
 * So, the lowest location number in this example is 35.
 *
 * What is the lowest location number that corresponds to any of the initial seed numbers?
 */
final class SeedLocations
{
    public function part1(): int
    {
        $lineGenerator = $this->readLine();
        $seeds = array_map(static fn (string $seed): int => (int) $seed, explode(' ', substr($lineGenerator->current(), 7)));

        $mapN = -1;
        $maps = [];
        // first create the maps and their ranges
        foreach ($lineGenerator as $line) {
            if (str_starts_with($line, 'seeds:')) {
                continue;
            }
            if (str_ends_with($line, 'map:')) {
                $mapN++;
                continue;
            }
            if ($line === '') {
                continue;
            }

            [$dest, $source, $length] = explode(' ', $line);
            $maps[$mapN][] = [
                'dest' => (int) $dest,
                'source' => (int) $source,
                'length' => (int) $length,
            ];
        }

        foreach ($seeds as $i => $seed) {
            $location = $seed;
            foreach ($maps as $map) {
                foreach ($map as $mapLine) {
                    if ($location >= $mapLine['source'] && $location <= $mapLine['source'] + $mapLine['length'] - 1) {
                        $location = $mapLine['dest'] + ($location - $mapLine['source']);
                        $seeds[$i] = $location;
                        continue 2;
                    }
                }
            }
        }

        return min($seeds);
    }

    public function part2(): int
    {
        $lineGenerator = $this->readLine(true);
        $rawSeeds = array_map(static fn (string $seed): int => (int) $seed, explode(' ', substr($lineGenerator->current(), 7)));
        $seedRanges = [];
        // first create the seed ranges
        for ($i = 0, $iMax = count($rawSeeds); $i < $iMax; $i += 2) {
            $seedRanges[] = ['source' => $rawSeeds[$i], 'dest' => $rawSeeds[$i] + ($rawSeeds[$i + 1] - 1)];
        }

        $mapN = -1;
        $maps = [];
        // then create the maps and their ranges
        foreach ($lineGenerator as $line) {
            if (str_starts_with($line, 'seeds:')) {
                continue;
            }
            if (str_ends_with($line, 'map:')) {
                $mapN++;
                continue;
            }
            if ($line === '') {
                continue;
            }

            [$dest, $source, $length] = explode(' ', $line);
            $maps[$mapN][] = [
                'dest' => (int) $dest,
                'source' => (int) $source,
                'length' => (int) $length,
            ];
        }

        // then for the fun part:
        // we need to check if the ranges overlap and if so, take the values for that range
        // something like this:
        //
        // no overlapping range, use full src range
        // x1-----x2                           x1-----x2
        //             y1----y2  or  y1----y2
        //
        // overlapping range starts after srcStart, truncate src range to [x1,y1]
        // x1-------------x2
        //        y1------------y2
        //
        // overlapping range starts before srcStart, truncate src range to [x1,y2].
        //        x1-------------x2
        // y1------------y2
        //
        // overlapping range contains src range, return full src range
        //      x1--x2
        // y1-------------y2

        foreach ($seedRanges as $i => $seed) {
            $location = $seed;
            foreach ($maps as $map) {
                foreach ($map as $mapLine) {
                    // overlaps ranges or matches perfectly
                    // seed.dest >= dest && dest+length-1 >= seed.source
                    if ($location['dest'] >= $mapLine['dest'] && ($mapLine['dest'] + $mapLine['length'] - 1) >= $location['source']) {
                        // check if the seed range overlaps with any map range
                        // then we need to get all the seed range parts that overlap

                        // case 1: fits perfectly in range, compute all seeds
                        if ($location['dest'] > $mapLine['dest'] && ($mapLine['dest'] + $mapLine['length'] - 1) > $location['source']) {
                            // TODO
                        }
                        var_dump('ee', $seed, $mapLine);die;
                        continue 2;
                    }
                }
            }
        }

        return min($seeds);
    }

    private function readLine(bool $control = false): \Generator
    {
        $fp = fopen(__DIR__ . '/../Data/' . ($control ? 'day5control.txt' : 'day5.txt'), 'rb');

        while (($line = fgets($fp)) !== false) {
            yield trim($line, "\r\n");
        }

        fclose($fp);
    }
}
