<?php

function apiCall($input) {
	$url = "http://boss.invigle.com:8001/db/data/$input[path]";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$data = curl_exec($ch);
	curl_close($ch);

    $json = json_decode($data, true);
    return $json['data'];
}


$api['path'] = "node/6";

$arr = apiCall($api);
print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>