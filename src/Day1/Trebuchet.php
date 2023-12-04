<?php

declare(strict_types=1);

namespace App\Day1;

/**
 * The newly-improved calibration document consists of lines of text;
 * each line originally contained a specific calibration value that the Elves now need to recover.
 * On each line, the calibration value can be found by combining the first digit
 * and the last digit (in that order) to form a single two-digit number.
 */
final class Trebuchet
{
    public function sumOfCalibrationValues(): int
    {
        $lineGenerator = $this->readLine();
        $sum = 0;
        foreach ($lineGenerator as $line) {
            $numbers = filter_var($line, FILTER_SANITIZE_NUMBER_INT);
            $first = $numbers[0];
            $last = $numbers[strlen($numbers) - 1];
            $sum += (int) ($first . $last);
        }

        return $sum;
    }

    private function readLine(bool $useLetters = false): \Generator
    {
        $file = $useLetters ? 'day1pt2.txt' : 'day1.txt';
        $fp = fopen(__DIR__ . '/../Data/' . $file, 'rb');

        while (($line = fgets($fp)) !== false) {
            yield trim($line, "\r\n");
        }

        fclose($fp);
    }

    public function sumOfCalibrationValuesWithText(): int
    {
        $numbersMap = ['one' => '1', 'two' => '2', 'three' => '3', 'four' => '4', 'five' => '5', 'six' => '6', 'seven' => '7', 'eight' => '8', 'nine' => '9'];

        $sum = 0;
        $lineGenerator = $this->readLine(true);
        foreach ($lineGenerator as $line) {
            $digits = [];
            $len = strlen($line);
            $i = 0;

            // special case = first char is digit
            if (is_numeric($line[0])) {
                $digits[] = $line[0];
                $i = 1;
            }

            // read the line char by char, in chunks of 5
            while ($i < $len) {
                $curText = substr($line, $i, 5);

                // special case = first char is digit
                if (is_numeric($curText[0])) {
                    $digits[] = $curText[0];
                    $i++;
                    continue;
                }

                // exact text match = 3, 7, 8
                if (in_array($curText, ['three', 'seven', 'eight'], true)) {
                    $digits[] = $numbersMap[$curText];
                    $i += 4;
                    continue;
                }

                // text match = 4, 5, 9
                foreach (['four', 'five', 'nine'] as $needle) {
                    if (str_contains($curText, $needle)) {
                        $digits[] = $numbersMap[$needle];
                        $i += 3;
                        continue 2;
                    }
                }

                // text match = 1, 2, 6: this case can generate duplicates, but it doesn't matter for the result
                foreach (['one', 'two', 'six'] as $needle) {
                    if (str_contains($curText, $needle)) {
                        $digits[] = $numbersMap[$needle];
                        $i += 2;
                        continue 2;
                    }
                }

                // number match: we need to check character by character in the current text
                foreach (str_split($curText) as $char) {
                    if (is_numeric($char)) {
                        $digits[] = $char;
                        $i++;
                        continue 2;
                    }
                }

                $i++;
            }

            // when only one number is found
            if (count($digits) === 1) {
                $digits[] = $digits[0];
            }

            $sum += (int) ($digits[0] . $digits[count($digits) - 1]);
        }

        return $sum;
    }
}
