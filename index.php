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
            'institution'=>'0',
            'relationshipstatus'=>'single',
            'gender'=>'female',
            'profilepicid'=>'0',
            'followercount'=>'0',
            'friendcount'=>'0'
            );

$user = new User();
$test = $user->addUser($usr);

print '<pre>';
print_r($test);
print '</pre>';

//$arr = $user->addUser($usr);

//print $arr;

?>