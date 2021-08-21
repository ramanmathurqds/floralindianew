<?php

error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$user = "FLRL";
$horizonApi = "XWxIQZDufocr4fqBMWsw";
$sendNumber = "+919664258769";
$senderid = "FLRLIN";

$message = '';
$message += 'You have received a new order number for FLFKD323423432 ';
$message += 'Delivery date 24-01-1993';
$message += 'Amount 2500';
$message += 'Kindly confirm the order at the earliest, customer is waiting for the order confirmation';

$ch = curl_init("https://smshorizon.co.in/api/sendsms.php?user=".$user."&apikey=".$horizonApi."&mobile=".$sendNumber."&senderid=".$senderid."&message=".$message."&type=txt&tid=xyz");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);      
curl_close($ch); 
?>