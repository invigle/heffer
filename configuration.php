<?php
/**
 * 
 * Define constant configuration variables for use globally within the system.
 * 
 */


//Add configuration values here.
$configuration = array(
                    "securitySalt"=>"kjwbgGRWwglrnwvbw242gf25g35thgwrWFEWDG25t2",
                      );
                      

//Define all variables in uppercase as constants, Usage: CONF_CONFKEY                      
foreach($configuration as $key => $value){
    $var = strtoupper($key);
    define("CONF_$var", $value);
}
?>