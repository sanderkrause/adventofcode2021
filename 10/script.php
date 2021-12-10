<?php

declare(strict_types=1);

$lines = file('input.txt');

//$lines = [
//    '[({(<(())[]>[[{[]{<()<>>',
//    '[(()[<>])]({[<{<<[]>>(',
//    '{([(<{}[<>[]}>{[]{[(<()>',
//    '(((({<>}<{<{<>}{[]{[]{}',
//    '[[<[([]))<([[{}[[()]]]',
//    '[{[{({}]{}}([{[{{{}}([]',
//    '{<[[]]>}<{[{[{[]{()[[[]',
//    '[<(<(<(<{}))><([]([]()',
//    '<{([([[(<>()){}]>(<<{{',
//    '<{([{{}}[<[[[<>{}]]]>[]]',
//];


function partOne(array $lines)
{
    $tokenValues = [
        ')' => 3,
        ']' => 57,
        '}' => 1197,
        '>' => 25137,
    ];
    $openingTokens = [
        '(',
        '[',
        '<',
        '{',
    ];
    $closingTokens = [
        ')',
        ']',
        '>',
        '}',
    ];
    $matchingTokens = array_combine($openingTokens, $closingTokens);
    $syntaxErrors = array_combine($closingTokens, array_fill(0, 4, 0));

    // Ignore incomplete sequences for now
    foreach ($lines as $lineNo => $line) {
        $line = trim($line);
        $tokens = str_split($line);
        $openTokens = [array_shift($tokens)];
        foreach ($tokens as $pos => $token) {
            $currentOpenToken = end($openTokens);
            if (in_array($token, $closingTokens, true) && $token === $matchingTokens[$currentOpenToken]) {
                // Matching token found, close one level deep
                array_pop($openTokens);
            } elseif (in_array($token, $openingTokens, true)) {
                // New opening token
                $openTokens[] = $token;
            } else {
                echo "Possible syntax error on $lineNo:$pos unexpected '$token' in $line\n";
                $syntaxErrors[$token]++;
                break;
            }
        }
    }
    $errorTotal = 0;
    foreach ($syntaxErrors as $token => $count) {
        $errorTotal += $count * $tokenValues[$token];
    }
    return $errorTotal;
}

function partTwo(array $lines)
{
    $tokenValues = [
        ')' => 1,
        ']' => 2,
        '}' => 3,
        '>' => 4,
    ];
    $openingTokens = [
        '(',
        '[',
        '<',
        '{',
    ];
    $closingTokens = [
        ')',
        ']',
        '>',
        '}',
    ];
    $matchingTokens = array_combine($openingTokens, $closingTokens);
    $completions = [];

    foreach ($lines as $line) {
        $line = trim($line);
        $tokens = str_split($line);
        $openTokens = [array_shift($tokens)];
        foreach ($tokens as $token) {
            $currentOpenToken = end($openTokens);
            if (in_array($token, $closingTokens, true) && $token === $matchingTokens[$currentOpenToken]) {
                // Matching token found, close one level deep
                array_pop($openTokens);
            } elseif (in_array($token, $openingTokens, true)) {
                // New opening token
                $openTokens[] = $token;
            } else {
                // Skip lines containing a syntax error
                continue 2;
            }
        }
        // Should only come here on incomplete lines, so $openTokens should be filled
        $completions[] = array_map(fn($t) => $matchingTokens[$t], array_reverse($openTokens));
    }

    $completionScores = [];
    foreach ($completions as $completion) {
        $score = 0;
        foreach ($completion as $token) {
            $score *= 5;
            $score += $tokenValues[$token];
        }
        $completionScores[] = $score;
    }
    sort($completionScores);
    return $completionScores[intdiv(count($completionScores), 2)];
}

echo partOne($lines) . PHP_EOL;
echo partTwo($lines) . PHP_EOL;
