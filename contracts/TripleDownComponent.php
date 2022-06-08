<?php

require_once "Injectable.php";
require_once "Renderable.php";
require_once "SessionSavable.php";


/**
 * This interface is composed of Injectable Renderable and SessionSavable interfaces, this provides a single and unified interface for all components
 */
interface TripleDownComponent extends Injectable, Renderable, SessionSavable
{
}
