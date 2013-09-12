<?php
namespace Invigle;
include("bootstrap.php");

$graph = new Graph();

$arr = $graph->neo4japi("/nodes/26");

print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>