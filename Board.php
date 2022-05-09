<?php

class Board
{
    private $board;

    function __construct()
    {
        $_board = array();

        for ($i = 0; $i < 8; $i++) {
            $row = array();
            for ($j = 0; $j < 8; $j++) {
                array_push($row, " ");

            }
            array_push($_board, $row);
        }
        for ($i = 0; $i < 13; $i++) {
            $materialToInsert = rand(1, 3);
            $col= rand(0, 8);
            $row= rand(0, 8);
            $_board[$row][$col] = $materialToInsert;

        }

        $this->board = $_board;
        $this->saveState();
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (isset($_SESSION["board"])) {
            return unserialize($_SESSION["board"]);
        } else {
            return new Board();
        }
    }


    public function insert($val, $row, $col) {
        $this->board[$row][$col] = $val;
        $this->saveState();
    }

    public function render()
    {
        echo '<table border style="border-collapse: collapse">';
        for ($i = 0; $i < 8; $i++) {
            echo '<tr>';
            for ($ii = 0; $ii < 8; $ii++) {
                $href = '?action=insert&row='.$i.'&col='.$ii;
                echo "<td width='100px' height='100px'> <a style='display:block; text-align:center; vertical-align:middle; height: 100%; font-size:40px' href=$href>". $this->board[$i][$ii].'</a> </td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    private function saveState () {
        $_SESSION["board"] = serialize($this);
    }
}

?>