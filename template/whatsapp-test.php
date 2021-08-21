<?php
//whatsapp notification to customer
$arr=json_encode(array(
    "phone"=>"919833189426",
    "body"=>"Hello. Thank you for placing your order request. Our team has received your order & will confirm shortly."
));
$url="https://eu222.chat-api.com/instance219597/messages?token=6r02eshv7jvmrar0";
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
curl_setopt($ch,CURLOPT_HTTPHEADER,array(
    'content-type:application/json',
    'content-length:'.strlen($arr)
));
$result=curl_exec($ch);
curl_close($ch);
echo $result;
?>