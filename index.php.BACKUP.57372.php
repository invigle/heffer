<?php
<<<<<<< HEAD
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
 
use Invigle\FrontEndUIs\FrontPage,
    Invigle\Language\EN_GB,
    Invigle\User;

require_once("bootstrap.php");

$user = new User();
$api = $user->followUser($_SESSION['uid'], $_GET['user']);
//print_r($_SESSION);

$language = new EN_GB();
?>
=======
require_once("rdb/rdb.php");
echo r\systemInfo();
?>
>>>>>>> origin/master
