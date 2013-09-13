<?php
namespace Invigle;
require_once("configuration.php");
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
$test = $user->validateUsernameFormatting($_GET['username']);

if($test){
    print 'Accepted';
}else{
    print 'Declined';
}

//$arr = $user->addUser($usr);

//print $arr;

?>