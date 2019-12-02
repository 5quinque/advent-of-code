<?php


/*

--- Part 1 ---

Late at night, you sneak to the warehouse - who knows what kinds of paradoxes you could cause if you were discovered - and use your fancy wrist device to quickly scan every box and produce a list of the likely candidates (your puzzle input).

To make sure you didn't miss any, you scan the likely candidate boxes again, counting the number that have an ID containing exactly two of any letter and then separately counting those with exactly three of any letter. You can multiply those two counts together to get a rudimentary checksum and compare it to what your device predicts.

For example, if you see the following box IDs:

    abcdef contains no letters that appear exactly two or three times.
    bababc contains two a and three b, so it counts for both.
    abbcde contains two b, but no letter appears exactly three times.
    abcccd contains three c, but no letter appears exactly two times.
    aabcdd contains two a and two d, but it only counts once.
    abcdee contains two e.
    ababab contains three a and three b, but it only counts once.

Of these box IDs, four of them contain a letter which appears exactly twice, and three of them contain a letter which appears exactly three times. Multiplying these together produces a checksum of 4 * 3 = 12.

What is the checksum for your list of box IDs?

--- Part Two ---

Confident that your list of box IDs is complete, you're ready to find the boxes full of prototype fabric.

The boxes will have IDs which differ by exactly one character at the same position in both strings. For example, given the following box IDs:

abcde
fghij
klmno
pqrst
fguij
axcye
wvxyz

The IDs abcde and axcye are close, but they differ by two characters (the second and fourth). However, the IDs fghij and fguij differ by exactly one character, the third (h and u). Those must be the correct boxes.

What letters are common between the two correct box IDs? (In the example above, this is found by removing the differing character from either ID, producing fgij.)

*/

function readInput()
{
    $input = [];

    if ($file = fopen("input/day2.txt", "r")) {
        while ($line = fgets($file)) {
            $input[] = $line;
        }
        fclose($file);
    }

    return $input;
}

function getCharCounts()
{
	$boxIDs = readInput();
	$charCounts = [];

	foreach ($boxIDs as $id) {
		$chars = str_split(trim($id));
		$charCount = [];

		foreach($chars as $c) {
			if (!array_key_exists($c, $charCount)) {
				$charCount[$c] = 1;
			} else {
				$charCount[$c]++;
			}
		}
		$charCounts[] = $charCount;
	}
	return $charCounts;
}

// part 1
function find2and3counts()
{
	$charCounts = getCharCounts();
	$two = 0;
	$three = 0;

	foreach($charCounts as $c) {
		$foundTwo = false;
		$foundThree = false;

		foreach($c as $k => $i) {
			if ($i == 2 && !$foundTwo) {
				$foundTwo = true;
				$two++;
			}
			if ($i == 3 && !$foundThree) {
				$foundThree = true;
				$three++;
			}
		}
	}

	return $two * $three;
}

function part2()
{
	$ids = readInput();

	foreach($ids as $idx) {
		$idx = trim($idx);
		$charsx = str_split($idx);

		foreach($ids as $idy) {
			$idy = trim($idy);
			$charsy = str_split($idy);

			$differCount = 0;

			// compare `$charsx` and `$charsy`
			for($i = 0; $i < count($charsx); $i++) {
				if ($charsx[$i] != $charsy[$i]) {
					$differCount++;
				}
			}
			if ($differCount == 1) {
				break 2;
			}
		}
	}

	// find common letters
	$charsx = str_split($idx);
	$charsy = str_split($idy);
	$common = "";

	for($i = 0; $i < count($charsx); $i++) {
		if ($charsx[$i] == $charsy[$i]) {
			$common .= $charsx[$i];
		}
	}

	return $common;
}

echo "Part 1: " . find2and3counts() . "\n";
echo "Part 2: " . part2() . "\n";
