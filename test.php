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
    Invigle\User,
    Invigle\Graph;

require_once("bootstrap.php");

if(isset($_GET['logout'])){
    $user = new User();
    $user->userLogout();
}


//$user = new User();
//$rtn = $user->updateUserTimeline('1', '9999');

//$graphModule = new Graph();
//$rtn = $graphModule->deleteConnection('1', '5', 'timeline');

print '<pre>';
print_r($rtn);
print '</pre>';
echo test;

?>