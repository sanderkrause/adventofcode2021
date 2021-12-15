<?php

declare(strict_types=1);

$lines = file('input.txt');

//$lines = [
//    'NNCB',
//    '',
//    'CH -> B',
//    'HH -> N',
//    'CB -> H',
//    'NH -> C',
//    'HB -> C',
//    'HC -> B',
//    'HN -> C',
//    'NN -> C',
//    'BH -> H',
//    'NC -> B',
//    'NB -> B',
//    'BN -> B',
//    'BB -> N',
//    'BC -> B',
//    'CC -> N',
//    'CN -> C',
//];


function parseReplacements(array $lines)
{
    $replacements = [];
    foreach ($lines as $line) {
        if (preg_match('/([A-Z])([A-Z])\s->\s([A-Z]+)/', $line, $matches)) {
            [, $left, $right, $insert] = $matches;
            $replacements["$left$right"] = $insert;
        }
    }
    return $replacements;
}

function partOne(array $lines, int $steps)
{
    $template = trim(array_shift($lines));
    $replacements = parseReplacements($lines);

    // Apply 10 steps of pair insertion to the polymer template
    for ($step = 1; $step <= $steps; $step++) {
        $injections = [];
        for ($i = 0; $i < strlen($template) - 1; $i++) {
            $pair = $template[$i] . $template[$i + 1];
//            echo "Pair found: $pair\n";
            if (isset($replacements[$pair])) {
                $injectIndex = $i + 1;
//                echo "Registering injection ${replacements[$pair]} at index $injectIndex\n";
                $injections[$injectIndex] = $replacements[$pair];
            }
        }
        // Do replacements in one go
        $offset = 0;
        foreach ($injections as $index => $inject) {
            $before = substr($template, 0, $index + $offset);
            $after = substr($template, $index + $offset);
            $template = $before . $inject . $after;
            // After each injection, the injection offset changes
            $offset++;
        }

        echo "Step $step completed\n";
    }
    $templateAsArray = str_split($template);
    // and find the most and least common elements in the result.
    $count = array_count_values($templateAsArray);
    // What do you get if you take the quantity of the most common element and subtract the quantity of the least common element?
    return max($count) - min($count);
}

function partTwo(array $lines, int $steps)
{
    $template = trim(array_shift($lines));
    $injections = parseReplacements($lines);
    $pairCount = [];

    // Count characters from initial template
    $characterCount = [];
    foreach (count_chars($template, 1) as $i => $count) {
        $characterCount[chr($i)] = $count;
    }

    // Go through the initial template once to count pairs
    for ($i = 0; $i < strlen($template) - 1; $i++) {
        $pair = $template[$i] . $template[$i + 1];
        $pairCount[$pair] ??= 0;
        $pairCount[$pair]++;
    }

    // Apply 40 steps of pair insertion to the polymer template
    for ($step = 1; $step <= $steps; $step++) {
        $newPairs = [];
        $removedPairs = [];
        foreach ($injections as $pair => $injection) {
            if (isset($pairCount[$pair]) && $pairCount[$pair] > 0) {
                // Remove the old pair
                $removedPairs[$pair] ??= 0;
                $removedPairs[$pair] += $pairCount[$pair];
                // Add character count for the injected character
                $characterCount[$injection] ??= 0;
                $characterCount[$injection] += $pairCount[$pair];
                // Construct the two new pairs
                $newPairs[$pair[0] . $injection] ??= 0;
                $newPairs[$pair[0] . $injection] += $pairCount[$pair];
                $newPairs[$injection . $pair[1]] ??= 0;
                $newPairs[$injection . $pair[1]] += $pairCount[$pair];
            }
        }
        // Merge counts
        // Add counted new pairs
        foreach ($newPairs as $pair => $count) {
            $pairCount[$pair] ??= 0;
            $pairCount[$pair] += $count;
        }
        // Remove counted changed pairs
        foreach ($removedPairs as $pair => $count) {
            $pairCount[$pair] -= $count;
        }

        $totalCount = array_sum($characterCount);
        echo "Step $step completed, length of $totalCount\n";
    }
    // Keep only non-zero character counts
    $characterCount = array_filter($characterCount);
    // and find the most and least common elements in the result.
    // What do you get if you take the quantity of the most common element and subtract the quantity of the least common element?
    return max($characterCount) - min($characterCount);
}

//echo partOne($lines, 10) . PHP_EOL;
echo partTwo($lines, 40) . PHP_EOL;

