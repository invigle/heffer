<?php
namespace Invigle;
require_once("bootstrap.php");


$usr = array(
            'firstname'=>"Jenna",
            'lasname'=>"Jameson",
            'email'=>"hugetits@hotmail.com",
            'password'=>'LovesC0CK',
            'username'=>'jjameson',
            'sexualpref'=>'straight',
            'birthday'=>'1984-11-26',
            'institution'=>'',
            'relationshipstatus'=>'single',
            'gender'=>'female',
            'profilepicid'=>'',
            'followercount'=>'0',
            'friendcount'=>'0'
            );

$user = new User();
$arr = $user->addUser($usr);


?>