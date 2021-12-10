<?php

declare(strict_types=1);

$lines = file('input.txt');

//$lines = [
//    '2199943210',
//    '3987894921',
//    '9856789892',
//    '8767896789',
//    '9899965678',
//];

function parseInput(array $lines)
{
    return array_map(fn($l) => str_split(trim($l)), $lines);
}

function findLowestPoints(array $input)
{
    $lowPoints = [];
    for ($y = 0; $y < count($input); $y++) {
        for ($x = 0; $x < count($input[$y]); $x++) {
            $point = (int)$input[$y][$x];
            $surroundings = array_map(
                fn($i) => (int)$i,
                array_filter([
                                 'top' => $input[$y - 1][$x] ?? null,
                                 'bottom' => $input[$y + 1][$x] ?? null,
                                 'left' => $input[$y][$x - 1] ?? null,
                                 'right' => $input[$y][$x + 1] ?? null,
                             ], fn($i) => $i !== null)
            );
//            echo "Checking surroundings of $x,$y ($point)...\n";
            $lowestSurroundingValue = min($surroundings);
            if ($lowestSurroundingValue > $point) {
//                echo "Found low point: $y,$x ($point < $lowestSurroundingValue)\n";
                $lowPoints[] = [
                    'coords' => [$y, $x],
                    // The risk level of a low point is 1 plus its value.
                    'value' => $point + 1,
                ];
            }
        }
    }
    return $lowPoints;
}

function checkLeft(array $grid, int $startX, int $startY, array &$coords)
{
    while (isset($grid[$startY][--$startX]) && !in_array("$startY,$startX", $coords, true)) {
//        echo "[LEFT] grid[$startY][$startX] == {$grid[$startY][$startX]}\n";
        if ($grid[$startY][$startX] != 9) {
            $coords[] = "$startY,$startX";
            checkUp($grid, $startX, $startY, $coords);
            checkDown($grid, $startX, $startY, $coords);
        } else {
            break;
        }
    }
}

function checkRight(array $grid, int $startX, int $startY, array &$coords)
{
    while (isset($grid[$startY][++$startX]) && !in_array("$startY,$startX", $coords, true)) {
//        echo "[RIGHT] grid[$startY][$startX] == {$grid[$startY][$startX]}\n";
        if ($grid[$startY][$startX] != 9) {
            $coords[] = "$startY,$startX";
            checkUp($grid, $startX, $startY, $coords);
            checkDown($grid, $startX, $startY, $coords);
        } else {
            break;
        }
    }
}

function checkUp(array $grid, int $startX, int $startY, array &$coords)
{
    while (isset($grid[--$startY][$startX]) && !in_array("$startY,$startX", $coords, true)) {
//        echo "[UP] grid[$startY][$startX] == {$grid[$startY][$startX]}\n";
        if ($grid[$startY][$startX] != 9) {
            $coords[] = "$startY,$startX";
            checkLeft($grid, $startX, $startY, $coords);
            checkRight($grid, $startX, $startY, $coords);
        } else {
            break;
        }
    }
}

function checkDown(array $grid, int $startX, int $startY, array &$coords)
{
    while (isset($grid[++$startY][$startX]) && !in_array("$startY,$startX", $coords, true)) {
//        echo "[DOWN] grid[$startY][$startX] == {$grid[$startY][$startX]}\n";
        if ($grid[$startY][$startX] != 9) {
            $coords[] = "$startY,$startX";
            checkLeft($grid, $startX, $startY, $coords);
            checkRight($grid, $startX, $startY, $coords);
        } else {
            break;
        }
    }
}

function partOne(array $lines)
{
    $input = parseInput($lines);
    $lowPoints = findLowestPoints($input);
    return array_sum(array_column($lowPoints, 'value'));
}

function partTwo(array $lines)
{
    $input = parseInput($lines);
    $lowestPoints = findLowestPoints($input);
    $basinSizes = [];
    foreach (array_column($lowestPoints, 'coords') as $lowestPoint) {
        [$y, $x] = $lowestPoint;

        $coords = ["$y,$x"];
        checkLeft($input, $x, $y, $coords);
        checkRight($input, $x, $y, $coords);
        checkUp($input, $x, $y, $coords);
        checkDown($input, $x, $y, $coords);

        $basinSizes[] = count(array_unique($coords));
    }

    rsort($basinSizes);
    $topThreeBasins = array_slice($basinSizes, 0, 3);

    return array_product($topThreeBasins);
}

echo partOne($lines) . PHP_EOL;
echo partTwo($lines) . PHP_EOL;
