<?php

error_reporting(E_ERROR);

session_start();
define('FLORAL_DB','floral_india_db');
define('DOC_ROOT',$_SERVER['DOCUMENT_ROOT']."/");
define('HOST',"https://".$_SERVER['HTTP_HOST']);
//define('DOMAIN', HOST.'/admin-panel/');
define('DOMAIN', HOST.'/admin-panel/');

?>