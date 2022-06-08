<?php

class Cell
{
    public $row;
    public $col;
    public $value;

    /**
     * Constructor of Cell instance
     * @param $row
     * @param $col
     * @param $val
     */
    function __construct($row, $col, $val)
    {
        $this->row = $row;
        $this->col = $col;
        $this->value = $val;
    }

    /**
     * This method is used to compare the Cell object
     * @param $cell cell object
     * @return bool
     */
    public function isEqualTo($cell)
    {
        return $this->row == $cell->row && $this->col == $cell->col;
    }
}
