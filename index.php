<?php
namespace Invigle;
require_once("bootstrap.php");

$graph = new Graph();
$arr = $graph->listNodes('19', 'LINKED_TO', '0', '10');

print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>