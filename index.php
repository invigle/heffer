<?php
require_once("includes/Graph.php");

$nodeParams['firstname'] = "Dilbert";
$nodeParams['lastname'] = "Jones";
$nodeParams['email'] = "dilbert@jonesco.com";
$nodeParams['password'] = "hello123";

$graph = new Graph();
$test = $graph->addNode($nodeParams);

print $test;
?>