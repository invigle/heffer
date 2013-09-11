<?php
require("phar:///invigle/heffer/includes/neo4jphp.phar");

// Connecting to a different port or host
$client = new Everyman\Neo4j\Client('localhost', 8001);

print_r($client->getServerInfo());
?>