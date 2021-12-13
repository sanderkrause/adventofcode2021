<?php

declare(strict_types=1);

$lines = file('input.txt');

//$lines = [
//    '6,10',
//    '0,14',
//    '9,10',
//    '0,3',
//    '10,4',
//    '4,11',
//    '6,0',
//    '6,12',
//    '4,1',
//    '0,13',
//    '10,12',
//    '3,4',
//    '3,0',
//    '8,4',
//    '1,10',
//    '2,14',
//    '8,10',
//    '9,0',
//    '',
//    'fold along y=7',
//    'fold along x=5',
//];

function parseInput(array $lines): array
{
    $input = [
        'x' => [],
        'y' => [],
        'marked' => [],
        'folds' => [],
    ];
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }
        if (preg_match('/(\d+),(\d+)/', $line, $matches)) {
            $x = $matches[1];
            $y = $matches[2];
            $input['x'][] = (int)$x;
            $input['y'][] = (int)$y;
            $input['marked'][] = [$x, $y];
        }
        if (preg_match('/fold along (\w)=(\d+)/', $line, $matches)) {
            $foldAlongAxis = $matches[1];
            $foldPosition = (int)$matches[2];
            $input['folds'][] = ['axis' => $foldAlongAxis, 'position' => $foldPosition];
        }
    }

    return $input;
}

function buildPage(int $maxX, int $maxY, array $markedPositions): array
{
    $grid = array_fill(0, $maxY + 1, array_fill(0, $maxX + 1, '.'));

    foreach ($markedPositions as $position) {
        [$x, $y] = $position;
        $grid[$y][$x] = '#';
    }

    return $grid;
}

function foldPage(array $page, string $axis, int $position): array
{
    if ($axis === 'y') {
        for ($x=0; $x<count($page[$position]); $x++) {
            $page[$position][$x] = '-';
        }
        for ($y=$position+1; $y<count($page); $y++) {
            $yTarget = $position - ($y - $position);

            for ($x=0; $x<count($page[$yTarget]); $x++) {
                if ($page[$yTarget][$x] !== '#' && $page[$y][$x] === '#') {
                    $page[$yTarget][$x] = '#';
                }
            }
        }
        $page = array_slice($page, 0, $position);
    }
    if ($axis === 'x') {
        for ($y=0; $y<count($page); $y++) {
            $page[$y][$position] = '|';
        }
        // @todo fold by merging right into left?
        // @todo don't forget to reduce the size of the page array after merging
    }

    return $page;
}

function printPage(array $page): void
{
    foreach ($page as $y => $xAxis) {
        echo str_pad("$y", 5) . implode(' ', $xAxis) . PHP_EOL;
    }
}

function countDots(array $page): int
{
    $dots = 0;
    foreach ($page as $line) {
        $dots += array_count_values($line)['#'] ?? 0;
    }
    return $dots;
}

function partOne(array $lines)
{
    $input = parseInput($lines);
    $page = buildPage(max($input['x']), max($input['y']), $input['marked']);
//    printPage($page);die;

    foreach ($input['folds'] as $fold) {
        ['axis' => $foldAxis, 'position' => $foldPosition] = $fold;
        $foldedPage = foldPage($page, $foldAxis, $foldPosition);
//        printPage($foldedPage);
        // Just the first fold instruction
        return countDots($foldedPage);
    }
}

function partTwo(array $lines)
{
}

echo partOne($lines) . PHP_EOL;
echo partTwo($lines) . PHP_EOL;
