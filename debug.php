<?php require_once("basics.php");

const DEFAULT_GRIDSIZE_X = 10;
const DEFAULT_GRIDSIZE_Y = 20;

$g = new Conway_Grid(DEFAULT_GRIDSIZE_X, DEFAULT_GRIDSIZE_Y);
$g->randomizeGrid();

echo "<table><tr><td>";

$g->debugOutput();

for($i = 0; $i < 10; $i++) {
    echo "</td><td>&nbsp;</td><td>";
    $g->stepForward();
    $g->debugOutput();
}

echo "</td></tr></table>";
