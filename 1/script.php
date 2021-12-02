<?php

declare(strict_types=1);

$lines = file('input.txt');
$lines = array_map(static fn($n) => (int)$n, $lines);

/**
 * @param int[] $lines
 * @return int
 */
function partOne(array $lines): int
{
    $numIncreases = 0;
    $previousInput = null;

    foreach ($lines as $line) {
        if ($previousInput !== null && $line > $previousInput) {
            $numIncreases++;
        }
        $previousInput = $line;
    }
    return $numIncreases;
}

function partTwo(array $lines): int
{
    $numIncreases = 0;

    for ($i = 0; $i < (count($lines) - 3); $i++) {
        $previousWindow = array_slice($lines, $i, 3);
        $currentWindow = array_slice($lines, $i + 1, 3);
        if (array_sum($previousWindow) < array_sum($currentWindow)) {
            $numIncreases++;
        }
    }

    return $numIncreases;
}

echo partOne($lines) . PHP_EOL;
echo partTwo($lines);

echo PHP_EOL;
