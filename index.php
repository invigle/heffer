<?php
namespace Invigle;
require_once("bootstrap.php");

$user = new User();
$arr = $user->validateEmailAddress($_GET['email']);

if(!$arr){
    print 'Username Taken';
}else{
    print 'Username Available';
}


?>