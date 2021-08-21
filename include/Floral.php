<?php
// error_reporting(E_ALL);
class Floral {

    public $_tags = array(); 
    public $_data = array();   
    public $_case = '';


    public function __construct($case=false)
    {
        if(!empty($case))
        {
            $this->_case = $case;
            $this->getMetaData();
        }
    }

    function truncate($text, $length) {
	   $length = abs((int)$length);
	   if(strlen($text) > $length) {
		  $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
	   }
	   return($text);
    }
   
    function fetch_api_results($api, $timeout=false, $raw=false, $par=false)
    {
		$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $api);

   		if(!empty($par))
		{
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, array('serial'=>json_encode($par)));
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	if($timeout > 1) 
		{
    		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		}
		else 
		{
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		}

    	$result		 		= curl_exec($ch);
        $info 				= curl_getinfo($ch);
        $data['url']		= $info['url'];
        $data['total_time'] = $info['total_time'];

		if(!empty($_GET['trace']))
    	{
    		echo $api . '<br>';
    		echo '<pre>';
		}
    	curl_close ($ch);

    	if(!empty($raw)) {
    		$result = json_decode($result, true);
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


	/* Image upload for photo cake */
	public static function uploadproimg($d, $FILES)
    {
		$result = array();

        $allowedExts = array("jpeg", "jpg", "png");
        $temp = explode(".", $FILES["CakePhotoFile"]["name"]);
        $extension = strtolower(end($temp));
        if($d['module'] == 'photoCakeImages'){
            $folder = 'photoCakeImages';
        }

        if (!file_exists('Content/assets/images/'.$folder))
        {
            mkdir('Content/assets/images/'.$folder, 0777, true) or die('fail to create folder');
        }

        $flocation = 'Content/assets/images/'.$folder.'/';
        
        if (($FILES["CakePhotoFile"]["size"] < 5000000000) && in_array($extension, $allowedExts))
        {
            if ($FILES["CakePhotoFile"]["error"] > 0)
            {
                $result['error'] = 1;
                $result['msg']   = $FILES["CakePhotoFile"]["error"];
            }
            else
            {
                $vrname = uniqid();
                $fileName = $vrname.".".$extension;

                if(move_uploaded_file($FILES["CakePhotoFile"]["tmp_name"],$flocation.$fileName ))
                {
                    $result['error'] 	= 0;
                    $result['path']		= $flocation;
					$result['filename']	= $fileName;
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


	/* Image upload for photo cake */
	public static function uploadproimg1($d, $FILES)
    {
		$result = array();

        $allowedExts = array("jpeg", "jpg", "png");
        $temp = explode(".", $FILES["ProfileImage"]["name"]);
        $extension = strtolower(end($temp));
        if($d['module'] == 'userProfilePics'){
            $folder = 'userProfilePics';
        }

        if (!file_exists('Content/assets/images/'.$folder))
        {
            mkdir('Content/assets/images/'.$folder, 0777, true) or die('fail to create folder');
        }

        $flocation = 'Content/assets/images/'.$folder.'/';
        
        if (($FILES["ProfileImage"]["size"] < 5000000000) && in_array($extension, $allowedExts))
        {
            if ($FILES["ProfileImage"]["error"] > 0)
            {
                $result['error'] = 1;
                $result['msg']   = $FILES["ProfileImage"]["error"];
            }
            else
            {
                $vrname = uniqid();
                $fileName = $vrname.".".$extension;

                if(move_uploaded_file($FILES["ProfileImage"]["tmp_name"],$flocation.$fileName ))
                {
                    $result['error'] 	= 0;
                    $result['path']		= $flocation;
					$result['filename']	= $fileName;
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


	/* End */		
	function getMetaData() {

		SWITCH($this->_case) {

			CASE 'detail':

				$url = FLORAL_API_LINK."floralapi.php?case=getProductDetail&ProductID=".urlencode($_GET['ProductID']);
				$data = $this->fetch_api_results($url, 1);
				$this->_data = json_decode($data);
				$seo = $this->_data->results;

				$this->_tags['title'] = isset($seo->SeoTitle) ?  $seo->SeoTitle : '';
				$this->_tags['desc']  = isset($seo->SeoMetaDescription) ?  $seo->SeoMetaDescription : '';
				$this->_tags['key']   = isset($seo->SeoMetaKeywords) ?  $seo->SeoMetaKeywords : '';

			BREAK;


			CASE 'listing':
				$url = FLORAL_API_LINK."floralapi.php?case=listingPageInfo&ProductCategoryID=".urlencode($_GET['ProductCategoryID']);
				$data = $this->fetch_api_results($url, 1);
				$this->_data = json_decode($data);
				$seo = $this->_data->results;

				$this->_tags['title'] = isset($seo->SeoTitle) ? $seo->SeoTitle : '';
				$this->_tags['desc']  = isset($seo->SeoMetaDescription) ? $seo->SeoMetaDescription : '';
				$this->_tags['key']   = isset($seo->SeoMetaKeywords) ? $seo->SeoMetaKeywords : '';
			BREAK;


			CASE 'home':
				$this->_tags['title'] = 'Buy Online Flowers | Cake | Sameday Gifts Delivery Service in India | Premium Flowers Starting From 299';
				$this->_tags['desc']  = 'Floral India is one of the best stop to send Flowers, Cakes, Balloons, Hampers and many other personalized gifts online. We are renowned as top Florist in New Delhi, India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;

			CASE 'cart':
				$this->_tags['title'] = 'Your shopping basket - Floral India';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;

			CASE 'checkout':
				$this->_tags['title'] = 'Secured checkout - Floral India';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;
			
			CASE 'checkout-success':
				$this->_tags['title'] = 'Order Placed - Floral India';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;

			CASE 'wishlist':
				$this->_tags['title'] = 'My favourite - Floral India';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;

			CASE 'about-us':
				$this->_tags['title'] = 'Buy Online Flowers | Cake | Sameday Gifts Delivery Service in India | Premium Flowers Starting From 299';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Floral India is one of the best stop to send Flowers, Cakes, Balloons, Hampers and many other personalized gifts online. We are renowned as top Florist in New Delhi, India';
			BREAK;

			CASE 'faq':
				$this->_tags['title'] = 'FAQ - Floral India';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;

			CASE 'contact-us':
				$this->_tags['title'] = 'Get in touch - Floral India';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;

			CASE 'track-order':
				$this->_tags['title'] = 'Track your order - Floral India';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;

			CASE 'covid19-precautions':
				$this->_tags['title'] = 'Our Covid 19 precautions - Floral India';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;

			CASE 'preserved-roses':
				$this->_tags['title'] = 'Preserved Roses - Floral India';
				$this->_tags['desc']  = 'Floral India';
				$this->_tags['key']   = 'Flower Delivery, Cake Delivery, Designer cakes delviery in india, Balloons for party';
			BREAK;
			
			DEFAULT:
			BREAK;						
		}
    }

	/* Forming URLs from Event Name */
	function replspecialchar($string) 
	{
		$string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
		$string = str_replace("-", " ", $string);
		$string = preg_replace('!\s+!', ' ', $string); // Replaces multiple spaces with single space
		$string = str_replace(' ', '-', trim($string, "- ")); // Replaces all spaces with hyphens.
		return $string;
	}
	/* Forming URLs from Event Name */
	
	
	/* Handling ALT Names */
	function replspecialchar_alt($string) 
	{
		$string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
		$string = str_replace("-", " ", $string);
		$string = preg_replace('!\s+!', ' ', $string); // Replaces multiple spaces with single space
		//$string = str_replace(' ', '-', trim($string, "- ")); // Replaces all spaces with hyphens.
		return $string;
	}
	/* Handling ALT Names */
	 
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
	   /* Get Ticketing Data Ends */
  
	function curPageURL() 
	{
		$pageURL = PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		return $pageURL;
	}

    function addResponseLogs($data){
      $content = '';
      $fileLocation =  DOC_ROOT.'logs/'.date('ymd').'.txt';
      $file = fopen($fileLocation,"a+");
      //chmod($fileLocation, 0777);
     
      $content .= "API Name : ".$data['url']." \n";
      $content .= "Response Time : ".$data['total_time']." \n";
      $content .= "Date : ".date('Y-m-d H:i:s')." \n";
      $content .= "\n";
      $content .= "-------------------------------------------------------";
      $content .= "\n";
      fwrite($file,$content);
      fclose($file); 
    }
}
?>
