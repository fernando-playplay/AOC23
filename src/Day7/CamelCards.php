<?php

declare(strict_types=1);

namespace App\Day7;

final class CamelCards
{
    private const CARD_STRENGTH = [
        'A' => 13,
        'K' => 12,
        'Q' => 11,
        'J' => 10,
        'T' => 9,
        '9' => 8,
        '8' => 7,
        '7' => 6,
        '6' => 5,
        '5' => 4,
        '4' => 3,
        '3' => 2,
        '2' => 1,
    ];

    const HAND_TYPES = [
        'FIVE_OF_A_KIND' => 7,
        'FOUR_OF_A_KIND' => 6,
        'FULL_HOUSE' => 5,
        'THREE_OF_A_KIND' => 4,
        'TWO_PAIRS' => 3,
        'ONE_PAIR' => 2,
        'HIGH_CARD' => 1,
    ];

    public function part1(): int
    {
        $lineGenerator = $this->readLine(true);
        $hands = [
            'FIVE_OF_A_KIND' => [],
            'FOUR_OF_A_KIND' => [],
            'FULL_HOUSE' => [],
            'THREE_OF_A_KIND' => [],
            'TWO_PAIRS' => [],
            'ONE_PAIR' => [],
            'HIGH_CARD' => [],
        ];

        // parse all cards in arrays of hand types
        // then reorder all of them by highest hand
        $handBids = [];
        foreach ($lineGenerator as $line) {
            [$hand, $bid] = explode(' ', $line);
            $handBids[$hand] = $bid;

            // check for five of a kind
            if (substr_count($hand, $hand[0]) === 5) {
                $hands['FIVE_OF_A_KIND'] = $this->sortAdd($hand, $hands['FIVE_OF_A_KIND'], 'FIVE_OF_A_KIND');
                continue;
            }
            // check for four of a kind
            if (in_array(4, array_count_values(str_split($hand)), true)) {
                $hands['FOUR_OF_A_KIND'] = $this->sortAdd($hand, $hands['FOUR_OF_A_KIND'], 'FOUR_OF_A_KIND');
                continue;
            }
            // check for full house
            if (in_array(3, array_count_values(str_split($hand)), true) && in_array(2, array_count_values(str_split($hand)), true)) {
                $hands['FULL_HOUSE'] = $this->sortAdd($hand, $hands['FULL_HOUSE'], 'FULL_HOUSE');
                continue;
            }

            //foreach (str_split(substr($hand, 1)) as $char) {
            //
            //}
        }
        var_dump($hands);die;

        return 0;
    }

    private function sortAdd(string $hand, array $hands, string $type): array
    {
        if ($hands === []) {
            $hands[] = $hand;
            return $hands;
        }

        // sort
        switch ($type) {
            case 'FIVE_OF_A_KIND':
                foreach ($hands as $key => $currentHand) {
                    if (self::CARD_STRENGTH[$hand[0]] > self::CARD_STRENGTH[$currentHand[0]]) {
                        array_splice($hands, $key, 0, $hand);
                        break;
                    }
                }
                break;
            default:
                foreach ($hands as $key => $currentHand) {
                    foreach (str_split($hand) as $ix => $char) {
                        if (self::CARD_STRENGTH[$char] > self::CARD_STRENGTH[$currentHand[$ix]]) {
                            array_splice($hands, $key, 0, $hand);
                            break;
                        }
                    }
                }
                break;
        }
        return $hands;
    }

    public function part2(): int
    {
        return 0;
    }

    private function readLine(bool $control = false): \Generator
    {
        $fp = fopen(__DIR__ . '/../Data/' . ($control ? 'day7control.txt' : 'day7.txt'), 'rb');

        while (($line = fgets($fp)) !== false) {
            yield trim($line, "\r\n");
        }

        fclose($fp);
    }
}
