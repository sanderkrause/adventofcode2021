<?php

declare(strict_types=1);

$lines = file('input.txt');
//$lines = [
//    '3,4,3,1,2',
//];

function parseLines(array $lines): array
{
    $ages = explode(',', array_shift($lines));
    return array_map(fn($age) => (int)$age, $ages);
}

function partOne(array $lines, int $numDays) {

    $ages = parseLines($lines);
//    echo "Initial state: " . implode(',', $ages) . PHP_EOL;
    for ($day=1; $day<=$numDays; $day++) {
        $fishCount = count($ages);
        for ($i=0; $i<$fishCount; $i++) {
            $ages[$i]--;
            if ($ages[$i] < 0) {
                $ages[$i] = 6;
                $ages[] = 8;
            }
        }
//        echo "After day $day: " . implode(',', $ages) . PHP_EOL;
    }
    return count($ages);
}

function partTwo(array $lines) {

    $ages = parseLines($lines);
    $fishCount = array_count_values($lines);
    for ($day=1; $day<=256; $day++) {
        // @todo now what?
    }
    return array_sum($fishCount);
}

echo partOne($lines, 80) . PHP_EOL;
//echo partTwo($lines) . PHP_EOL;
