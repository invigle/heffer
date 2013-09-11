<?php
require_once("includes/Graph.php");

$nodeParams['indexBy'] = "username";
$nodeParams['username'] = "seamore101";

$nodeParams['firstname'] = "Seamore";
$nodeParams['lastname'] = "Butts";
$nodeParams['email'] = "seamore@butts.com";
$nodeParams['password'] = "pa55w0rd";


$graph = new Graph();
$test = $graph->addNode($nodeParams);
?>