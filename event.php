<?php
session_start();
/**
 * frontpage - The Invigle home page
 * 
 * @package   
 * @author heffer
 * @copyright Gavin Hanson
 * @version 2013
 * @access public
 */
require_once("configuration.php");
 
use    Invigle\FrontEndUIs\Events,
       Invigle\Language\EN_GB,
       Invigle\User;

require_once("bootstrap.php");


$language = new EN_GB();
$events = new Events($language);

?>