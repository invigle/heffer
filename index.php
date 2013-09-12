<?php
namespace Invigle;
require_once("bootstrap.php");

$graph = new Graph();
$arr = $graph->editProperties($foo = array());

print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>