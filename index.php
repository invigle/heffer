<?php
namespace Invigle;
require_once("bootstrap.php");

$graph = new Graph();
$arr = $graph->neo4japi("/node/6");

print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>