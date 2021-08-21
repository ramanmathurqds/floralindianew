<?php

/*
* PayPal configuration 
*/

// PayPal configuration
$protocol = (isset($_SERVER['HTTP_X_FORWARDED_PROTOCOL']) && !empty($_SERVER['HTTP_X_FORWARDED_PROTOCOL'])) ? 'https://' : 'http://';
// $protocol = 'https://';
// define('PROTOCOL', $protocol);

define('HOST', $protocol . $_SERVER['HTTP_HOST']);

if (stripos(HOST,"localhost")) {

    define('PAYPAL_ID', 'sb-bvlha4011034@business.example.com');
    define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE

    // define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME']));
    define('PAYPAL_RETURN_URL', 'http://localhost/floralindia/paypal-placeholder');
    define('PAYPAL_CANCEL_URL', 'http://localhost/floralindia');
    define('PAYPAL_NOTIFY_URL', 'http://localhost/floralindia/ipn');

} elseif (stripos(HOST,"smoke1.floralindia.com"))  {

    define('PAYPAL_ID', 'sb-bvlha4011034@business.example.com');
    define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE

    // define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME']));
    define('PAYPAL_RETURN_URL', 'https://smoke2.floralindia.com/paypal-placeholder');
    define('PAYPAL_CANCEL_URL', 'https://smoke2.floralindia.com');
    define('PAYPAL_NOTIFY_URL', 'https://smoke2.floralindia.com/ipn');

} elseif (stripos(HOST,"smoke2.floralindia.com"))  {

    define('PAYPAL_ID', 'sb-bvlha4011034@business.example.com');
    define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE

    // define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME']));
    define('PAYPAL_RETURN_URL', 'https://smoke2.floralindia.com/paypal-placeholder');
    define('PAYPAL_CANCEL_URL', 'https://smoke2.floralindia.com');
    define('PAYPAL_NOTIFY_URL', 'https://smoke2.floralindia.com/ipn');

} else {

    define('PAYPAL_ID', 'admin@floralindia.com');
    define('PAYPAL_SANDBOX', FALSE); //TRUE or FALSE

    // define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME']));
    define('PAYPAL_RETURN_URL', 'https://floralindia.com/paypal-placeholder');
    define('PAYPAL_CANCEL_URL', 'https://floralindia.com');
    define('PAYPAL_NOTIFY_URL', 'https://floralindia.com/ipn');

	// print_r(PAYPAL_NOTIFY_URL);

}

define('PAYPAL_CURRENCY', 'INR');

// Change not required 
define('PAYPAL_URL', (PAYPAL_SANDBOX) ? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr");


?>
