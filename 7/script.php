<?php

declare(strict_types=1);

$input = trim(file_get_contents('input.txt'));

//$input = '16,1,2,0,4,2,7,1,2,14';

$input = explode(',', $input);

function partOne(array $input) {

    $fuelConsumption = [];
    $range = range(min($input),max($input));

    foreach ($range as $position) {
        $fuelConsumption[$position] = array_sum(array_map(static function(int $crab) use ($position) {
            return abs($crab - $position);
        }, $input));
    }
    return min($fuelConsumption);
}

function partTwo(array $input) {

    $fuelConsumption = [];
    $range = range(min($input),max($input));

    foreach ($range as $position) {
        $fuelConsumption[$position] = array_sum(array_map(static function(int $crab) use ($position) {
            $moves = abs($crab - $position);
            return array_sum(range(1, $moves));
        }, $input));
    }
    return min($fuelConsumption);
}

echo partOne($input) . PHP_EOL;
echo partTwo($input) . PHP_EOL;
