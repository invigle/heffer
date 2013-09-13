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

$language = new EN_GB();

$frontPage = new FrontPage($language);

if(isset($_SESSION['sid']) && isset($_SESSION['uid'])){
    //Session is set... But is it a real one?
    $user = new User();
    $val = $user->validateSession();
    
    print 'Sessions Detected, Checking<hr>';
    if($val){
        print 'Genuine';
    }else{
        print 'Hax0r';
    }
}

?>