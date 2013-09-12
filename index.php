<?php
namespace Invigle;
require_once("bootstrap.php");

$graph = new Graph();
$arr = $graph->addConnection('19', '31', 'LINKED_TO');

print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>