<?php
  
ob_start();
session_start();
$protocol = (isset($_SERVER['HTTP_X_FORWARDED_PROTOCOL']) && !empty($_SERVER['HTTP_X_FORWARDED_PROTOCOL'])) ? 'https://' : 'http://';
//$protocol = 'https://';
define('PROTOCOL', $protocol);
 
define('HOST', PROTOCOL . $_SERVER['HTTP_HOST']);

if (stripos(HOST,"localhost"))  {
    // error_reporting(0);
	// error_reporting(E_ALL);
	// ini_set('display_errors', 'on');
	
    define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME']).'/');
    define('FLORAL_API_LINK',HOST.'/floralindia/admin-panel/API/');
    define('FLORAL_AJAX',HOST.'/floralindia/');
    define('IMAGE_URL',HOST.'/floralindia/admin-panel/admin/uploads/');
    define('JS', DOMAIN . 'js/');
    define('MINIFIED', DOMAIN . 'Content/assets/');
    define('LIB_CSS', DOMAIN . 'js/libs/');
    define('FONTS', DOMAIN . 'Content/assets/fonts/');
    define('AUTH_URL', TRUE);
    define('DOMAIN_URL', 'http://localhost/floralindia');
   
}  elseif (stripos(HOST,"floralindia"))  {
    // error_reporting(0);
	// error_reporting(E_ALL);
	// ini_set('display_errors', 'on');
	
    define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME']));
    define('FLORAL_API_LINK',HOST.'/admin-panel/API/');
    define('FLORAL_AJAX',HOST.'/');
    define('IMAGE_URL',HOST.'/admin-panel/admin/uploads/');
    define('JS', DOMAIN . 'js/');
    define('MINIFIED', DOMAIN . 'Content/assets/');
    define('MINIFIED_SELLERS', DOMAIN . 'template/sellers-form/assets/');
    define('LIB_CSS', DOMAIN . 'js/libs/');
    define('FONTS', DOMAIN . 'Content/assets/fonts/');
    define('AUTH_URL', TRUE);
    define('DOMAIN_URL', 'http://floralindia');
   
}  elseif (stripos(HOST,"smoke2.floralindia.com"))  {
     
    error_reporting(0);
	// error_reporting(E_ALL);
	// ini_set('display_errors', 'on');

    define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME']));
    define('FLORAL_API_LINK',HOST.'/admin-panel/API/');
    define('FLORAL_AJAX',HOST.'/');
    define('IMAGE_URL',HOST.'/admin-panel/admin/uploads/');
    define('JS', DOMAIN . 'js/');
    define('LIB_CSS', DOMAIN . 'js/libs/');
    define('MINIFIED', DOMAIN . 'Content/assets/');
    define('FONTS', DOMAIN . 'Content/assets/fonts/');
    define('AUTH_URL', TRUE);
    define('DOMAIN_URL', 'https://smoke2.floralindia.com');

} else {

    define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME']));
    define('FLORAL_API_LINK',HOST.'/admin-panel/API/');
    define('FLORAL_AJAX',HOST.'/');
    define('IMAGE_URL',HOST.'/admin-panel/admin/uploads/');
    define('JS', DOMAIN . 'js/');
    define('LIB_CSS', DOMAIN . 'js/libs/');
    define('MINIFIED', DOMAIN . 'Content/assets/');
    define('FONTS', DOMAIN . 'Content/assets/fonts/');
    define('AUTH_URL', TRUE);
    define('DOMAIN_URL', 'https://floralindia.com');

}

$cookie_domain = (strstr(DOMAIN, 'floralindia.com') == true) ? '.floralindia.com' : HOST.'/floralindia';

define('RESPONSELOG', 1);
define('COOKIE_DOMAIN', $cookie_domain);
define('DOC_ROOT', dirname(__FILE__) . '/');
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']);
define('INCLUDES', DOC_ROOT . 'include/');
define('LOGS', DOC_ROOT . 'log/');
define('TEMPLATE', DOC_ROOT . 'template/');
define('LIBRARY', DOC_ROOT . 'lib/');
define('IMG', DOMAIN . 'Content/assets/images/');
define('CLASS_FILE', DOC_ROOT . '/admin-panel/API/class/');
define('VERSION', '10.8');
define('API_VERSION', '1.00');

// Email config
define('EMAIL_HOST', "103.120.176.195");
define('EMAIL_USERNAME', 'hosting@floralindia.com');
define('EMAIL_PASSWORD', '%jG1u7k7');
define('EMAIL_FROM', 'order@floralindia.com');
define('EMAIL_CC', 'admin@floralindia.com');
define('EMAIL_FROM_NAME', 'FloralIndia');

// SMS config
define('SMS_API_URL', "https://smshorizon.co.in/api/sendsms.php?user=");
define('SMS_USER', "FLRL");
define('SMS_HORIZON_API', 'XWxIQZDufocr4fqBMWsw');
define('SMS_SEND_NUMBER', '+919910200042');
define('SMS_SENDER_ID', 'FLRLIN');
define('SMS_ADMIN_NUMBER', '9910200042');
define('WHATSAPP_NUMBER', '9910200043');

// validation pattern
define('patternLettersOnly', "^[a-zA-Z'\-\s]+$");
define('patternAlphaNumeric', "^[a-zA-Z0-9]+$");
define('patternEmail', '^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$');
define('patternMobileNo', '^[0-9]{10,11}$')
?>
