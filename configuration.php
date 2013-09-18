<?php
/**
 * Define constant configuration variables for use globally within the system.
 */


//Add configuration values here.
$configuration = array(
                    "securitySalt"=>"kjwbgGRWwglrnwvbw242gf25g35thgwrWFEWDG25t2", //Changing this value will stop ALL passwords from working.
                    "dateFormat"=>"d/m/Y @ H:i", //Format added to date() see date.php.net for options.
                      );
                      

//Define all variables in uppercase as constants, Usage: CONF_CONFKEY                      
foreach($configuration as $key => $value){
    $var = strtoupper($key);
    define("CONF_$var", $value);
}
?>