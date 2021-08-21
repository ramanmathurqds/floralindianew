<?php

/*
* PayU configuration 
*/

// PayU configuration
// $protocol = (isset($_SERVER['HTTP_X_FORWARDED_PROTOCOL']) && !empty($_SERVER['HTTP_X_FORWARDED_PROTOCOL'])) ? 'https://' : 'http://';
$protocol = 'https://';
// define('PROTOCOL', $protocol);

define('HOST', $protocol . $_SERVER['HTTP_HOST']);

if (stripos(HOST,"localhost")) {

    error_reporting(0);
	// error_reporting(E_ALL);
    // ini_set('display_errors', 'on');

    define('HOST_URL', HOST.'/floralindia');
    define('API_URL', 'http://localhost/floralindia/admin-panel/API/floralapi.php?case=');

} elseif (stripos(HOST,"smoke1.floralindia.com"))  {

    define('HOST_URL', HOST);
    define('API_URL', HOST.'/admin-panel/API/floralapi.php?case=');

} elseif (stripos(HOST,"smoke2.floralindia.com"))  {

    define('HOST_URL', HOST);
    define('API_URL', HOST.'/admin-panel/API/floralapi.php?case=');

} else {

    define('HOST_URL', HOST);
    define('API_URL', HOST.'/admin-panel/API/floralapi.php?case=');
}


?>
