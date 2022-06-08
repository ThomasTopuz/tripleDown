<?php
include_once './models/Cell.php';
include_once './contracts/TripleDownComponent.php';


class Board implements TripleDownComponent
{
    const BOARD_LENGHT = 8;
    const EMPTY = " ";
    private $board;
    private $points = 0;
    private static $involvedCells = array();
    private static $visitedCells = array();

    /**
     * Constructor of Board instance, generates randomly a new board and saves it in session
     */
    function __construct()
    {
        $_board = array();

        for ($i = 0; $i < self::BOARD_LENGHT; $i++) {
            $row = array();
            for ($j = 0; $j < self::BOARD_LENGHT; $j++) {
                array_push($row, self::EMPTY);
            }
            array_push($_board, $row);
        }

        $intialFilledCells =  intval(((self::BOARD_LENGHT *  self::BOARD_LENGHT) / 100) * 20) + 1;
        $intialFilledCells = 10;
        $initialCells = array();

        for ($i = 0; $i < $intialFilledCells; $i++) {
            do {
                $materialToInsert = rand(1, 3);
                $col = rand(0, self::BOARD_LENGHT - 1);
                $row = rand(0, self::BOARD_LENGHT - 1);
            } while ($_board[$row][$col] != self::EMPTY);

            $_board[$row][$col] = $materialToInsert;
            array_push($initialCells, new Cell($row, $col, $materialToInsert));
        }

        $this->board = $_board;

        foreach ($initialCells as &$cell) {
            $this->insert($cell->value, $cell->row, $cell->col);
        }

        $this->points = 0;
        $this->saveState();
    }


    /**
     * This method is used to get the Board instance, wheather form memory or session
     */
    public static function getInstance()
    {
        if (isset($_SESSION["board"])) {
            return unserialize($_SESSION["board"]);
        } else {
            return new Board();
        }
    }

    /**
     * Method used to insert a new value in the board
     */
    public function insert($val, $row, $col)
    {
        $this->board[$row][$col] = $val;

        $currentValue = $val;

        // to while to enable combos
        do {
            self::$involvedCells = array();
            self::$visitedCells = array();
            $this->findInvolvedCells($currentValue, $row, $col, self::$involvedCells, self::$visitedCells);
            if (count(self::$involvedCells) >= 3) {
                $this->clearInvolvedCells(self::$involvedCells);
                $this->upgradeMaterialOnCell($row, $col, $currentValue);
                $this->points = pow($this->points + count(self::$involvedCells) * intval($currentValue), intval($currentValue));
                $currentValue += 1;
            }
        } while (count(self::$involvedCells) >= 3);

        $this->saveState();
    }

    /**
     *
     * Recursive Depth First Search implementation for finding all the cells involved in a move
     * @param $val value of the cell
     * @param $row row of the cell
     * @param $col column of the cell
     * @param $involvedCells array of cells involved in the move, this cells will be deleted
     * @return bool
     */
    private  function findInvolvedCells($val, $row, $col, &$involvedCells, &$visitedCells)
    {

        if ($row > self::BOARD_LENGHT - 1 || $row < 0 || $col > self::BOARD_LENGHT - 1 || $col < 0) {
            return false;
        }

        $currentCell = new Cell($row, $col, $val);
        if (self::isVisited($visitedCells, $currentCell)) {
            return false;
        }

        array_push($visitedCells, $currentCell);

        if ($this->board[$row][$col] == $val) {
            array_push($involvedCells, $currentCell);
        } else {
            return false;
        }

        // down 
        if (self::findInvolvedCells($val, $row + 1, $col, $involvedCells, $visitedCells)) {
            array_push($involvedCells, new Cell($row + 1, $col, $val));
            return true;
        }

        // up
        if (self::findInvolvedCells($val, $row - 1, $col, $involvedCells, $visitedCells)) {
            array_push($involvedCells, new Cell($row - 1, $col, $val));
            return true;
        }

        // right
        if (self::findInvolvedCells($val, $row, $col + 1, $involvedCells, $visitedCells)) {
            array_push($involvedCells, new Cell($row, $col + 1, $val));
            return true;
        }

        // left
        if (self::findInvolvedCells($val, $row, $col - 1, $involvedCells, $visitedCells)) {
            array_push($involvedCells, new Cell($row, $col - 1, $val));
            return true;
        }
        return false;
    }

    /**
     * Method to upgrade the level of material for a particular cell
     * @param $val value of the cell
     * @param $row row of the cell
     * @param $col column of the cell
     * @return void
     */
    private function upgradeMaterialOnCell($row, $col, $val)
    {
        $this->board[$row][$col] = intval($val) + 1;
    }

    /**
     * Method to clear all cells in the involvedCells array
     * @param $cells involved cells
     * @return void
     */
    private function clearInvolvedCells($cells)
    {
        foreach ($cells as &$cell) {
            $this->board[$cell->row][$cell->col] = self::EMPTY;
        }
    }

    /**
     * Method to check if a cell is in a cell array
     * @param $visitedCells array of cells
     * @param $cell cell
     * @return bool
     */
    private static function isVisited($visitedCells, $cell)
    {
        foreach ($visitedCells as &$visited) {
            if ($visited->isEqualTo($cell)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Method used to render information of the Board component
     * @return void
     */
    public function render()
    {
        echo '<table border style="border-collapse: collapse">';
        $isGameEnded = true;
        for ($i = 0; $i < self::BOARD_LENGHT; $i++) {
            echo '<tr>';
            for ($ii = 0; $ii < self::BOARD_LENGHT; $ii++) {
                if ($this->board[$i][$ii] == self::EMPTY) {
                    $href = '?action=insert&row=' . $i . '&col=' . $ii;
                    $isGameEnded = false;
                } else {
                    $href = "";
                }
                if ($href) {
                    echo "<td width='80px' height='80px'> <a style='display:block; text-align:center; vertical-align:middle; height: 100%; font-size:40px' href=$href>" . $this->board[$i][$ii] . '</a> </td>';
                } else {
                    echo "<td width='80px' height='80px'> <a style='display:block; text-align:center; vertical-align:middle; height: 100%; font-size:40px'>" . $this->board[$i][$ii] . '</a></td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
        echo "<p> Points: " . $this->points . "</p>";
        if ($isGameEnded) {
            echo "<p>GAME OVER :(</p>";
        }
    }

    /**
     * Method to save the state of the Board object in session
     * @return void
     */
    public function saveState()
    {
        $_SESSION["board"] = serialize($this);
    }
}
