<?php

/*

--- Part 1 ---

The gravity assist was successful, and you're well on your way to the Venus refuelling station. During the rush back on Earth, the fuel management system wasn't completely installed, so that's next on the priority list.

Opening the front panel reveals a jumble of wires. Specifically, two wires are connected to a central port and extend outward on a grid. You trace the path each wire takes as it leaves the central port, one wire per line of text (your puzzle input).

The wires twist and turn, but the two wires occasionally cross paths. To fix the circuit, you need to find the intersection point closest to the central port. Because the wires are on a grid, use the Manhattan distance for this measurement. While the wires do technically cross right at the central port where they both start, this point does not count, nor does a wire count as crossing with itself.

For example, if the first wire's path is R8,U5,L5,D3, then starting from the central port (o), it goes right 8, up 5, left 5, and finally down 3:

...........
...........
...........
....+----+.
....|....|.
....|....|.
....|....|.
.........|.
.o-------+.
...........

Then, if the second wire's path is U7,R6,D4,L4, it goes up 7, right 6, down 4, and left 4:

...........
.+-----+...
.|.....|...
.|..+--X-+.
.|..|..|.|.
.|.-X--+.|.
.|..|....|.
.|.......|.
.o-------+.
...........

These wires cross at two locations (marked X), but the lower-left one is closer to the central port: its distance is 3 + 3 = 6.

Here are a few more examples:

    R75,D30,R83,U83,L12,D49,R71,U7,L72
    U62,R66,U55,R34,D71,R55,D58,R83 = distance 159
    R98,U47,R26,D63,R33,U87,L62,D20,R33,U53,R51
    U98,R91,D20,R16,D67,R40,U7,R15,U6,R7 = distance 135

What is the Manhattan distance from the central port to the closest intersection?

--- Part Two ---

It turns out that this circuit is very timing-sensitive; you actually need to minimize the signal delay.

To do this, calculate the number of steps each wire takes to reach each intersection; choose the intersection where the sum of both wires' steps is lowest. If a wire visits a position on the grid multiple times, use the steps value from the first time it visits that position when calculating the total value of a specific intersection.

The number of steps a wire takes is the total number of grid squares the wire has entered to get to that location, including the intersection being considered. Again consider the example from above:

...........
.+-----+...
.|.....|...
.|..+--X-+.
.|..|..|.|.
.|.-X--+.|.
.|..|....|.
.|.......|.
.o-------+.
...........

In the above example, the intersection closest to the central port is reached after 8+5+5+2 = 20 steps by the first wire and 7+6+4+3 = 20 steps by the second wire for a total of 20+20 = 40 steps.

However, the top-right intersection is better: the first wire takes only 8+5+2 = 15 and the second wire takes only 7+6+2 = 15, a total of 15+15 = 30 steps.

Here are the best steps for the extra examples from above:

    R75,D30,R83,U83,L12,D49,R71,U7,L72
    U62,R66,U55,R34,D71,R55,D58,R83 = 610 steps
    R98,U47,R26,D63,R33,U87,L62,D20,R33,U53,R51
    U98,R91,D20,R16,D67,R40,U7,R15,U6,R7 = 410 steps

What is the fewest combined steps the wires must take to reach an intersection?

*/

function readInput()
{
    $wires = [];

    if ($file = fopen("input/day3.txt", "r")) {
        while ($line = fgets($file)) {
            $wires[] = explode(",", $line);
        }
        fclose($file);
    }

    return $wires;
}

function plotGrid($wires)
{
    $grid = [];
    $wireLength = [];
    $wireID = 0;

    foreach ($wires as $w) {
        $posX = 0;
        $posY = 0;
        $wl = 0;
        $wireLength[$wireID][$posX][$posY] = $wl;

        foreach ($w as $ins) {
            $direction = str_split($ins)[0];
            $length = trim(substr($ins, 1));

            switch ($direction) {
                case 'U':
                    for ($i = $posY; $posY < $i + $length;) {
                        $grid[$posX][++$posY][] = $wireID;
                        $wl++;
                        //echo "U - id $wireID X $posX Y $posY L $wl\n";
                        $wireLength[$wireID][$posX][$posY] = $wl;
                    }
                    break;
                Case 'D':
                    for ($i = $posY; $posY > $i - $length;) {
                        $grid[$posX][--$posY][] = $wireID;
                        $wl++;
                        $wireLength[$wireID][$posX][$posY] = $wl;
                    }
                    break;
                Case 'L':
                    for ($i = $posX; $posX > $i - $length;) {
                        $grid[--$posX][$posY][] = $wireID;
                        $wl++;
                        $wireLength[$wireID][$posX][$posY] = $wl;
                    }
                    break;
                Case 'R':
                    for ($i = $posX; $posX < $i + $length;) {
                        $grid[++$posX][$posY][] = $wireID;
                        $wl++;
                        //echo "R - id $wireID X $posX Y $posY L $wl\n";
                        $wireLength[$wireID][$posX][$posY] = $wl;
                    }
                    break;
            }
        }

        $wireID++;
    }

    //print_r($wireLength);

    return [$grid, $wireLength];
}


function part1()
{
    $wires = readInput();

    $grid = plotGrid($wires)[0];

    $mdClosestToPort = null;

    foreach ($grid as $X => $arrY) {
        foreach ($arrY as $Y => $wireIDs) {
            // Find intersection
            if (count($wireIDs) > 1) {

                // Calculate Manhattan distance
                if ($X < 0) {
                    $X = -$X;
                }
                if ($Y < 0) {
                    $Y = -$Y;
                }
                $i = $X + $Y;
                
                if ($i < $mdClosestToPort && $i != 0 || $mdClosestToPort == null) {
                    $mdClosestToPort = $i;
                }
            }
        }
    }

    return $mdClosestToPort;
}


function part2()
{
    $wires = readInput();
    //$wires = [["U7", "R4"], ["R2", "U3", "L4"]];
    //$wires = [["R2", "U3"]];
    //$wires = [["U3", "R3", "D3", "L3"]];


    /*
    
    |
    |
    |
    | 
  --+-|
    | |
    | |
     --

    */

    $grid = plotGrid($wires);
    $wireLength = $grid[1];

    //print_r($wireLength);

    $lClosestToPort = null;

    foreach ($grid[0] as $X => $arrY) {
        foreach ($arrY as $Y => $wireIDs) {
            // Find intersection
            if (count($wireIDs) > 1) {
                // Calculate wirelength from this point.
                $lengthFromO1 = $wireLength[$wireIDs[0]][$X][$Y];
                $lengthFromO2 = $wireLength[$wireIDs[1]][$X][$Y];
                $totalLength = $lengthFromO1 + $lengthFromO2;

                if ($totalLength < $lClosestToPort || $lClosestToPort == null) {
                    echo "WireID 0: {$wireIDs[0]} WireID 1: {$wireIDs[1]}\n";
                    echo "Line1 Length: $lengthFromO1 Line2 Length: $lengthFromO2 Total $totalLength\n";
                    echo "X: $X Y: $Y\n";
                    $lClosestToPort = $totalLength;
                }
            }
        }
    }
    
    return $lClosestToPort;
}

echo "Part 1 " . part1() . "\n";
echo "Part 2 " . part2() . "\n";

