<?php
include_once './contracts/TripleDownComponent.php';


class Material implements TripleDownComponent
{
    private $current;
    private $preview;


    /**
     *
     * Constructor of Material instance,
     * it generates two random numbers that rapresent the next material
     * to insert and saves the instance in session
     */
    function __construct()
    {
        $this->current = self::getRandomMaterial();
        $this->preview = self::getRandomMaterial();
        $this->saveState();
    }

    /**
     * Method to get a random material, uses particular distribution for different material codes
     * 1 => 45%
     * 2=> 25%
     * 3=> 15%
     * 4=> 10%
     * 5=> 5%
     *
     * @return int material code
     */
    private static function getRandomMaterial()
    {
        $random = rand(0, 100);
        $materialToInsert = 0;
        if ($random <= 45) {
            $materialToInsert = 1;
        } else if ($random <= 70) {
            $materialToInsert = 2;
        } else if ($random <= 85) {
            $materialToInsert = 3;
        } else if ($random <= 95) {
            $materialToInsert = 4;
        } else if ($random <= 100) {
            $materialToInsert = 5;
        }

        if ($random <= 45) {
            $materialToInsert = 1;
        } else {
            $materialToInsert = 2;
        }
        return $materialToInsert;
    }

    /**
     * This method is used to get the Board instance, wheather form memory or session
     */
    public static function getInstance()
    {
        if (isset($_SESSION["material"])) {
            return unserialize($_SESSION["material"]);
        } else {
            return new Material();
        }
    }

    /**
     * Get the current material code to insert
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Method used to render information of the Material component
     * @return void
     */
    public function render()
    {
        echo "Next material:";
        echo $this->current;
        echo "  -   Preview:";
        echo $this->preview;
    }

    /**
     * method used to handle the insert action, generates a new material and the preview becomes the current
     * @return void
     */
    public function insert()
    {
        $this->current = $this->preview;
        $this->preview = self::getRandomMaterial();
        $this->saveState();
    }

    /**
     * Method to save the state of the Bomb object in session
     * @return void
     */
    public function saveState()
    {
        $_SESSION["material"] = serialize($this);
    }
}
