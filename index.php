<?php
function neo4japi($path, $type = 'GET', $postfields = array())
	{
		$url = "$this->_neo4jurlprefix://$this->_neo4jHref:$this->_neo4jPort/db/data/$path";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		if ($type === "POST")
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		} elseif ($type === "JSONPOST")
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postfields));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		} elseif ($type === "PUT")
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
		} elseif ($type === "DELETE")
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($data, true);
		return $json;
	}
    
    
$testCode = "CREATE n:User; RETURN n;";
$app = neo4japi('cypher', 'JSONPOST', $testCode);

print '<pre>';
print_r($app);
print '</pre>';
?>
