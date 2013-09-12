<?php

function apiCall($api, $type, $postfields = array()) {
	$url = "http://boss.invigle.com:8001/db/data/$api[path]";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        if($type === "POST"){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);    
        }
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);

    $json = json_decode($data, true);
    return $json['data'];
}


$api['path'] = "node/6";


$arr = apiCall($api, 'GET');
print 'WORK:<hr><pre>';
print_r($arr);
print '</pre>';


?>