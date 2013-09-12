<?php
namespace Invigle;
require_once("bootstrap.php");

$user = new User();
$arr = $user->validateUsername($_GET['username']);

print_r($arr);

if($arr){
    print 'Username Taken';
}else{
    print 'Username Available';
}


?>