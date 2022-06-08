
<?php
include_once "Board.php";
include_once './contracts/TripleDownComponent.php';


class Bomb implements TripleDownComponent
{
    private $moves = 0;
    private $actualMoves = 0;
    private $bombNotAvailableError = false;

    private static $MOVES_FOR_BOMB = 50;

    /**
     * constructor of the Bomb object, saves in session default values
     */
    function __construct()
    {
        $this->saveState();
    }

    /**
     * This method is used to get the Bomb instance, wheather form memory or session
     */
    public static function getInstance()
    {
        if (isset($_SESSION["moves"])) {
            return unserialize($_SESSION["moves"]);
        } else {
            return new Bomb();
        }
    }

    /**
     * Method used to render information of the Bomb component
     * @return void
     */
    public function render()
    {
        if ($this->bombNotAvailableError) {
            echo "<p>Error, you don't have bombs available</p>";
        }
        echo "<p>Moves: " . $this->actualMoves . "</p>";
        echo "<p>Available Bombs: " . intval($this->moves / self::$MOVES_FOR_BOMB) . "</p>";
        echo "<hr>";
        echo "Launch a bomb:";
        echo '<form action="" method="GET">
                <input type="hidden" name="action" value="bomb" />
                <label>Column:</label>
                <input type="number" name=col  min="1" max="8">
                <br>
                <label>Row:</label>
                <input type="number" name=row " min="1" max="8">
                <br>
                <input type="submit" value="Use a bomb">
            </form>';
        echo "<hr>";
    }

    /**
     * Method to increment the moves count
     * @return void
     */
    public function incrementMove()
    {
        $this->actualMoves += 1;
        $this->moves += 1;
        $this->saveState();
    }

    /**
     * Method to use a bomb
     * @param $board the board instance
     * @param $row row of the cell
     * @param $col column of the cell
     * @return void
     */
    public function useBomb($board, $row, $col)
    {
        if ($this->canUseBomb()) {
            $this->moves -= self::$MOVES_FOR_BOMB;
            $this->saveState();
            $board->clearCell($row, $col);
        } else {
            $this->bombNotAvailableError = true;
        }
    }

    /**
     * Method that returns weather there are bombs available
     * @return bool
     */
    public function canUseBomb()
    {
        return intval($this->moves / self::$MOVES_FOR_BOMB) > 0;
    }

    /**
     * Method to save the state of the Bomb object in session
     * @return void
     */
    public function saveState()
    {
        $_SESSION["moves"] = serialize($this);
    }
}

$bomb = Bomb::getInstance();

if (isset($_GET["action"])) {
    if ($_GET["action"] == "bomb") {
        $bomb->useBomb(Board::getInstance(), intval($_GET["row"]) - 1, intval($_GET["col"]) - 1);
    } else {
        $bomb->incrementMove();
    }
}

$bomb->render();
