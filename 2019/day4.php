<?php

/*

--- Part 1 ---

You arrive at the Venus fuel depot only to discover it's protected by a password. The Elves had written the password on a sticky note, but someone threw it out.

However, they do remember a few key facts about the password:

    It is a six-digit number.
    The value is within the range given in your puzzle input.
    Two adjacent digits are the same (like 22 in 122345).
    Going from left to right, the digits never decrease; they only ever increase or stay the same (like 111123 or 135679).

Other than the range rule, the following are true:

    111111 meets these criteria (double 11, never decreases).
    223450 does not meet these criteria (decreasing pair of digits 50).
    123789 does not meet these criteria (no double).

How many different passwords within the range given in your puzzle input meet these criteria?

Your puzzle input is 156218-652527.

*/

function part1($input)
{
    preg_match('/^(\d+)-(\d+)$/', $input, $range);

    $count = 0;

    for ($i = $range[1]; $i <= $range[2]; $i++) {
        $prevDigit = 0;
        $double = false;
        $string = (string) $i;

        foreach (str_split($string) as $s) {
            if ((int) $s < $prevDigit) {
                continue 2;
            }
            if ((int) $s == $prevDigit) {
                $double = true;
            }

            $prevDigit = (int) $s;
        }

        if ($double) {
            $count++;
        }
    }
    return $count;
}

function part2($input)
{
    preg_match('/^(\d+)-(\d+)$/', $input, $range);

    $count = 0;

    for ($i = $range[1]; $i <= $range[2]; $i++) {
        $prevDigit = 0;
        $double = false;
        $string = (string) $i;
        // Remove triples or more
        $noTrips = preg_replace('/(\d)\1\1+/', '-', $string);        

        // skip things like 555666
        if (preg_match('/^-+$/', $noTrips)) {
            continue;
        }

        foreach (str_split($string) as $s) {
            if ((int) $s < $prevDigit) {
                continue 2;
            }
            $prevDigit = (int) $s;
        }

        $prevDigit = 0;
        foreach (str_split($noTrips) as $s) {
            if ($s == "-") {
                continue;
            }
            if ((int) $s == $prevDigit) {
                $double = true;
            }

            $prevDigit = (int) $s;
        }

        if ($double) {
            $count++;
        }
    }
    return $count;
}

$input = "156218-652527";
echo "Part 1 " . part1($input) . "\n";
echo "Part 2 " . part2($input) . "\n";
