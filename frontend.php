<?php

/**
 * @author Grant Millar
 * @copyright 2013
 */
 
use Invigle\FrontEndUIs,
    Invigle\User;

require_once("bootstrap.php");

$frontEnd = new FrontEndUIs();

echo $frontEnd->renderHeader('test2');

?>