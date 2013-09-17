<?php
session_start();
/**
 * User - View a user
 * 
 * @package   
 * @author heffer
 * @copyright Gavin Hanson
 * @version 2013
 * @access public
 */
require_once("configuration.php");
 
use    Invigle\FrontEndUIs\userProfile,
       Invigle\Language\EN_GB,
       Invigle\User;

require_once("bootstrap.php");


$language = new EN_GB();
$userProfile = new userProfile($language);

?>