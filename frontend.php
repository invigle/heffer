<?php

/**
 * @author Grant Millar
 * @copyright 2013
 */
 
use Invigle\FrontEndUIs,
    Invigle\FrontEndUIs\Header,
    Invigle\User;

require_once("bootstrap.php");

$frontEndUI = new FrontEndUIs();
$frontEndUI->setPageTitle('this page');

echo $frontEndUI->_header->renderHeader();

?>