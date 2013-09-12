<?php
namespace Invigle;
require_once("bootstrap.php");

$foo['indexBy'] = "username";
$foo['indexValue'] = "dilbert101";

$graph = new Graph();
$arr = $graph->editProperties($foo);

print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>