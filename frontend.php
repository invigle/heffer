<?php

/**
 * @author Grant Millar
 * @copyright 2013
 */

require_once("includes/xhp/php-lib/init.php");

$pageTitle = 'testing';

        $html = "<!DOCTYPE HTML>
                    <html>";
        $html .= "<head>
                    <title>{$_GET['name']}</title>
                </head>";
        echo $html;
?>