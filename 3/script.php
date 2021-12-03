<?php

declare(strict_types=1);

$lines = file('input.txt');

//$lines = [
//    '00100',
//    '11110',
//    '10110',
//    '10111',
//    '10101',
//    '01111',
//    '00111',
//    '11100',
//    '10000',
//    '11001',
//    '00010',
//    '01010',
//];

function partOne(array $lines) {
    $gamma = '';
    $epsilon = '';

    $bits = array_map('str_split', $lines);

    for ($i=0; $i<count($bits[0]); $i++) {
        $values = array_count_values(array_column($bits, $i));
        asort($values, SORT_NUMERIC);
        $gamma .= array_key_last($values);
        $epsilon .= array_key_first($values);
    }

    return bindec($gamma) * bindec($epsilon);
}

function partTwo(array $lines) {
    $bits = array_map('str_split', $lines);

    $oxygen_generator_rating = findOxygenGeneratorRating($bits, 0);
    $co2_scrubber_rating = findCo2ScrubberRating($bits, 0);

    return bindec($oxygen_generator_rating) * bindec($co2_scrubber_rating);
}

function findOxygenGeneratorRating(array $bits, int $position): string {
    if (count($bits) > 1 && $position < count($bits[array_key_first($bits)])) {
        $values = array_count_values(array_column($bits, $position));
        if ($values[0] === $values[1] || $values[0] < $values[1]) {
            $keep = 1;
        } else {
            $keep = 0;
        }

        $bits = array_filter($bits, static function($bitArray) use ($keep, $position) {
            return $bitArray[$position] == $keep;
        });
    } else {
        return implode('', $bits[array_key_first($bits)]);
    }
    return findOxygenGeneratorRating($bits, ++$position);
}

function findCo2ScrubberRating(array $bits, int $position): string {
    if (count($bits) > 1 && $position < count($bits[array_key_first($bits)])) {
        $values = array_count_values(array_column($bits, $position));
        if ($values[0] === $values[1] || $values[0] < $values[1]) {
            $keep = 0;
        } else {
            $keep = 1;
        }

        $bits = array_filter($bits, static function($bitArray) use ($keep, $position) {
            return $bitArray[$position] == $keep;
        });
    } else {
        return implode('', $bits[array_key_first($bits)]);
    }
    return findCo2ScrubberRating($bits, ++$position);
}

echo 'Power consumption: ' . partOne($lines) . PHP_EOL;
echo 'Life support rating: ' . partTwo($lines) . PHP_EOL;
