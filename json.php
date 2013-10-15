<?php require_once("basics.php");

if(isset($_POST['jsondata'])) {
    $json = $_POST['jsondata'];
    $steps = max(1, $_POST['steps']);
    $grid = Conway_Grid::createByJSON($json);
    //$grid->debugOutput();
    $grid->stepForward($steps);
    //$grid->debugOutput();
    echo $grid->json_output();


} elseif(isset($_POST['random'])) {
    $grid = new Conway_Grid((int)$_POST['sizeX'],(int)$_POST['sizeY']);
    $grid->randomizeGrid();
    echo $grid->json_output();

}elseif(isset($_POST['clear'])) {
    $grid = new Conway_Grid((int)$_POST['sizeX'],(int)$_POST['sizeY']);
    echo $grid->json_output();
}
