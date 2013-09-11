<?php
require_once("includes/Graph.php");

$nodeParams['indexBy'] = "username";
$nodeParams['username'] = "dilbertgdrt10dghgfg1";
$nodeParams['firstname'] = "Dilbedfsdrt";
$nodeParams['lastname'] = "Smfgdfgith";
$nodeParams['email'] = "dilbert@smitdfgh.com";
$nodeParams['password'] = "pa55w0rd";


$graph = new Graph();
$test = $graph->editProperties($nodeParams);
?>