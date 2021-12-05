<?php

declare(strict_types=1);

$lines = file('input.txt');

//$lines = [
//    '0,9 -> 5,9', // Keep
//    '8,0 -> 0,8',
//    '9,4 -> 3,4', // Keep
//    '2,2 -> 2,1', // Keep
//    '7,0 -> 7,4', // Keep
//    '6,4 -> 2,0',
//    '0,9 -> 2,9', // Keep
//    '3,4 -> 1,4', // Keep
//    '0,0 -> 8,8',
//    '5,5 -> 8,2',
//];

function parseCoords(array $lines, $diagonal = false): array {
    $coords = [];
    foreach ($lines as $coordLine) {
        if (preg_match('/(\d+),(\d+)\s+->\s+(\d+),(\d+)/', $coordLine, $parsed)) {
            // Only consider straight lines
            if ($diagonal || $parsed[1] === $parsed[3] || $parsed[2] === $parsed[4]) {
                $coords[] = [
                    'x1' => (int)$parsed[1],
                    'y1' => (int)$parsed[2],
                    'x2' => (int)$parsed[3],
                    'y2' => (int)$parsed[4],
                ];
            }
        }
    }
    return $coords;
}

function partOne(array $lines) {

    $coords = parseCoords($lines);
    $coordsHit = [];

    // Generate a list of points hit in line
    foreach ($coords as $coordLine) {
        ['x1' => $x1, 'x2' => $x2, 'y1' => $y1, 'y2' => $y2] = $coordLine;
//        echo "$x1,$y1 -> $x2,$y2\n";
        if ($x1 === $x2) {
            $lowY = min($y1, $y2);
            $highY = max($y1, $y2);
            for ($i=$lowY; $i<=$highY; $i++) {
//                echo "Adding $x1,$i\n";
                $coordsHit[] = "$x1,$i";
            }
        }
        if ($y1 === $y2) {
            $lowX = min($x1, $x2);
            $highX = max($x1, $x2);
            for ($i=$lowX; $i<=$highX; $i++) {
//                echo "Adding $i,$y1\n";
                $coordsHit[] = "$i,$y1";
            }
        }
    }

    return count(array_filter(array_count_values($coordsHit), fn($c) => $c >= 2));
}

function partTwo(array $lines) {

    $coords = parseCoords($lines, true);
    $coordsHit = [];

    // Generate a list of points hit in line
    foreach ($coords as $coordLine) {
        ['x1' => $x1, 'x2' => $x2, 'y1' => $y1, 'y2' => $y2] = $coordLine;
        if ($x1 === $x2) {
            $lowY = min($y1, $y2);
            $highY = max($y1, $y2);
            for ($i=$lowY; $i<=$highY; $i++) {
                $coordsHit[] = "$x1,$i";
            }
        }
        elseif ($y1 === $y2) {
            $lowX = min($x1, $x2);
            $highX = max($x1, $x2);
            for ($i=$lowX; $i<=$highX; $i++) {
                $coordsHit[] = "$i,$y1";
            }
        }
        else {
//            echo "Diagonal line $x1,$y1 -> $x2,$y2\n";
//            echo "Adding $x1,$y1\n";
            $coordsHit[] = "$x1,$y1";
            $x = $x1;
            $y = $y1;
            do {
                if ($x1 < $x2) {
                    $x++;
                } else {
                    $x--;
                }
                if ($y1 < $y2) {
                    $y++;
                } else {
                    $y--;
                }
//                echo "Adding $x,$y\n";
                $coordsHit[] = "$x,$y";
            } while ("$x,$y" !== "$x2,$y2");
        }
    }

    return count(array_filter(array_count_values($coordsHit), fn($c) => $c >= 2));
}

echo partOne($lines) . PHP_EOL;
echo partTwo($lines) . PHP_EOL;
