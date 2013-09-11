<?php
require_once("includes/Graph.php");

$nodeParams['indexBy'] = "username";
$nodeParams['username'] = "dilbert101";
$nodeParams['firstname'] = "Dilbert";
$nodeParams['lastname'] = "Smith";
$nodeParams['email'] = "dilbert@smith.com";
$nodeParams['password'] = "helloABC123";


$graph = new Graph();
$test = $graph->addNode($nodeParams);
?>