<?php

use Invigle\Graph;

require_once("bootstrap.php");

$id1 = "6";
$id2 = "19";
$type = "FRIEND_OF";

$graph = new Graph();
$test = $graph->deleteConnection($id1, $id2, $type);
?>