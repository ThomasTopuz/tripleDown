<?php
interface Injectable
{
    /**
     * Method to return the instance of a singleton
     * @return mixed
     */
    static function getInstance();
}
