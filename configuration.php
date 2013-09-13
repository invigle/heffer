<?php
$configuration = array(
                    "securitySalt"=>"kjwbgGRWwglrnwvbw242gf25g35thgwrWFEWDG25t2",
                      );
                      
foreach($configuration as $key => $value){
    $var = strtoupper($key);
    define("CONF_$var", $value);
}
?>