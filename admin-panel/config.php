<?php

define('HOST',"http://".$_SERVER['HTTP_HOST']);

if(stripos(HOST,"localhost") || 1)
{
	error_reporting(0);

	define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME'])."/");
	define('API',HOST.'/floralindia/admin-panel/API/');
	define('JS_URL',DOMAIN.'tools/js/');
	define('CSS_URL',DOMAIN.'tools/css/');
	define('FILE_URL',DOMAIN.'tools/');
	define('FONT_URL',DOMAIN.'tools/font-awesome/');
	define('TEMPLATE',$_SERVER['DOCUMENT_ROOT'].'templates/');
}
else
{
	error_reporting(0);
	define('DOMAIN',HOST.dirname($_SERVER['SCRIPT_NAME'])."/");
	define('API','http://floralindia/admin-panel/API/');
	define('JS_URL',DOMAIN.'tools/js/');
	define('CSS_URL',DOMAIN.'tools/css/');
	define('FILE_URL',DOMAIN.'tools/');
	define('FONT_URL',DOMAIN.'tools/font-awesome/');
	define('TEMPLATE',DOC_ROOT.'templates/');

}

$cookie_domain = (strstr(DOMAIN, 'floralindia.com') == true) ? '.floralindia.com' : HOST.'/floralindia';

define('RESPONSELOG',1);
define('COOKIE_DOMAIN',$cookie_domain);
define('DOC_ROOT',dirname(__FILE__).'/');
define('INCLUDES',$_SERVER['DOCUMENT_ROOT'].'/floralindia/admin-panel/admin/include/');
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']);
define('LOGS',DOMAIN.'log/');
define('TEMPLATE_PATH',$_SERVER['DOCUMENT_ROOT'].'/floralindia/admin-panel/admin/templates/');
define('LIBRARY',DOMAIN.'lib/');
define('IMG',DOMAIN.'tools/images/'); 
define('UPLOADS',DOMAIN.'uploads/'); 
define('VERSION','1');
define('API_VERSION','1'); 

?>