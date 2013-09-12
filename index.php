<?php
namespace Invigle;
require_once("bootstrap.php");

$foo['indexBy'] = "username";
$foo['indexValue'] = "seamore101";

$foo['newProperty'] = "panda";
$foo['firstname'] = "Eatmore";

$graph = new Graph();
$arr = $graph->editProperties($foo);

print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>