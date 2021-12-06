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

function partTwo(array $lines, int $numDays) {

    $ages = parseLines($lines);
    $fishCount = array_count_values($ages);
    for ($day=1; $day<=$numDays; $day++) {
        $resetFish = $fishCount[0] ?? 0;
        $fishCount[0] = $fishCount[1] ?? 0;
        $fishCount[1] = $fishCount[2] ?? 0;
        $fishCount[2] = $fishCount[3] ?? 0;
        $fishCount[3] = $fishCount[4] ?? 0;
        $fishCount[4] = $fishCount[5] ?? 0;
        $fishCount[5] = $fishCount[6] ?? 0;
        $fishCount[6] = ($fishCount[7] ?? 0) + $resetFish;
        $fishCount[7] = $fishCount[8] ?? 0;
        $fishCount[8] = $resetFish;
    }
    return array_sum($fishCount);
}

echo partOne($lines, 80) . PHP_EOL;
echo partTwo($lines, 256) . PHP_EOL;
