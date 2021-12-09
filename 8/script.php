<?php

declare(strict_types=1);

$lines = file('input.txt');

//$lines = [
//    'be cfbegad cbdgef fgaecd cgeb fdcge agebfd fecdb fabcd edb | fdgacbe cefdb cefbgd gcbe',
//    'edbfga begcd cbg gc gcadebf fbgde acbgfd abcde gfcbed gfec | fcgedb cgb dgebacf gc',
//    'fgaebd cg bdaec gdafb agbcfd gdcbef bgcad gfac gcb cdgabef | cg cg fdcagb cbg',
//    'fbegcd cbd adcefb dageb afcb bc aefdc ecdab fgdeca fcdbega | efabcd cedba gadfec cb',
//    'aecbfdg fbg gf bafeg dbefa fcge gcbea fcaegb dgceab fcbdga | gecf egdcabf bgf bfgea',
//    'fgeab ca afcebg bdacfeg cfaedg gcfdb baec bfadeg bafgc acf | gebdcfa ecba ca fadegcb',
//    'dbcfg fgd bdegcaf fgec aegbdf ecdfab fbedc dacgb gdcebf gf | cefg dcbef fcge gbcadfe',
//    'bdfegc cbegaf gecbf dfcage bdacg ed bedf ced adcbefg gebcd | ed bcgafe cdgba cbgef',
//    'egadfb cdbfeg cegd fecab cgb gbdefca cg fgcdab egfdb bfceg | gbdfcae bgc cg cgb',
//    'gcafb gcf dcaebfg ecagb gf abcdeg gaef cafbge fdbac fegbdc | fgae cfgab fg bagce',
//];

function parseInput(array $lines) {
    $input = [];
    foreach ($lines as $line) {
        [$signalString, $outputString] = explode(' | ', $line);
        $input[] = [
            'signals' => explode(' ', trim($signalString)),
            'output' => explode(' ', trim($outputString)),
        ];
    }

    return $input;
}

function partOne(array $lines) {

    $input = parseInput($lines);
    $output = [
        1 => 0,
        4 => 0,
        7 => 0,
        8 => 0,
    ];
    $outputSegments = array_column($input, 'output');
    foreach ($outputSegments as $segments) {
        foreach ($segments as $segment) {
            switch (strlen($segment)) {
                case 2:
                    $output[1]++;
                    break;
                case 4:
                    $output[4]++;
                    break;
                case 3:
                    $output[7]++;
                    break;
                case 7:
                    $output[8]++;
                    break;
            }
        }
    }

    return array_sum($output);
}

function partTwo(array $lines) {

    $input = parseInput($lines);
    $totalInput = count($input);
    $i = 0;
    $output = [];
    foreach ($input as $inputSegments) {
        $decodedNumbers = [];
        $decodedSegments = [];
        $i++;

        echo "Starting decoding $i/$totalInput...\n";
        do {
            foreach ($inputSegments['signals'] as $signal) {
                $signal = str_split($signal);
                sort($signal);
                switch (count($signal)) {
                    case 2: // 1
                        if (!isset($decodedNumbers[1])) {
                            echo "Found 1\n";
                            $decodedNumbers[1] = $signal;
                        }
                        if (isset($decodedNumbers[7]) && !isset($decodedSegments['a'])) {
                            $topSegment = array_diff($decodedNumbers[7], $decodedNumbers[1]);
                            $decodedSegments['a'] = end($topSegment);
                            unset($topSegment);
                            echo "Found A\n";
                        }
                        break;
                    case 3: // 7
                        if (!isset($decodedNumbers[7])) {
                            $decodedNumbers[7] = $signal;
                            echo "Found 7\n";
                        }
                        if (isset($decodedNumbers[1]) && !isset($decodedSegments['a'])) {
                            $topSegment = array_diff($decodedNumbers[7], $decodedNumbers[1]);
                            $decodedSegments['a'] = end($topSegment);
                            unset($topSegment);
                            echo "Found A\n";
                        }
                        break;
                    case 4: // 4
                        if (!isset($decodedNumbers[4])) {
                            $decodedNumbers[4] = $signal;
                            echo "Found 4\n";
                        }
                        if (isset($decodedNumbers[3]) && !isset($decodedNumbers[9])) {
                            echo "Found 9\n";
                            $nine = array_values(array_unique(array_merge($decodedNumbers[3], $decodedNumbers[4])));
                            sort($nine);
                            $decodedNumbers[9] = $nine;
                        }
                        break;
                    case 5:
                        // 2,3,5
                        if (isset($decodedNumbers[1]) && !isset($decodedNumbers[3])) {
                            $intersect = array_intersect($signal, $decodedNumbers[1]);
                            sort($intersect);
                            if ($intersect === $decodedNumbers[1]) {
                                // Has to be 3
                                $decodedNumbers[3] = $signal;
                                echo "Found 3\n";
                                if (isset($decodedNumbers[4]) && !isset($decodedNumbers[9])) {
                                    echo "Found 9\n";
                                    $nine = array_values(array_unique(array_merge($decodedNumbers[3], $decodedNumbers[4])));
                                    sort($nine);
                                    $decodedNumbers[9] = $nine;
                                }
                            }
                        } elseif (isset($decodedSegments['e'], $decodedNumbers[3]) && $signal !== $decodedNumbers[3] && !isset($decodedNumbers[2], $decodedNumbers[5])) {
                            if (in_array($decodedSegments['e'], $signal, true)) {
                                // Must be 2
                                $decodedNumbers[2] = $signal;
                                echo "Found 2\n";
                            } else {
                                $decodedNumbers[5] = $signal;
                                echo "Found 5\n";
                            }
                        }
                        break;
                    case 6:
                        // 0,6,9
                        if (isset($decodedNumbers[1], $decodedNumbers[9]) && $signal !== $decodedNumbers[9] && !isset($decodedNumbers[0], $decodedNumbers[6])) {
                            $intersect = array_intersect($signal, $decodedNumbers[1]);
                            sort($intersect);
                            if ($intersect === $decodedNumbers[1]) {
                                // Has to be 0
                                $decodedNumbers[0] = $signal;
                                echo "Found 0\n";
                                if (!isset($decodedSegments['e']) && isset($decodedNumbers[8])) {
                                    $bottomLeftSegment = array_diff($decodedNumbers[8], $decodedNumbers[9]);
                                    $decodedSegments['e'] = end($bottomLeftSegment);
                                    unset($bottomLeftSegment);
                                    echo "Found E\n";
                                }
                            } elseif (!isset($decodedNumbers[6])) {
                                $decodedNumbers[6] = $signal;
                                echo "Found 6\n";
                            }
                        }
                        break;
                    case 7: // 8
                        if (!isset($decodedNumbers[8])) {
                            $decodedNumbers[8] = $signal;
                            echo "Found 8\n";
                            if (!isset($decodedSegments['e']) && isset($decodedNumbers[8], $decodedNumbers[9])) {
                                $bottomLeftSegment = array_diff($decodedNumbers[8], $decodedNumbers[9]);
                                $decodedSegments['e'] = end($bottomLeftSegment);
                                unset($bottomLeftSegment);
                                echo "Found E\n";
                            }
                        }
                        break;
                }
            }
        } while (count($decodedNumbers) < 10);
        $decodedNumbers = array_map(fn($n) => implode('', $n), $decodedNumbers);
        $decodedNumbers = array_flip($decodedNumbers);

        $inputSegments['output'] = array_map(static function (string $encodedDigit) {
            $splitDigit = str_split($encodedDigit);
            sort($splitDigit);
            return implode('', $splitDigit);
        }, $inputSegments['output']);
        $output[] = implode('', array_map(fn ($d) => $decodedNumbers[$d], $inputSegments['output']));
    }

    return array_sum($output);
}

echo partOne($lines) . PHP_EOL;
echo partTwo($lines) . PHP_EOL;
