<?php
require_once("includes/Graph.php");

$id1 = "6";
$id2 = "19";
$type = "FRIEND_OF";

$graph = new Graph();
$test = $graph->addConnection($id1, $id2, $type);
?>