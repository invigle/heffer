<?php
namespace Invigle;
require_once("bootstrap.php");


$usr = array(
            'firstname'=>"Jenna",
            'lasname'=>"Jameson",
            'email'=>"hugetits@hotmail.com",
            'password'=>'LovesC0CK',
            'username'=>'jjameson',
            'sexualpref'=>'straight'
            );


$user = new User();
$arr = $user->addUser($usr);


?>