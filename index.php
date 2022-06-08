<?php
session_start();

include_once "./components/Bomb.php";
include_once "./components/Board.php";
include_once "./components/Material.php";
include_once "./models/Cell.php";


# formula per il punteggio: (nr coinvolti + livello) eleveato livello
$board = Board::getInstance();
$material = Material::getInstance();

$cell = new Cell("5", 2, 1);
if (isset($_GET["action"])) {
    if ($_GET["action"] == "insert") {
        $currentMaterial = $material->getCurrent();
        $material->insert();
        $board->insert($currentMaterial, $_GET["row"], $_GET["col"]);
    }
}

$material->render();
$board->render();
