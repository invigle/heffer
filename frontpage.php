<?php

/**
 * @author Grant Millar
 * @copyright 2013
 */
 
use Invigle\FrontEndUIs\FrontPage,
    Invigle\Language\EN_GB,
    Invigle\User;

require_once("bootstrap.php");

$language = new EN_GB();

$frontPage = new FrontPage($language);

?>