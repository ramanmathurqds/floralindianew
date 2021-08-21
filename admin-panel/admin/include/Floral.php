<?php

//error_reporting(E_ALL);
class Floral 
{
    public $_data = array();   
    public $_case = '';  

    public function __construct($case=false)
    {
        if(!empty($case))
        {
            $this->_case = $case;
        }
    }
	
    function fetch_api_results($api,$timeout=false,$raw=false,$par=false)
    {
		$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $api);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Curl Application');

   		if(!empty($par))
		{
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE); 
			curl_setopt ($ch, CURLOPT_POSTFIELDS, array('serial'=>json_encode($par)));
		}
	
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	if($timeout > 1) 
		{
    		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		}
		else 
		{
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		}

    	$result		 		= curl_exec($ch);
        $info 				= curl_getinfo($ch);
        $data['url']		= $info['url'];
        $data['total_time'] = $info['total_time'];

		if(!empty($_GET['trace']))
    	{
    		echo $api . '<br>';
    		echo '<pre>';
			print_r($info); 
    	}
              
    	curl_close ($ch);
    	return $result;
    }

    function uploadproimg($d,$FILES)
    {
        
        $result = array();
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $FILES["pimage"]["name"]);
        $extension = strtolower(end($temp));
        if($d['module'] == 'panimage'){
            $folder = 'panimage';
        }

        if (!file_exists('uploads/'.$folder)) 
        {
            mkdir('uploads/'.$folder, 0777, true) or die('fail to create folder');
        }
        
        $flocation = 'uploads/'.$folder.'/';
        
        
        if (($FILES["pimage"]["size"] < 5000000000) && in_array($extension, $allowedExts)) 
        {
        
            if ($FILES["pimage"]["error"] > 0)
            {
                $result['error'] = 1;
                $result['msg']   = $FILES["pimage"]["error"];
            }
            else 
            {
                $fname  = preg_replace("/[^a-zA-Z0-9]+/", "", $d["user_name"]);
                $vrname = !empty($fname) ? $fname."_".uniqid() : uniqid();
                $fileName = $vrname.".".$extension;
            
                if(move_uploaded_file($FILES["pimage"]["tmp_name"],$flocation.$fileName ))
                {
                    $result['error'] = 0;
                    $result['path']      = $flocation; 
                    $result['fname']     = $fileName;
                }
            }
        }
        else 
        {
            $result['error'] = 1;
            $result['msg']   = "File Size Exceeded / Extension not supported";
        }
        return $result;
    }


    function uploadproimg1($d,$FILES)
    {
        
        $result = array();
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $FILES["aimage"]["name"]);
        $extension = strtolower(end($temp));
        if($d['module'] == 'adhaarimage') {
            $folder = 'adhaarimage';
        }

        if (!file_exists('uploads/'.$folder)) 
        {
            mkdir('uploads/'.$folder, 0777, true) or die('fail to create folder');
        }
        
        $flocation = 'uploads/'.$folder.'/';
        
        
        if (($FILES["aimage"]["size"] < 5000000000) && in_array($extension, $allowedExts)) 
        {
        
            if ($FILES["aimage"]["error"] > 0)
            {
                $result['error'] = 1;
                $result['msg']   = $FILES["aimage"]["error"];
            }
            else 
            {
                $fname  = preg_replace("/[^a-zA-Z0-9]+/", "", $d["user_name"]);
                $vrname = !empty($fname) ? $fname."_".uniqid() : uniqid();
                $fileName = $vrname.".".$extension;
            
                if(move_uploaded_file($FILES["aimage"]["tmp_name"],$flocation.$fileName ))
                {
                    $result['error'] = 0;
                    $result['path']      = $flocation; 
                    $result['fname']     = $fileName;
                }
            }
        }
        else 
        {
            $result['error'] = 1;
            $result['msg']   = "File Size Exceeded / Extension not supported";
        }
        return $result;
    }


    function uploadproimg2($d,$FILES)
    {
        
        $result = array();
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $FILES["pcimage"]["name"]);
        $extension = strtolower(end($temp));
        if($d['module'] == 'policyimage'){
            $folder = 'policyimage';
        }

        if (!file_exists('uploads/'.$folder)) 
        {
            mkdir('uploads/'.$folder, 0777, true) or die('fail to create folder');
        }
        
        $flocation = 'uploads/'.$folder.'/';
        
        
        if (($FILES["pcimage"]["size"] < 5000000000) && in_array($extension, $allowedExts)) 
        {
        
            if ($FILES["pcimage"]["error"] > 0)
            {
                $result['error'] = 1;
                $result['msg']   = $FILES["pcimage"]["error"];
            }
            else 
            {
                $fname  = preg_replace("/[^a-zA-Z0-9]+/", "", $d["user_name"]);
                $vrname = !empty($fname) ? $fname."_".uniqid() : uniqid();
                $fileName = $vrname.".".$extension;
            
                if(move_uploaded_file($FILES["pcimage"]["tmp_name"],$flocation.$fileName ))
                {
                    $result['error'] = 0;
                    $result['path']      = $flocation; 
                    $result['fname']     = $fileName;
                }
            }
        }
        else 
        {
            $result['error'] = 1;
            $result['msg']   = "File Size Exceeded / Extension not supported";
        }
        return $result;
    }


    
	
	
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
            
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            $dt = curl_exec($ch);
            curl_close($ch);
            if($return_type != 1) {
                $dt = json_decode($dt,true);
            }
            return $dt;
    }
}

?>