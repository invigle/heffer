<?php
namespace Invigle;
require_once("bootstrap.php");

$user = new User();
$arr = $user->validateUsername($_GET['username']);

if($arr){
    print 'Username Taken';
}else{
    print 'Username Available';
}


?>