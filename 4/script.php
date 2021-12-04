<?php

declare(strict_types=1);

$lines = file('input.txt');

//$lines = [
//    '7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1',
//    '',
//    '22 13 17 11  0',
//    '8  2 23  4 24',
//    '21  9 14 16  7',
//    '6 10  3 18  5',
//    '1 12 20 15 19',
//    '',
//    '3 15  0  2 22',
//    '9 18 13 17  5',
//    '19  8  7 25 23',
//    '20 11 10 24  4',
//    '14 21 16 12  6',
//    '',
//    '14 21 17 24  4',
//    '10 16 15  9 19',
//    '18  8 23 26 20',
//    '22 11 13  6  5',
//    '2  0 12  3  7',
//];

function createBoards(array $lines): array {
    $boards = [];
    // Create boards
    $currentBoard = null;
    foreach ($lines as $line) {
        // Empty lines mark the beginning of a new board
        if (empty(trim($line))) {
            // Add board to boards collection
            if ($currentBoard !== null) {
                $boards[] = $currentBoard;
            }
            // Reset current board and move on
            $currentBoard = [];
            continue;
        }
        $currentBoard[] = preg_split('/\s+/', $line, -1, PREG_SPLIT_NO_EMPTY);
    }
    if (!empty($currentBoard)) {
        $boards[] = $currentBoard;
    }
    return $boards;
}

function calculateWinningSum(array $drawn, array $winningBoard): int {
    $flattenedBoard = array_merge(...$winningBoard);
    return array_sum(array_diff($flattenedBoard, $drawn));
}

function partOne(array $lines) {
    // Numbers to draw
    $drawPile = explode(',', array_shift($lines));
    // Numbers that have been drawn
    $drawn = [];
    // Bingo boards
    $boards = createBoards($lines);

    // Start drawing
    while (!empty($drawPile)) {
        // Add to drawn pile
        $currentDraw = array_shift($drawPile);
        $drawn[] = $currentDraw;

        foreach ($boards as $boardNo => $currentBoard) {
            foreach ($currentBoard as $lineNo => $boardLine) {
                if (in_array($currentDraw, $boardLine, true)) {
                    echo "[Board $boardNo] $currentDraw in " . implode(' ', $boardLine) . "\n";
                    // Check line for win
                    if (empty(array_diff($boardLine, $drawn))) {
                        echo "BINGO row $lineNo: " . implode(' ', $boardLine). "\n";
                        return calculateWinningSum($drawn, $currentBoard) * (int)$currentDraw;
                    }
                    // No win yet, but check columns from the position of the drawn number
                    $position = array_search($currentDraw, $boardLine, true);
                    if (empty(array_diff(array_column($currentBoard, $position), $drawn))) {
                        echo "BINGO column $position: " . implode(' ', array_column($currentBoard, $position)) . "\n";
                        return calculateWinningSum($drawn, $currentBoard) * (int)$currentDraw;
                    }
                    echo "No BINGO!\n";
                }
            }
        }
    }
    return 0;
}

function partTwo(array $lines) {
    // Which board loses? (wins LAST)
    // Numbers to draw
    $drawPile = explode(',', array_shift($lines));
    // Numbers that have been drawn
    $drawn = [];
    // Bingo boards
    $boards = createBoards($lines);
    // Eliminated boards
    $eliminated = [];

    // Start drawing
    while (!empty($drawPile)) {
        // Add to drawn pile
        $currentDraw = array_shift($drawPile);
        $drawn[] = $currentDraw;

        foreach ($boards as $boardNo => $currentBoard) {
            if (!in_array($boardNo, $eliminated, true)) {
                foreach ($currentBoard as $lineNo => $boardLine) {
                    if (in_array($currentDraw, $boardLine, true)) {
                        echo "[Board $boardNo] $currentDraw in " . implode(' ', $boardLine) . "\n";
                        // Check line for win
                        if (empty(array_diff($boardLine, $drawn))) {
                            echo "BINGO row $lineNo: " . implode(' ', $boardLine). "\n";
                            if ((count($boards) - count($eliminated) === 1)) {
                                echo "Board $boardNo won LAST!\n";
                                return calculateWinningSum($drawn, $currentBoard) * (int)$currentDraw;
                            }
                            echo "Eliminating board $boardNo\n";
                            $eliminated[] = $boardNo;
                            continue 2;
                        }
                        // No win yet, but check columns from the position of the drawn number
                        $position = array_search($currentDraw, $boardLine, true);
                        if (empty(array_diff(array_column($currentBoard, $position), $drawn))) {
                            echo "BINGO column $position: " . implode(' ', array_column($currentBoard, $position)) . "\n";
                            if ((count($boards) - count($eliminated) === 1)) {
                                echo "Board $boardNo won LAST!\n";
                                return calculateWinningSum($drawn, $currentBoard) * (int)$currentDraw;
                            }
                            echo "Eliminating board $boardNo\n";
                            $eliminated[] = $boardNo;
                            continue 2;
                        }
                        echo "No BINGO!\n";
                    }
                }
            }
        }
    }
    return 0;
}

echo partOne($lines) . PHP_EOL;
echo partTwo($lines) . PHP_EOL;
