<?php

/*

--- Part 1 ---

The Elves managed to locate the chimney-squeeze prototype fabric for Santa's suit (thanks to someone who helpfully wrote its box IDs on the wall of the warehouse in the middle of the night). Unfortunately, anomalies are still affecting them - nobody can even agree on how to cut the fabric.

The whole piece of fabric they're working on is a very large square - at least 1000 inches on each side.

Each Elf has made a claim about which area of fabric would be ideal for Santa's suit. All claims have an ID and consist of a single rectangle with edges parallel to the edges of the fabric. Each claim's rectangle is defined as follows:

    The number of inches between the left edge of the fabric and the left edge of the rectangle.
    The number of inches between the top edge of the fabric and the top edge of the rectangle.
    The width of the rectangle in inches.
    The height of the rectangle in inches.

A claim like #123 @ 3,2: 5x4 means that claim ID 123 specifies a rectangle 3 inches from the left edge, 2 inches from the top edge, 5 inches wide, and 4 inches tall. Visually, it claims the square inches of fabric represented by # (and ignores the square inches of fabric represented by .) in the diagram below:

...........
...........
...#####...
...#####...
...#####...
...#####...
...........
...........
...........

The problem is that many of the claims overlap, causing two or more claims to cover part of the same areas. For example, consider the following claims:

#1 @ 1,3: 4x4
#2 @ 3,1: 4x4
#3 @ 5,5: 2x2

Visually, these claim the following areas:

........
...2222.
...2222.
.11XX22.
.11XX22.
.111133.
.111133.
........

The four square inches marked with X are claimed by both 1 and 2. (Claim 3, while adjacent to the others, does not overlap either of them.)

If the Elves all proceed with their own plans, none of them will have enough fabric. How many square inches of fabric are within two or more claims?

*/


function readInput()
{
    $input = [];

    if ($file = fopen("input/day3.txt", "r")) {
        while ($line = fgets($file)) {
            $input[] = $line;
        }
        fclose($file);
    }

    return $input;
}

$input = readInput();

//$input = [
//"#1 @ 1,3: 4x4",
//"#2 @ 3,1: 4x4",
//"#3 @ 5,5: 2x2",
//];
//
$input = [
"#1 @ 1,1: 3x3",
"#2 @ 4,4: 3x3",
];

$coords = [];

foreach ($input as $l) {
    preg_match("/^#(\d+) @ (\d+),(\d+): (\d+)x(\d+)$/", $l, $matches);

    $id = $matches[1];

    $fromLeft = $matches[2];
    $fromTop = $matches[3];

    $width = $matches[4];
    $height = $matches[5];

                
    $rect = [$fromLeft,			    // x1
        $fromTop,			    	// y1
        $fromLeft + $width - 1,		// x2
        $fromTop + $height - 1];	// y2

    $coords[$id] = $rect;
}

$seenIds = [];
$totalIntersect = 0;

foreach ($coords as $id1 => $c1) {
    $seenIds[] = $id1;

    foreach ($coords as $id2 => $c2) {
        if ($id1 == $id2 || in_array($id2, $seenIds)) {
            continue;
        }
        //echo "ID1: $id1, ID2: $id2\n";
        //print_r($c1);
        //print_r($c2);


        // find intersection
        $x1 = max($c1[0], $c2[0]);
        $y1 = max($c1[1], $c2[1]);
        $x2 = min($c1[2], $c2[2]);
        $y2 = min($c1[3], $c2[3]);
        
        $intersect = [$x1, $y1, $x2, $y2];

        // get "square inches" of intersect
        $interWidth = $intersect[2] - $intersect[0] + 1;
        $interHeight = $intersect[3] - $intersect[1] + 1;

        $sqIn = $interWidth * $interHeight;

        $totalIntersect += $sqIn;

        //echo "SqIn: $sqIn\n";

        //print_r($intersect);

        //echo "c1\tX1 {$c1[0]} Y1 {$c1[1]} : X2 {$c1[2]} Y2 {$c1[3]}\n";
        //echo "c2\tX1 {$c2[0]} Y1 {$c2[1]} : X2 {$c2[2]} Y2 {$c2[3]}\n";
        //echo "in\tX1 $x1 Y1 $y1 : X2 $x2 Y2 $y2\n";
        //echo "width: $inter_width height: $inter_height\n";
    }
}

echo $totalIntersect;


/*

x = 1,1  3,3
y = 2,2  4,4

i = 2,2  3,3

0 1 2 3 4 5 6 7 8

1 x x x

2 x y y y

3 x y y y

4   y y y

5

6

7

8




 */
