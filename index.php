<?php
namespace Invigle;
require_once("bootstrap.php");

$user = new User();
$arr = $user->validateUsername($_GET['username']);

print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>