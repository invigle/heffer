<?php

/**
 * @author Grant Millar
 * @copyright 2013
 */
 
use Invigle\FrontEndUIs,
    Invigle\FrontEndUIs\Header,
    Invigle\User;

require_once("bootstrap.php");

$frontEnd = new FrontEndUIs();
$frontEnd->setPageTitle('this page');

echo $frontEnd->renderHeader();

?>