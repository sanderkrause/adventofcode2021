<?php

declare(strict_types=1);

$lines = file('input.txt');

//$lines = [
//    'forward 5',
//    'down 5',
//    'forward 8',
//    'up 3',
//    'down 8',
//    'forward 2',
//];

function partOne(array $lines): int {

    $horizontalPosition = 0;
    $depth = 0;

    foreach ($lines as $line) {
        [$instruction, $amount] = explode(' ', $line);
        switch ($instruction) {
            case 'forward':
                $horizontalPosition += (int)$amount;
                break;
            case 'down':
                $depth += (int)$amount;
                break;
            case 'up':
                $depth -= (int)$amount;
        }
    }

    return $horizontalPosition * $depth;
}

function partTwo(array $lines) {

    $horizontalPosition = 0;
    $depth = 0;
    $aim = 0;

    foreach ($lines as $line) {
        [$instruction, $amount] = explode(' ', $line);
        switch ($instruction) {
            case 'forward':
                $horizontalPosition += (int)$amount;
                $depth += ($aim * (int)$amount);
                break;
            case 'down':
                $aim += (int)$amount;
                break;
            case 'up':
                $aim -= (int)$amount;
        }
    }

    return $horizontalPosition * $depth;
}

//echo partOne($lines) . PHP_EOL;
echo partTwo($lines) . PHP_EOL;
