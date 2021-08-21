<?php
	// session_start();

	require_once ('config.php'); 
	require_once (INCLUDES.'Floral.php');

	$is_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	if(stripos(HOST,".floralindia.com")) $is_ajax = true;

	if(!$is_ajax) {
		header('Content-type: application/json');
		$json = json_encode(array("results" =>array("msg" =>"Forbidden Request!","error" =>403)));
		echo $json;
		exit();
	}

	$params = array_merge($_GET,$_POST);
	$case   = explode(",",$params['case']);
	$obj	= new Floral();

	$sizePrice = '';
	$packingPrice = '';
	$slotCharges = '';

	switch($params['case']) /* $params['case'] = $_GET & $_POST */
	{

		case "fetchSelectedCountry":

			$url = FLORAL_API_LINK."floralapi.php?case=fetchSelectedCountry&CountryCode=".urlencode($params['CountryCode']);
			$data = $obj->fetch_api_results($url, 1);
			$array = json_decode($data, true);

			if(!empty($array['results']['CountryCode'])) {
				// setcookie("country", $array['results']['CountryCode'], time()+86400*30,'/', $cookie_domain);
				// setcookie("CountryFlag", $array['results']['CountryFlag'], time()+86400*30,'/', $cookie_domain);
				// setcookie("CountryName", $array['results']['CountryName'], time()+86400*30,'/', $cookie_domain);
				// setcookie("CityName", $array['results']['CityName'], time()+86400*30,'/', $cookie_domain);
				// setcookie("CityID", $array['results']['CityID'], time()+86400*30,'/', $cookie_domain);
				// setcookie("CurrencyLogo", $array['results']['CurrencyLogo'], time()+86400*30,'/', $cookie_domain);

				setcookie("country", $array['results']['CountryCode'], time()+86400*30,'/');
				setcookie("CountryFlag", $array['results']['CountryFlag'], time()+86400*30,'/');
				setcookie("CountryName", $array['results']['CountryName'], time()+86400*30,'/');
				setcookie("CurrencyLogo", $array['results']['CurrencyLogo'], time()+86400*30,'/');
				setcookie("CityName", $array['results']['CityName'], time()+86400*30,'/');
				setcookie("CityID", $array['results']['CityID'], time()+86400*30,'/');
			}
			echo $data;

		break;

		case "setCity":

			$url = FLORAL_API_LINK."floralapi.php?case=setCity&CityID=".urlencode($params['CityID']);
			$data = $obj->fetch_api_results($url, 1);
			$array = json_decode($data, true);

			if(!empty($array['results']['CityName'])) {
				// setcookie("CityName", $array['results']['CityName'], time()+86400*30,'/', $cookie_domain);
				// setcookie("CityID", $array['results']['CityID'], time()+86400*30,'/', $cookie_domain);

				setcookie("CityName", $array['results']['CityName'], time()+86400*30,'/');
				setcookie("CityID", $array['results']['CityID'], time()+86400*30,'/');
			}
			echo $data;

		break;

		case "setCurrency":

			$url = FLORAL_API_LINK."floralapi.php?case=fetchSelectedCountry&CountryCode=".urlencode($params['CountryCode']);
			$data = $obj->fetch_api_results($url, 1);
			$array = json_decode($data, true);

			if(!empty($array['results']['Currency'])) {
				// setcookie("Currency", $array['results']['Currency'], time()+86400*30,'/', $cookie_domain);
				// setcookie("CurrencyLogo", $array['results']['CurrencyLogo'], time()+86400*30,'/', $cookie_domain);

				setcookie("Currency", $array['results']['Currency'], time()+86400*30,'/');
				setcookie("CurrencyLogo", $array['results']['CurrencyLogo'], time()+86400*30,'/');
			}
			echo $data;

		break;

		case "signupUser":
			$url = FLORAL_API_LINK."floralapi.php?case=signupUser&FirstName=".urlencode($params['FirstName'])."&LastName=".urlencode($params['LastName'])."&Email=".urlencode($params['Email'])."&Password=".urlencode($params['Password']);
		  	$data = $obj->fetch_api_results($url, 1);
		 	echo $data;

		break;

		case "eventFilter":
			$url = FLORAL_API_LINK."floralapi.php?case=eventFilter&CategoryFilterID=".urlencode($params['CategoryFilterID'])."&ProductID=".urlencode($params['ProductID']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "guestLogin":
			$url = FLORAL_API_LINK."floralapi.php?case=guestLogin&guestEmail=".urlencode($params['guestEmail']);
			$data = $obj->fetch_api_results($url, 1);

			$array = json_decode($data, true);

			if(!empty($array['results']['guestLoggedIn']) && isset($array['results']['guestLoggedIn']))
			{
				$_SESSION['GUEST_LOGIN'] = $array['results']['guestLoggedIn'];
				$_SESSION['uid'] = $array['results']['ID'];
			}

		 	echo $data;

		break;

		case "fetchDeliveryTimeSlots":
			$url = FLORAL_API_LINK."floralapi.php?case=fetchDeliveryTimeSlots&DeliveryID=".urlencode($params['DeliveryID'])."&DeliveryDate=".urlencode($params['DeliveryDate']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "fetchWalletDetails":
			$userID = $_SESSION['uid'];
			if(!isset($userID)) {
				header('Location: '.rtrim(DOMAIN, '/'));
			}

			$url = FLORAL_API_LINK."floralapi.php?case=fetchWalletDetails&UserID=".urlencode($userID)."&TransactionCurrency=".urlencode($params['TransactionCurrency']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "deleteUserAddress":
			$url = FLORAL_API_LINK."floralapi.php?case=deleteUserAddress&ID=".urlencode($params['ID']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "socialLogin":
			$loginType = $params['loginType'];
			$profilePic = !empty($params['ProfileImage']) ? $params['ProfileImage'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=socialLogin&loginType=".urlencode($loginType)."&socialEmail=".urlencode($params['socialEmail'])."&first_name=".urlencode($params['first_name'])."&last_name=".urlencode($params['last_name'])."&ProfileImage=".urlencode($profilePic);
			$data = $obj->fetch_api_results($url, 1);

			$array = json_decode($data, true);

			if(!empty($array['results']['socailLoggedIn']) && isset($array['results']['socailLoggedIn']))
			{

				$CartUniqueID = $_COOKIE['CartUniqueID'];
				$Blank = 0;

				$url1 = FLORAL_API_LINK."floralapi.php?case=updateCartUniqueIDByUserID&CartUniqueID=".urlencode($CartUniqueID)."&UserID=".urlencode($array['results']['ID'])."&Blank=".urlencode($Blank);
				$data1 = $obj->fetch_api_results($url1, 1);

				$_SESSION['uid'] = $array['results']['ID'];
				$_SESSION["inLogEmail"] = $array['results']['socailLoggedIn'];
				$logfirstname 	= $array['results']['FirstName']; 
				$_SESSION["inLogFirstName"] = $logfirstname;
			}

			echo $data;

		break;

		case "searchCityBasedOnCountry":
			$url = FLORAL_API_LINK."floralapi.php?case=searchCityBasedOnCountry&CityName=".urlencode($params['CityName'])."&CountryCode=".urlencode($params['CountryCode']);
		  	$data = $obj->fetch_api_results($url, 1);

			echo $data;

		break;

		case "search_product_list":
			$url = FLORAL_API_LINK."floralapi.php?case=search_product_list&query=".urlencode($params['query'])."&CityID=".urlencode($params['CityID']);
		  	$data = $obj->fetch_api_results($url, 1);

			echo $data;

		break;

		case "addSelectedAddToProduct":
			$url = FLORAL_API_LINK."floralapi.php?case=addSelectedAddToProduct&CartID=".urlencode($params['CartID'])."&ID=".urlencode($params['ID']);
		  	$data = $obj->fetch_api_results($url, 1);

			echo $data;

		break;

		case "delteCart":
			$url = FLORAL_API_LINK."floralapi.php?case=delteCart&CartID=".urlencode($params['CartID'])."&action=".urlencode($params['action']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "insertTransactionDetails":
			$url = FLORAL_API_LINK."floralapi.php?case=insertTransactionDetails&UserID=".urlencode($params['UserID'])."&TransactionID=".urlencode($params['TransactionID'])."&TransactionType=".urlencode($params['TransactionType'])."&PaymentStatus=".urlencode($params['PaymentStatus'])."&TransactionTotal=".urlencode($params['TransactionTotal'])."&TransactionCurrency=".urlencode($params['TransactionCurrency'])."&PayerEmail=".urlencode($params['PayerEmail'])."&Discount=".urlencode($params['Discount'])."&PaymentStatusCode=".urlencode($params['PaymentStatusCode'])."&PaymentGateway=".urlencode($params['PaymentGateway'])."&UserWalletStatus=".urlencode($params['UserWalletStatus']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "insertOrderDetails":
			$url = FLORAL_API_LINK."floralapi.php?case=insertOrderDetails&UserID=".urlencode($params['UserID'])."&OrderID=".urlencode($params['OrderID'])."&CartID=".urlencode($params['CartID'])."&PaymentStatus=".urlencode($params['PaymentStatus'])."&PaymentStatusCode=".urlencode($params['PaymentStatusCode'])."&TransactionID=".urlencode($params['TransactionID'])."&BillingAddress=".urlencode($params['BillingAddress'])."&MobileNumber=".urlencode($params['MobileNumber']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;


		case "insertWalletDetails":
			$url = FLORAL_API_LINK."floralapi.php?case=insertWalletDetails&UserID=".urlencode($params['UserID'])."&TransactionID=".urlencode($params['TransactionID'])."&TransactionCurrency=".urlencode($params['TransactionCurrency']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "fetchOrderHistory":
			$userID = $_SESSION['uid'];

			$url = FLORAL_API_LINK."floralapi.php?case=fetchOrderHistory&UserID=".urlencode($userID)."&Month=".urlencode($params['Month']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "insertContactUs":
			$url = FLORAL_API_LINK."floralapi.php?case=insertContactUs&contactName=".urlencode($params['contactName'])."&contactSurname=".urlencode($params['contactSurname'])."&contactEmail=".urlencode($params['contactEmail'])."&contactPhone=".urlencode($params['contactPhone'])."&contactSubject=".urlencode($params['contactSubject'])."&contactMessage=".urlencode($params['contactMessage']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;
	
		case "editCart":

			$senderMsg = !empty($params['SenderMessage']) ? $params['SenderMessage'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=editCart&CartID=".urlencode($params['CartID'])."&ProductQty=".urlencode($params['ProductQty'])."&SenderMessage=".urlencode($senderMsg);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "fetchOrdersWithTracking":
			$url = FLORAL_API_LINK."floralapi.php?case=fetchOrdersWithTracking&OrderID=".urlencode($params['OrderID']);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "removePromoCode":
			$url = FLORAL_API_LINK."floralapi.php?case=removePromoCode";
			$data = $obj->fetch_api_results($url, 1);

			$array = json_decode($data, true);

			if(!empty($_SESSION['PromoDiscount']) && isset($_SESSION['PromoDiscount']))
			{
				unset($_SESSION['PromoDiscount']);

				// setcookie("Pd", "", time() - 3600,'/', $cooki_edomain);
				setcookie("Pd", "", time() - 3600, '/');
			}

		 	echo $data;

		break;

		case "promoCodeValidator":
			$userID = $_SESSION['uid'];
			$url = FLORAL_API_LINK."floralapi.php?case=promoCodeValidator&PromoCode=".urlencode($params['PromoCode'])."&UserID=".urlencode($userID);
			$data = $obj->fetch_api_results($url, 1);

			$array = json_decode($data, true);

			if(!empty($array['results']['PromoDiscount']) && isset($array['results']['PromoDiscount']))
			{
				$_SESSION['PromoDiscount'] = $array['results']['PromoDiscount'];

				// setcookie("Pd", $array['results']['PromoDiscount'], time()+1800,'/', $cookie_domain);
				setcookie("Pd", $array['results']['PromoDiscount'], time()+1800,'/');
			}

		 	echo $data;

		break;

		case "editUserAddress":
			$userID = $_SESSION['uid'];
			$url = FLORAL_API_LINK."floralapi.php?case=editUserAddress&ID=".urlencode($params['ID'])."&UserID=".urlencode($userID);

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "addProductReview":
			$userID = $_SESSION['uid'];
			$lastName = isset($_SESSION["inLogLastName"]) ? $_SESSION["inLogLastName"] : '';
			$firstName = isset($_SESSION["inLogFirstName"]) ? $_SESSION["inLogFirstName"] : 'User';
			$userName = $firstName.' '.$lastName;

			$url = FLORAL_API_LINK."floralapi.php?case=addProductReview&ProductID=".urlencode($params['ProductID'])."&UserID=".urlencode($userID)."&Rating=".urlencode($params['Rating'])."&ReviewSubject=".urlencode($params['ReviewSubject'])."&ReviewMessage=".urlencode($params['ReviewMessage'])."&UserName=".urlencode($userName);

			// print_r($url);
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "addUserWishlist":
			$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=addUserWishlist&action=".urlencode($params['action'])."&ProductID=".urlencode($params['ProductID'])."&CartUniqueID=".urlencode($cartUniqueID)."&UserID=".urlencode($userID);

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "updateUserDetails":
			$userID = $_SESSION['uid'];
			$url = FLORAL_API_LINK."floralapi.php?case=updateUserDetails&ID=".urlencode($userID)."&editFirstname=".urlencode($params['editFirstname'])."&editLastname=".urlencode($params['editLastname'])."&editEmail=".urlencode($params['editEmail'])."&editMobile=".urlencode($params['editMobile'])."&editGender=".urlencode($params['editGender'])."&editDOB=".urlencode($params['editDOB']);

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "fetchStateForCity":
			$userID = $_SESSION['uid'];

			$url = FLORAL_API_LINK."floralapi.php?case=fetchStateForCity&CityID=".urlencode($params['CityID']);

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		case "addUserAddress":
			$userID = $_SESSION['uid'];

			$url = FLORAL_API_LINK."floralapi.php?case=addUserAddress&UserID=".urlencode($userID)."&Title=".urlencode($params['Title'])."&FirstName=".urlencode($params['FirstName'])."&LastName=".urlencode($params['LastName'])."&BuildingName=".urlencode($params['BuildingName'])."&StreetName=".urlencode($params['StreetName'])."&AreaName=".urlencode($params['AreaName'])."&Landmark=".urlencode($params['Landmark'])."&City=".urlencode($params['City'])."&State=".urlencode($params['State'])."&Postcode=".urlencode($params['Postcode'])."&Country=".urlencode($params['Country'])."&MobileNumber=".urlencode($params['MobileNumber'])."&AlternateNumber=".urlencode($params['AlternateNumber'])."&SpecialInstruction=".urlencode($params['SpecialInstruction']);

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;

		
		case "updateUserAddress":

			$url = FLORAL_API_LINK."floralapi.php?case=updateUserAddress&ID=".urlencode($params['ID'])."&Title=".urlencode($params['Title'])."&FirstName=".urlencode($params['FirstName'])."&LastName=".urlencode($params['LastName'])."&BuildingName=".urlencode($params['BuildingName'])."&StreetName=".urlencode($params['StreetName'])."&AreaName=".urlencode($params['AreaName'])."&Landmark=".urlencode($params['Landmark'])."&City=".urlencode($params['City'])."&State=".urlencode($params['State'])."&Postcode=".urlencode($params['Postcode'])."&Country=".urlencode($params['Country'])."&MobileNumber=".urlencode($params['MobileNumber'])."&AlternateNumber=".urlencode($params['AlternateNumber'])."&SpecialInstruction=".urlencode($params['SpecialInstruction']);

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;


		case "addPhotoCakeImage":

			if(!empty($_FILES['CakePhotoFile']['name']))
			{
				$params['module'] = 'photoCakeImages';
				$imgop = $obj->uploadproimg($params, $_FILES);
			}

			$cakePhotoFile = $imgop['filename'];

			$url = FLORAL_API_LINK."floralapi.php?case=addPhotoCakeImage&CakePhotoFile=".$cakePhotoFile;
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;
		

		case "addUserImage":

			$userID = $_SESSION['uid'];

			if(!empty($_FILES['ProfileImage']['name']))
			{
				$params['module'] = 'userProfilePics';
				$imgop = $obj->uploadproimg1($params, $_FILES);
			}

			$photoFile = $imgop['filename'];

			$url = FLORAL_API_LINK."floralapi.php?case=addUserImage&ID=".urlencode($userID)."&ProfileImage=".$photoFile;
			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;


		case "addToCart":

			$params = json_decode(file_get_contents('php://input'), true);

			$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';
			$cakePhotoFile = isset($params['CakePhotoFile']) ? $params['CakePhotoFile'] : '';
			$feature = isset($params['Feature']) ? $params['Feature'] : '';
			$type = isset($params['Type']) ? $params['Type'] : '';
			$size = isset($params['Size']) ? $params['Size'] : '';
			$productSizePrice = (isset($params['ProductSizePrice'])) ? "&ProductSizePrice=".urlencode($params['ProductSizePrice']) : '';


			$url = FLORAL_API_LINK."floralapi.php?case=addToCart&UserID=".urlencode($uid)."&ProductID=".urlencode($params['ProductID'])."&ProductQty=".urlencode($params['ProductQty'])."&DeliveryTimeText=".urlencode($params['DeliveryTimeText'])."&DeliveryTimeSlot=".urlencode($params['DeliveryTimeSlot'])."&DeliveryDate=".urlencode($params['DeliveryDate'])."&Size=".urlencode($size)."&SenderName=".urlencode($params['SenderName'])."&SenderMessage=".urlencode($params['SenderMessage'])."&action=".urlencode($params['action'])."&productType=".urlencode($params['productType'])."&Feature=".urlencode($feature)."&Type=".urlencode($type)."&CustomType=".urlencode($params['CustomType'])."&CaptionMessage=".urlencode($params['CaptionMessage'])."&CartUniqueID=".urlencode($cartUniqueID)."&PackingPrice=".urlencode($params['PackingPrice'])."&TimeSlotCharges=".urlencode($params['TimeSlotCharges'])."&photoImage=".urlencode($cakePhotoFile)."&AnonymousPerson=".urlencode($params['AnonymousPerson'])."&City=".urlencode($params['City'])."&ProductCategoryID=".urlencode($params['ChildProductCategoryID'])."&RecieverName=".urlencode($params['RecieverName'])."&ParentProductID=".urlencode($params['ParentProductID']).$productSizePrice;

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;


		case "addonAddToCart":

			$params = json_decode(file_get_contents('php://input'), true);

			$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';
			$cakePhotoFile = isset($params['CakePhotoFile']) ? $params['CakePhotoFile'] : '';
			$feature = isset($params['Feature']) ? $params['Feature'] : '';
			$type = isset($params['Type']) ? $params['Type'] : '';
			$size = isset($params['Size']) ? $params['Size'] : '';
			$productSizePrice = (isset($params['ProductSizePrice'])) ? "&ProductSizePrice=".urlencode($params['ProductSizePrice']) : 0;


			$url = FLORAL_API_LINK."floralapi.php?case=addonAddToCart&UserID=".urlencode($uid)."&ProductID=".urlencode($params['ProductID'])."&ProductQty=".urlencode($params['ProductQty'])."&DeliveryTimeText=".urlencode($params['DeliveryTimeText'])."&DeliveryTimeSlot=".urlencode($params['DeliveryTimeSlot'])."&DeliveryDate=".urlencode($params['DeliveryDate'])."&Size=".urlencode($size)."&SenderName=".urlencode($params['SenderName'])."&SenderMessage=".urlencode($params['SenderMessage'])."&action=".urlencode($params['action'])."&productType=".urlencode($params['productType'])."&Feature=".urlencode($feature)."&Type=".urlencode($type)."&CustomType=".urlencode($params['CustomType'])."&CaptionMessage=".urlencode($params['CaptionMessage'])."&CartUniqueID=".urlencode($cartUniqueID)."&PackingPrice=".urlencode($params['PackingPrice'])."&TimeSlotCharges=".urlencode($params['TimeSlotCharges'])."&photoImage=".urlencode($cakePhotoFile)."&AnonymousPerson=".urlencode($params['AnonymousPerson'])."&City=".urlencode($params['City'])."&ProductCategoryID=".urlencode($params['ProductCategoryID'])."&RecieverName=".urlencode($params['RecieverName'])."&ParentProductID=".urlencode($params['ParentProductID'])."&ParentProductType=".urlencode($params['ParentProductType'])."&ParentDeliveryDate=".urlencode($params['ParentDeliveryDate'])."&ParentTimeSlotCharges=".urlencode($params['ParentTimeSlotCharges']).$productSizePrice;

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;

		break;


		case "fetchFilteredCustomListing":
			if(!empty($params['filter_items'])){
				$filter_items = $params['filter_items'];

				$items = implode(",", $filter_items);
			} else {
				$items = '';
			}

			$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';
			$productCategoryID = isset($params['ProductCategoryID']) ? $params['ProductCategoryID'] : '';
			$productSubCategoryName = isset($params['ProductSubCategoryName']) ? $params['ProductSubCategoryName'] : '';
			$sort = isset($params['sort']) ? $params['sort'] : '';
			$menuName = isset($params['MenuName']) ? $params['MenuName'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=fetchFilteredCustomListing&ProductCategoryID=".urlencode($productCategoryID)."&minimum_price=".urlencode($params['minimum_price'])."&maximum_price=".urlencode($params['maximum_price'])."&oneDay=".urlencode($params['oneDay'])."&filter_items=".urlencode($items)."&sort=".urlencode($sort)."&UserID=".urlencode($userID)."&CartUniqueID=".urlencode($cartUniqueID)."&page=".urlencode($params['page'])."&CityID=".urlencode($params['CityID'])."&list-type=".urlencode($params['ListType'])."&ProductSubCategoryName=".urlencode($productSubCategoryName)."&MenuName=".urlencode($menuName);

		  	$data = $obj->fetch_api_results($url, 1);
		 	echo $data;

		break;


		case "fetchFilteredCustomListingCount":
			if(!empty($params['filter_items'])){
				$filter_items = $params['filter_items'];

				$items = implode(",", $filter_items);
			} else {
				$items = '';
			}

			$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';
			$productCategoryID = isset($params['ProductCategoryID']) ? $params['ProductCategoryID'] : '';
			$productSubCategoryName = isset($params['ProductSubCategoryName']) ? $params['ProductSubCategoryName'] : '';
			$sort = isset($params['sort']) ? $params['sort'] : '';
			$menuName = isset($params['MenuName']) ? $params['MenuName'] : '';
			$listType = isset($params['ListType']) ? $params['ListType'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=fetchFilteredCustomListingCount&ProductCategoryID=".urlencode($productCategoryID)."&minimum_price=".urlencode($params['minimum_price'])."&maximum_price=".urlencode($params['maximum_price'])."&oneDay=".urlencode($params['oneDay'])."&filter_items=".urlencode($items)."&sort=".urlencode($sort)."&UserID=".urlencode($userID)."&CartUniqueID=".urlencode($cartUniqueID)."&page=".urlencode($params['page'])."&CityID=".urlencode($params['CityID'])."&list-type=".urlencode($listType)."&ProductSubCategoryName=".urlencode($productSubCategoryName)."&MenuName=".urlencode($menuName);

		  	$data = $obj->fetch_api_results($url, 1);
		 	echo $data;

		break;
		
		case "loginFunction":
			$url = FLORAL_API_LINK."floralapi.php?case=loginFunction&Email=".urlencode($params['Email'])."&Password=".urlencode($params['Password']);
			$data = $obj->fetch_api_results($url, 1);
			$array = json_decode($data, true);

			if(!empty($array['results']['Email']))
			{
				$logfirstname 	= $array['results']['FirstName']; 
				$loglastname 	= $array['results']['LastName']; 
				$usrmail 		= $array['results']['Email'];
				$uid 			= $array['results']['ID'];
				$mobileNo 		= $array['results']['MobileNo'];

				$_SESSION["inLogFirstName"] = $logfirstname;
				$_SESSION["inLogLastName"] = $loglastname;
				$_SESSION["inLogEmail"] = $usrmail;
				$_SESSION["uid"] = $uid;
				$_SESSION["inLogMobileNo"] = $mobileNo;

				$CartUniqueID = $_COOKIE['CartUniqueID'];
				$Blank = 0;

				$url1 = FLORAL_API_LINK."floralapi.php?case=updateCartUniqueIDByUserID&CartUniqueID=".urlencode($CartUniqueID)."&UserID=".urlencode($array['results']['ID'])."&Blank=".urlencode($Blank);
				$data1 = $obj->fetch_api_results($url1, 1);
			}
			echo $data;

		break;

		case "passwordChange":
			$userID = $_SESSION['uid'];

			$url = FLORAL_API_LINK."floralapi.php?case=passwordChange&Password=".urlencode($params['Password'])."&UserID=".urlencode($userID)."&OldPassword=".urlencode($params['OldPassword']); 
		  	$data = $obj->fetch_api_results($url, 1); 
		  	echo $data;

	 	break;


		case "logout":
			if(isset($_SESSION['inLogEmail']))
			{
				session_start();
				session_unset();
				unset($_SESSION['email']);
				unset($_SESSION['uid']);
				session_destroy();
				session_write_close();
			}
			echo $data;

	  	break;

		  case "addSellersData":
			$url = FLORAL_API_LINK."floralapi.php?case=addSellersData&Password=".urlencode($PhoneNumber)."&BusinessName=".urlencode($params['BusinessName'])."&BusinessType=".urlencode($params['BusinessType'])."&BusinessUrl=".urlencode($params['BusinessUrl'])."&Email=".urlencode($params['Email'])."&PhoneNumber=".urlencode($params['PhoneNumber'])."&SellerName=".urlencode($params['SellerName']);

			$data = $obj->fetch_api_results($url, 1);
		 	echo $data;
		break;

		case "editReminder":
			$userID = $_SESSION['uid'];
			$url = FLORAL_API_LINK."floralapi.php?case=editReminder&ID=".urlencode($params['ID']);

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;
		break;

		case "updateReminder":
			$userID = $_SESSION['uid'];
			$userEmail = $_SESSION['inLogEmail'];
			$userName = $_SESSION['inLogFirstName'];
			$url = FLORAL_API_LINK."floralapi.php?case=updateReminder&ID=".urlencode($params['ID'])."&UserID=".urlencode($userID)."&Preference=".urlencode($params['Preference'])."&User_name=".urlencode($userName)."&Email=".urlencode($userEmail)."&ReminderName=".urlencode($params['reminderName'])."&LocationName=".urlencode($params['LocationName'])."&STD=".urlencode($params['reminderCountryCode'])."&ContactNumber=".urlencode($params['reminderContact'])."&EventCode=".urlencode($params['EventCode'])."&Event=".urlencode($params['txtReminderEvent'])."&ReminderDate=".urlencode($params['reminderDate'])."&Notes=".urlencode($params['Notes'])."&IsNotified=0&IsActive=1";

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;
		break;

		case "addReminder":
			$userID = $_SESSION['uid'];
			$userEmail = $_SESSION['inLogEmail'];
			$userName = $_SESSION['inLogFirstName'];
			$url = FLORAL_API_LINK."floralapi.php?case=addReminder&UserID=".urlencode($userID)."&Preference=".urlencode($params['Preference'])."&User_name=".urlencode($userName)."&Email=".urlencode($userEmail)."&ReminderName=".urlencode($params['reminderName'])."&LocationName=".urlencode($params['LocationName'])."&STD=".urlencode($params['reminderCountryCode'])."&ContactNumber=".urlencode($params['reminderContact'])."&EventCode=".urlencode($params['EventCode'])."&Event=".urlencode($params['txtReminderEvent'])."&ReminderDate=".urlencode($params['reminderDate'])."&Notes=".urlencode($params['Notes'])."&IsNotified=0&IsActive=1";

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;
		break;

		case "deleteReminder":
			$userID = $_SESSION['uid'];
			$url = FLORAL_API_LINK."floralapi.php?case=deleteReminder&UserID=".urlencode($userID)."&ID=".urlencode($params['ID']);

			$data = $obj->fetch_api_results($url, 1);

		 	echo $data;
		break;
	}

?>
