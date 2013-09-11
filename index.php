<?php
require_once("includes/Graph.php");

$nodeParams['firstname'] = "Dilbert";
$nodeParams['lastname'] = "Smith";
$nodeParams['email'] = "dilbert@smith.com";
$nodeParams['password'] = "helloABC123";
$nodeParams['username'] = "dilbert101";

$graph = new Graph();
$test = $graph->editProperties($nodeParams);
?>