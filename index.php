<?php
require_once("includes/Graph.php");

$nodeParams['indexBy'] = "username";
$nodeParams['username'] = "dilbertgdrt10dghgfg1";
$nodeParams['firstname'] = "Dilbedfsdrt";
$nodeParams['lastname'] = "Smfgdfgith";
$nodeParams['email'] = "dilbert@smitdfgh.com";
$nodeParams['password'] = "helloABC1gdfgd23";


$graph = new Graph();
$test = $graph->addNode($nodeParams);
?>