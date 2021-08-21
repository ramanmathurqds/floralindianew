<?php

	function curlCallPost($apiurl, $param, $return_type = '', $auth = '')
	{
		$ch = curl_init($apiurl);
        $postvalue = '';
        foreach($param as $name => $value) {
          $postvalue .= urlencode($name).'='.urlencode($value).'&';
        }
        $postvalue = substr($postvalue, 0, strlen($postvalue)-1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $postvalue);
        if($return_type == 1) {
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        }
        if($auth == 'auth') { 
            curl_setopt($ch, CURLOPT_USERPWD, "fnpuat:fnppavan");
        } else {
			curl_setopt($ch, CURLOPT_USERPWD, "development:DevelopmenT");
		}
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        $dt = curl_exec($ch);
        curl_close($ch);
        if($return_type != 1) {
            $dt = json_decode($dt,true);
        }
        return $dt;
	}
    
    
	function calculateHash($posted, $salt) 
	{
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';
        foreach ($hashVarsSeq as $hash_var) {
			$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
			$hash_string .= '|';
        }
        $hash_string .= $salt;
        $hash = strtolower(hash('sha512', $hash_string));
        return $hash;
	}

	function domain_exists($email, $record = 'MX')
	{
		list($user, $domain) = split('@', $email);
		//return checkdnsrr($domain, $record);
		if(checkdnsrr($domain, $record))
		{
			$data['error']       = 0;
			$data['email_exist'] = true;
		}
		else
		{
			$data['error']       = 1;
			$data['email_exist'] = false;
		}

		return json_encode($data);
	}


	function get_client_ip()
	{
		if($_SERVER['HTTP_AKAXFF'])
		{
			$ip_address = $_SERVER['HTTP_AKAXFF'];
		}
		else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
		{
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else if ($_SERVER['HTTP_TRUE_CLIENT_IP'])
		{
			$ip_address = $_SERVER['HTTP_TRUE_CLIENT_IP'];
		}
		else
		{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}

		$ip_address = get_actual_ip($ip_address);

		return $ip_address;
	}

	function get_actual_ip($ip)
	{
		$ip_list = $ip;
		$ip_arr  =  explode(',',$ip);
		
		if(count($ip_arr) > 1)
		{
			foreach($ip_arr as $key => $val)
			{
				$val = trim($val);
				
				if(stristr($val,'192.168.'))
				{
					unset($ip_arr[$key]);
				}
			}
			
			$ip_arr = array_filter($ip_arr);
			$ip_list = implode(',',$ip_arr);
			$ip_list = trim($ip_list,',');
		}

		return $ip_list;
	}

	function addResponseLogs($data){
		$content = '';
		$fileLocation =  DOC_ROOT.'logs/'.date('ymd').'.txt';
		//chmod($fileLocation, 0777);
		$file = fopen($fileLocation,"a+");
		$content .= "API Name : ".$data['url']." \n";
		$content .= "Response Time : ".$data['total_time']." \n";
		$content .= "Date : ".date('Y-m-d H:i:s')." \n";
		$content .= "\n";
		$content .= "-------------------------------------------------------";
		$content .= "\n";
		fwrite($file,$content);
		fclose($file); 
	}

	function curPageURL() 
	{
		$pageURL = PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		return $pageURL;
	}
?>
