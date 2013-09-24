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
 
use Invigle\FrontEndUIs\FrontPage,
    Invigle\Language\EN_GB,
    Invigle\User;

require_once("bootstrap.php");

if(isset($_GET['logout'])){
    $user = new User();
    $user->userLogout();
}


$user = new User();
$rtn = $user->validateUsername('gavinhanson');

print '<pre>';
print_r($rtn);
print '</pre>';

?>