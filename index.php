<?php
session_start();
include_once "Board.php";
include_once "Material.php";



$board = Board::getInstance();
$board->render();
$material = Material::getInstance();

if (isset($_GET["action"])) {
    
    $currentMaterial = $material->getCurrent();
    $material->play();
}

$material->render();

?>