<?php

/*
* PayU configuration 
*/

// PayU configuration
// $protocol = (isset($_SERVER['HTTP_X_FORWARDED_PROTOCOL']) && !empty($_SERVER['HTTP_X_FORWARDED_PROTOCOL'])) ? 'https://' : 'http://';
$protocol = 'https://';
// define('PROTOCOL', $protocol);

define('HOST', $protocol . $_SERVER['HTTP_HOST']);

// Merchant Key and Salt as provided by Payu.
$MERCHANT_KEY = "axVUjDjE";
$SALT = "zdYkcXW28D";

if (stripos(HOST,"localhost")) {

    error_reporting(0);
	// error_reporting(E_ALL);
    // ini_set('display_errors', 'on');

    $PAYU_BASE_URL = "https://sandboxsecure.payu.in";		// For Sandbox Mode

    define('HOST_URL', HOST.'/floralindia');
    define('PAYU_SUCCESS_URL', 'http://localhost/floralindia/template/index.php?case=payu-success');
    define('PAYU_FAILED_URL', 'http://localhost/floralindia');
    define('API_URL', 'http://localhost/floralindia/admin-panel/API/floralapi.php?case=');

} elseif (stripos(HOST,"smoke1.floralindia.com"))  {

    $PAYU_BASE_URL = "https://sandboxsecure.payu.in";			// For Smoke Mode

    define('HOST_URL', HOST);

    define('PAYU_SUCCESS_URL', HOST.'/payu-success');
    define('PAYU_FAILED_URL', HOST);
    define('API_URL', HOST.'/admin-panel/API/floralapi.php?case=');

} elseif (stripos(HOST,"smoke2.floralindia.com"))  {

    $PAYU_BASE_URL = "https://sandboxsecure.payu.in";			// For Smoke Mode

    define('HOST_URL', HOST);

    define('PAYU_SUCCESS_URL', HOST.'/payu-success');
    define('PAYU_FAILED_URL', HOST);
    define('API_URL', HOST.'/admin-panel/API/floralapi.php?case=');

} else {

    $PAYU_BASE_URL = "https://secure.payu.in";			// For Production Mode

    define('HOST_URL', HOST);
    define('PAYU_SUCCESS_URL', HOST.'/payu-success');
    define('PAYU_FAILED_URL', HOST.'/floralindia.com');
    define('API_URL', HOST.'/admin-panel/API/floralapi.php?case=');
}


?>
