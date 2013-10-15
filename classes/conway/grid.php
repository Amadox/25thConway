<?php

/**
 * Class Conway_Grid
 */
class Conway_Grid {

    private $_sizeX;
    private $_sizeY;
    private $_grid;

    /**
     * Construct a Grid for Conway's Game of Life
     * @param int $sizeX        Horizontal Size of the Grid
     * @param int|null $sizeY   Vertical Size of the Grid (on NULL, Vertical = Horizontal Size)
     */
    public function __construct($sizeX, $sizeY = NULL) {
        $this->_sizeX = max(3, $sizeX);

        if($sizeY !== NULL)
            $this->_sizeY = max(3, $sizeY);
        else
            $this->_sizeY = max(3, $sizeX);

        $this->resetGrid();
    }

    /**
     * Reset Grid to be totally empty
     * @return $this
     */
    public function resetGrid() {
        $this->_grid = array();
        for($x = 0; $x < $this->_sizeX; $x++)
            for($y = 0; $y < $this->_sizeY; $y++)
                $this->setState($x, $y, 0);
        return $this;
    }

    /**
     * Randomize Grid Contents
     * @return $this
     */
    public function randomizeGrid() {
        for($x = 0; $x < $this->_sizeX; $x++)
            for($y = 0; $y < $this->_sizeY; $y++)
                $this->setState($x, $y, mt_rand(0,1));
        return $this;
    }

    /**
     * Set the state of a specific field
     * @param int $x (0-based)
     * @param int $y (0-based)
     * @param bool $state
     * @return $this
     * @throws Conway_Exception
     */
    public function setState($x, $y, $state) {
        if(($x < 0) || ($x >= $this->_sizeX)) throw new Conway_Exception("Illegal X Value Input");
        if(($y < 0) || ($y >= $this->_sizeY)) throw new Conway_Exception("Illegal Y Value Input");
        $this->_grid[$y][$x] = $state;
        return $this;
    }

    /**
     * get the state of a specific field
     * @param int $x (0-based)
     * @param int $y (0-based)
     * @return bool
     * @throws Conway_Exception
     */
    public function getState($x, $y) {
        if(($x < 0) || ($x >= $this->_sizeX)) throw new Conway_Exception("Illegal X Value Input");
        if(($y < 0) || ($y >= $this->_sizeY)) throw new Conway_Exception("Illegal Y Value Input");
        return $this->getStateInt($x, $y);
    }

    /**
     * @param $grid
     */
    public function setGrid($grid) {
        $this->_grid = array();
        for($x = 0; $x < $this->_sizeX; $x++)
            for($y = 0; $y < $this->_sizeY; $y++)
                $this->setState($x, $y, $grid[$y][$x]);
        //$this->_grid = $grid;
    }

    /**
     * get the state of a specific field
     * @param int $x (0-based)
     * @param int $y (0-based)
     * @return int
     */
    protected function getStateInt($x, $y) {
        if(($x < 0) || ($x >= $this->_sizeX)) return 0;
        if(($y < 0) || ($y >= $this->_sizeY)) return 0;
        return $this->_grid[$y][$x]?1:0;
    }

    /**
     * Calculate one or more Steps
     * @param int $steps
     * @return $this
     */
    public function stepForward($steps = 1) {
        for($i=0;$i<$steps;$i++) {
            $newgrid = array();

            for($x = 0; $x < $this->_sizeX; $x++) {
                for($y = 0; $y < $this->_sizeY; $y++) {

                    $alive = $this->getState($x, $y);
                    $cnt = $this->countNeighbours($x, $y);

                    $newgrid[$y][$x] = $alive;
                    if($alive && ($cnt < 2))
                        $newgrid[$y][$x] = 0;
                    elseif($alive && ($cnt > 3))
                        $newgrid[$y][$x] = 0;
                    elseif(!$alive && ($cnt == 3))
                        $newgrid[$y][$x] = 1;
                }
            }

            $this->_grid = $newgrid;
        }
        return $this;
    }

    /**
     * @param int $x (0-based)
     * @param int $y (0-based)
     * @return int
     * @throws Conway_Exception
     */
    public function countNeighbours($x, $y) {
        if(($x < 0) || ($x >= $this->_sizeX)) throw new Conway_Exception("Illegal X Value Input");
        if(($y < 0) || ($y >= $this->_sizeY)) throw new Conway_Exception("Illegal Y Value Input");

        $cnt = $this->getStateInt($x-1, $y-1);
        $cnt += $this->getStateInt($x-1, $y);
        $cnt += $this->getStateInt($x-1, $y+1);
        $cnt += $this->getStateInt($x, $y-1);
        $cnt += $this->getStateInt($x, $y+1);
        $cnt += $this->getStateInt($x+1, $y-1);
        $cnt += $this->getStateInt($x+1, $y);
        $cnt += $this->getStateInt($x+1, $y+1);
        return $cnt;
    }

    /**
     * @return string
     */
    public function json_output() {
        $output = array(
            "sizeX" => $this->_sizeX,
            "sizeY" => $this->_sizeY,
            "grid" => $this->_grid
        );
        return json_encode($output);
    }

    /**
     * @param string $json (as exported by json_output method)
     * @return Conway_Grid
     */
    public static function createByJSON($json) {
        if(!is_array($json))
            $json = json_decode($json);
        $obj = new self($json["sizeX"], $json["sizeY"]);
        $obj->setGrid($json["grid"]);
        return $obj;
    }

    /**
     * basic HTML/Ascii output for Debugging
     */
    public function debugOutput() {
        echo "<pre>";
        for($x = 0; $x < $this->_sizeX; $x++) {
            for($y = 0; $y < $this->_sizeY; $y++) {
                echo $this->getState($x, $y)?"X":".";
            }
            echo "<br/>";
        }
        echo "</pre>";
    }
}