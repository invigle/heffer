<?php
require_once("includes/Graph.php");

$nodeParams['indexBy'] = "username";
$nodeParams['username'] = "seamore101";

$nodeParams['firstname'] = "Seamore";
$nodeParams['lastname'] = "Butts";
$nodeParams['email'] = "seamore@butts.com";
$nodeParams['password'] = "l337P455";


$graph = new Graph();
$test = $graph->editProperties($nodeParams);
?>