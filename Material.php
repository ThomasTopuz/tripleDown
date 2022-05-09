<?php

class Material
{
    private $current;
    private $preview;

    function __construct()
    {
        $this->current = self::getRandomMaterial();
        $this->preview = self::getRandomMaterial();
        $this->saveState();
    }

    private static function getRandomMaterial () {
        $random = rand(0, 100);
            $materialToInsert = 0;
            if ($random <= 45) {
                $materialToInsert = 1;
            } else if ($random<=70) {
                $materialToInsert = 2;
            } else if ($random<=85) {
                $materialToInsert = 3;
            } else if ($random<=95) {
                $materialToInsert = 4;
            } else if ($random<=100) {
                $materialToInsert = 5;
            }
        return $materialToInsert; 
    }
    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (isset($_SESSION["material"])) {
            return unserialize($_SESSION["material"]);
        } else {
            return new Material();
        }
    }

    public function getCurrent () {
        return $this->current;
    }

    public function render()
    {
        echo $this->current;
        echo "\n";
        echo $this->preview;
    }

    public function play () {
        $this->current = $this->preview;
        $this->preview = self::getRandomMaterial();
        $this->saveState();
    }

    private function saveState () {
        $_SESSION["material"] = serialize($this);
    }
}

?>