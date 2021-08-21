<?php 
//error_reporting(E_ALL); 
require_once ('../config.php');
require_once (INCLUDES.'Floral.php');

session_start();

$params = array_merge($_GET,$_POST); 
$obj	= new Floral();

switch($params['case']) /* $params['case'] = $_GET & $_POST */
	{
	case "update_location":

		$url = API."floralapi.php?case=update_location&location_name=".urlencode($params['location_name'])."&location_id=".$params['location_id'];
		$data = $obj->fetch_api_results($url,1);
		echo $data;

	break;

	case "fetchSelectedCountry":

		$url = API."floralapi.php?case=fetchSelectedCountry&CountryCode=".urlencode($params['CountryCode']);
		$data = $obj->fetch_api_results($url,1);
		$array = json_decode($data, true);

		if(empty($array[results][error])) {
			setcookie("country", $array[results][0][CountryCode], time()+86400*24,'/', $cookie_domain);
			setcookie("CountryFlag", $array[results][0][CountryFlag], time()+86400*24,'/', $cookie_domain);
			setcookie("CountryName", $array[results][0][CountryName], time()+86400*24,'/', $cookie_domain);
		}
		echo $data;

	break;

	}

	?>