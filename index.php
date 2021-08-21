<?php
  //error_reporting(E_ALL);
  //ini_set("display_errors", 1);

	//if(preg_match('/(?i)msie [5,6,7,8]/',$_SERVER['HTTP_USER_AGENT']))
	//{
		//header("Location:/floral/upgrade.php");
		//exit;
	//}

	// session_destroy();
	// session_unset();
	// unset($_SESSION['email']);
	$case = (!empty($_GET['case']) ? trim($_GET['case']) : 'home');

	require_once 'config.php';
	require_once (INCLUDES.'Floral.php');
	
	// Set Cookie for Add To Cart
	if(!isset($_COOKIE['CartUniqueID'])) {

		$uniqueID = uniqid();

		// setcookie("CartUniqueID", $uniqueID, time()+86400*30,'/', $cookie_domain);
		setcookie("CartUniqueID", $uniqueID, time()+86400*30, '/');
		$_SESSION['CartUniqueID'] = $uniqueID;
	}

	$params = array_merge($_GET,$_POST); 
	$obj    = new Floral();
	ob_start();

	// Get Active Countries
	$countryUrl = FLORAL_API_LINK."/floralapi.php?case=countryListing";			
	$countryData  = $obj->fetch_api_results($countryUrl, 1);	
	$countryData = json_decode($countryData);
	
	if(!isset($_COOKIE['country']) && !isset($_COOKIE['CountryFlag']) && !isset($_COOKIE['CityID'])) {
		$flag = "/Content/assets/images/CountryFlags/flag-of-India.jpg";
		$countryCode = "in";
		$city = "Delhi";
		$CityID = "706";
		$CurrencyLogo = "&#8377";
	} else {
		$flag = isset($_COOKIE['CountryFlag']) ? $_COOKIE['CountryFlag'] : "/Content/assets/images/CountryFlags/flag-of-India.jpg";
		$countryCode = isset($_COOKIE['country']) ? strtolower($_COOKIE['country']) : "in";
		$city =  isset($_COOKIE['CityName']) ? $_COOKIE['CityName'] : "Delhi";
		$CityID = isset($_COOKIE['CityID']) ? $_COOKIE['CityID'] : "706";
		$CurrencyLogo = isset($_COOKIE['CurrencyLogo']) ? $_COOKIE['CurrencyLogo'] : "&#8377";
	}

	function isMobile() {
		return preg_match("/\b(?:a(?:ndroid|vantgo)|b(?:lackberry|olt|o?ost)|cricket|docomo|hiptop|i(?:emobile|p[ao]d)|kitkat|m(?:ini|obi)|palm|(?:i|smart|windows )phone|symbian|up\.(?:browser|link)|tablet(?: browser| pc)|(?:hp-|rim |sony )tablet|w(?:ebos|indows ce|os))/i", $_SERVER["HTTP_USER_AGENT"]);
	}

	$currency = !empty($_COOKIE['Currency']) ? $_COOKIE['Currency'] : "Rupee (INR)";
	$cityName = !empty($_COOKIE['CityName']) ? $_COOKIE['CityName'] : $city;

	// Get Active Cities based on Country
	// $cityUrl = FLORAL_API_LINK."floralapi.php?case=fetchCitiesByCountry&CountryCode=".$countryCode;			
	// $cityData  = $obj->fetch_api_results($cityUrl, 1);
	// $cityData = json_decode($cityData);

	// Get Website Data based on Country
	$siteUrl = FLORAL_API_LINK."floralapi.php?case=fetchWebSiteData&CountryCode=".$countryCode;			
	$siteData = $obj->fetch_api_results($siteUrl, 1);
	$siteData = json_decode($siteData);

	// Get keyFeatures
	$keyUrl = FLORAL_API_LINK."floralapi.php?case=keyFeatures";
	$keyData = $obj->fetch_api_results($keyUrl, 1);
	$keyData = json_decode($keyData);

	// Get clientDetails
	$clientDetailUrl = FLORAL_API_LINK."floralapi.php?case=clientDetails";
	$cdData = $obj->fetch_api_results($clientDetailUrl, 1);
	$cdData = json_decode($cdData);

	// Get globalNumbers
	$globalNumbersUrl = FLORAL_API_LINK."floralapi.php?case=globalNumbers";
	$gnData = $obj->fetch_api_results($globalNumbersUrl, 1);
	$gnData = json_decode($gnData);

	// Get alertDetails
	$alertDetailsUrl = FLORAL_API_LINK."floralapi.php?case=alertDetails";
	$adData = $obj->fetch_api_results($alertDetailsUrl, 1);
	$adData = json_decode($adData);

	// Get footerDetails
	$footerDetailsUrl = FLORAL_API_LINK."floralapi.php?case=footerDetails";
	$fdData = $obj->fetch_api_results($footerDetailsUrl, 1);
	$fdData = json_decode($fdData);

	// Get GlobalDetails
	$globalDetailsUrl = FLORAL_API_LINK."floralapi.php?case=fetchGlobalDetails";
	$gdData = $obj->fetch_api_results($globalDetailsUrl, 1);
	$gdData = json_decode($gdData);

	// Get Main Occasion for Menu
	$selectedOccasionUrl = FLORAL_API_LINK."floralapi.php?case=fetchSelectedOccasion&CountryCode=".$countryCode;
	$soData = $obj->fetch_api_results($selectedOccasionUrl, 1);
	$soData = json_decode($soData);

	// Get Main Menu's
	$menuListingUrl = FLORAL_API_LINK."floralapi.php?case=menuListing&CountryCode=".$countryCode;
	$mlData = $obj->fetch_api_results($menuListingUrl, 1);
	$mlData = json_decode($mlData);

	// Get Cart listing
	$CartUniqueId = isset($_COOKIE['CartUniqueID']) ? $_COOKIE['CartUniqueID'] : $_SESSION['CartUniqueID'];

	if(isset($_SESSION["uid"]) && !isset($_SESSION["GUEST_LOGIN"])) {
		$dataVal = "UserID=".$_SESSION["uid"];

		$userUrl = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$_SESSION["uid"];
		$userData = $obj->fetch_api_results($userUrl, 1);
		$userData = json_decode($userData);

	} else {
		$dataVal = "CartUniqueID=".$CartUniqueId;
	}

	if(isset($dataVal)) {
		$cartListing = FLORAL_API_LINK."floralapi.php?case=fetchCartByID&".$dataVal."&City=".urlencode($city);
		$clData = $obj->fetch_api_results($cartListing, 1);
		$clData = json_decode($clData);
	}

	$countryFlag = $flag;


    SWITCH ($case) {

		case 'order-history':

			require_once(TEMPLATE.'header.php');

			$url  = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$_SESSION["uid"];
			$data = $obj->fetch_api_results($url, 1);	
			$data = json_decode($data);

			if($params['state'] === 'ongoing-orders') {
				$url1 = FLORAL_API_LINK."floralapi.php?case=fetchOrderHistory&UserID=".$_SESSION["uid"]."&state=ongoing-orders";
				$orhistory = $obj->fetch_api_results($url1, 1);	
				$orhistory = json_decode($orhistory);
			}

			if($params['state'] === 'all-orders') {
				$url1 = FLORAL_API_LINK."floralapi.php?case=fetchOrderHistory&UserID=".$_SESSION["uid"]."&state=all-orders";
				$orhistory = $obj->fetch_api_results($url1, 1);	
				$orhistory = json_decode($orhistory);
			}

			if($params['state'] === 'cancelled-orders') {
				$url1 = FLORAL_API_LINK."floralapi.php?case=fetchOrderHistory&UserID=".$_SESSION["uid"]."&state=cancelled-orders";
				$orhistory = $obj->fetch_api_results($url1, 1);	
				$orhistory = json_decode($orhistory);
			}

			require_once(TEMPLATE.'user/order-history.php');

			require_once(TEMPLATE.'footer.php');
		BREAK;


		case 'edit-profile':

			require_once(TEMPLATE.'header.php');

			$url = FLORAL_API_LINK."floralapi.php?case=user_profile&user_id=".$params['user_id'];			
			$data  = $obj->fetch_api_results($url,1);	
			$data = json_decode($data);

			require_once(TEMPLATE.'edit-profile.php');

			require_once(TEMPLATE.'footer.php');
		BREAK;


		case 'about-us':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			require_once(TEMPLATE.'about-us.php');

			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'track-order':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			$orderid = isset($params['OrderID']) ? $params['OrderID'] : '';
			
			$url = FLORAL_API_LINK."floralapi.php?case=fetchOrdersWithTracking&OrderID=".$orderid;
			$data  = $obj->fetch_api_results($url,1);	
			$data = json_decode($data);

			require_once(TEMPLATE.'track-order.php');

			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'amazon-login':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			require_once(TEMPLATE.'test.php');

			require_once(TEMPLATE.'footer.php');
		BREAK;


		case 'terms-and-condition':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			require_once(TEMPLATE.'terms.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK; 

		case 'faq':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			require_once(TEMPLATE.'faq.php');

			require_once(TEMPLATE.'footer.php');
		BREAK; 


		case 'ipn':

			require_once(TEMPLATE.'PayPal/ipn.php');

		BREAK;

		case 'payu-success':

			require_once(TEMPLATE.'PayU/success.php');

		BREAK;

		case 'paypal-redirect':

			require_once(TEMPLATE.'PayPal/pgRedirect.php');
			
		BREAK;

		case 'paypal-placeholder':

			require_once(TEMPLATE.'PayPal/paypal_placeholder.php');
		BREAK;

		case 'payu-placeholder':

			require_once(TEMPLATE.'PayU/PayUMoney_form.php');
		BREAK;

		case 'wallet-placeholder':

			require_once(TEMPLATE.'Wallet/Wallet_form.php');
		BREAK;

		case 'wallet-success':

			require_once(TEMPLATE.'Wallet/Wallet_success.php');
		BREAK;

		case 'whatsapp-test':

			require_once(TEMPLATE.'whatsapp-test.php');
			
		BREAK;

		case 'contact-us':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			require_once(TEMPLATE.'contact.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'covid19-precautions':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			require_once(TEMPLATE.'covid19-precautions.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'preserved-roses':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			require_once(TEMPLATE.'preserved-roses.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'privacy-policy':
			
			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			require_once(TEMPLATE.'privacy-policy.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'my-profile':

			require_once(TEMPLATE.'header.php');

			$url = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$_SESSION["uid"];
			$data = $obj->fetch_api_results($url, 1);	
			$data = json_decode($data);

			require_once(TEMPLATE.'user/my-profile.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'wish-list':

			require_once(TEMPLATE.'header.php');

			$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=fetchUserWishList&UserID=".$userID."&CartUniqueID=".$cartUniqueID;
			$data = $obj->fetch_api_results($url, 1);	
			$data = json_decode($data);

			require_once(TEMPLATE.'wishlist.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'wallet':

			require_once(TEMPLATE.'header.php');

			$url = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$_SESSION["uid"];
			$data = $obj->fetch_api_results($url, 1);	
			$data = json_decode($data);

			$url1 = FLORAL_API_LINK."floralapi.php?case=fetchWalletUserHistory&UserID=".$_SESSION["uid"];
			$data1 = $obj->fetch_api_results($url1, 1);	
			$data1 = json_decode($data1);

			require_once(TEMPLATE.'user/wallet.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'address-book':

			require_once(TEMPLATE.'header.php');

			$url = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$_SESSION["uid"];
			$data = $obj->fetch_api_results($url, 1);	
			$data = json_decode($data);

			$url1 = FLORAL_API_LINK."floralapi.php?case=fetchUserAddress&".$dataVal;
			$data1 = $obj->fetch_api_results($url1, 1);	
			$data1 = json_decode($data1);

			$url3  = FLORAL_API_LINK."floralapi.php?case=fetchCityBasedOnCountry&CountryCode=".$countryCode;
			$data3 = $obj->fetch_api_results($url3, 1);	
			$data3 = json_decode($data3);

			require_once(TEMPLATE.'user/address.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'occasion-reminder':
			require_once(TEMPLATE.'header.php');
			$url = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$_SESSION["uid"];
			$data = $obj->fetch_api_results($url, 1);	
			$data = json_decode($data);

			$url1 = FLORAL_API_LINK."floralapi.php?case=fetchUserReminders&UserID=".$_SESSION["uid"];
			$data1 = $obj->fetch_api_results($url1, 1);
			$data1 = json_decode($data1);

			$url2 = FLORAL_API_LINK."floralapi.php?case=countryListing";
			$data2 = $obj->fetch_api_results($url2, 1);
			$data2 = json_decode($data2);

			$url3 = FLORAL_API_LINK."floralapi.php?case=fetchOccasion";
			$data3 = $obj->fetch_api_results($url3, 1);
			$data3 = json_decode($data3);


			require_once(TEMPLATE.'user/personal-reminder.php');
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'account-setting':

			require_once(TEMPLATE.'header.php');

			$url = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$_SESSION["uid"];
			$data = $obj->fetch_api_results($url, 1);	
			$data = json_decode($data);

			require_once(TEMPLATE.'user/account-setting.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'product':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			if(isset($_SESSION['GUEST_LOGIN']) && !empty($_SESSION['GUEST_LOGIN'])) {
				unset($_SESSION['GUEST_LOGIN']);
				unset($_SESSION['uid']);
			}

			$ProductCategoryID = $params['ProductCategoryID'];
			$ProductID = $params['ProductID'];

			$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=getProductDetail&ProductID=".$ProductID."&UserID=".$userID."&CartUniqueID=".$cartUniqueID."&ProductCategoryID=".$ProductCategoryID."&CityID=".$CityID;
			$data = $obj->fetch_api_results($url, 1);	
			$data = json_decode($data);

			$url1 = FLORAL_API_LINK."floralapi.php?case=fetchCategoryFilters&ProductCategoryID=".$ProductCategoryID;
			$data1 = $obj->fetch_api_results($url1, 1);	
			$data1 = json_decode($data1);

			$url2 = FLORAL_API_LINK."floralapi.php?case=fetchDeliveryStates&CategoryID=".$ProductCategoryID."&CountryCode=".$countryCode;
			$timeSlot = $obj->fetch_api_results($url2, 1);
			$timeSlot = json_decode($timeSlot);

			$url3 = FLORAL_API_LINK."floralapi.php?case=fetchProductReviews&ProductID=".$ProductID;
			$reviews = $obj->fetch_api_results($url3, 1);	
			$reviews = json_decode($reviews);

			$url4 = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$userID;
			$userDetails = $obj->fetch_api_results($url4, 1);
			$userDetails = json_decode($userDetails);

			if (!isset($_SESSION['recentProducts']))
			{
				$_SESSION['recentProducts'] = array();
			}

			if (!in_array($data->results->ProductID, $_SESSION['recentProducts']))
			{
				$_SESSION['recentProducts'][] = $data->results->ProductID;
			}

			$recentprod = isset($_SESSION["recentProducts"]) ? implode(", ",$_SESSION["recentProducts"]) : "";

			$url5 = FLORAL_API_LINK."floralapi.php?case=recentProduct&ProductID=".urlencode($recentprod);
			$recentProducts = $obj->fetch_api_results($url5, 1);	
			$recentProducts = json_decode($recentProducts);

			$url6 = FLORAL_API_LINK."floralapi.php?case=fetchCountryBasedAddonProductsGroupOne&CityID=".$CityID;
			$addonGroupOne = $obj->fetch_api_results($url6, 1);
			$addonGroupOne = json_decode($addonGroupOne);

			$url7 = FLORAL_API_LINK."floralapi.php?case=fetchCountryBasedAddonProductsGroupTwo&CityID=".$CityID;
			$addonGroupTwo = $obj->fetch_api_results($url7, 1);
			$addonGroupTwo = json_decode($addonGroupTwo);
			
			$url8 = FLORAL_API_LINK."floralapi.php?case=fetchCountryBasedAddonProductsGroupThree&CityID=".$CityID;
			$addonGroupThree = $obj->fetch_api_results($url8, 1);
			$addonGroupThree = json_decode($addonGroupThree);

			$url9 = FLORAL_API_LINK."floralapi.php?case=moreProductDetailImages&ProductID=".$ProductID;
			$moreImages = $obj->fetch_api_results($url9, 1);
			$moreImages = json_decode($moreImages);

			require_once(TEMPLATE.'product-detail.php');
			
			require_once (TEMPLATE.'footer.php');
		BREAK;


		case 'checkout':

			require_once(TEMPLATE.'header.php');

			$url = FLORAL_API_LINK."floralapi.php?case=fetchCartByID&".$dataVal."&City=".urlencode($city);
			// print_r($url);

			$data = $obj->fetch_api_results($url, 1);
			$data = json_decode($data);

			if(isset($_SESSION["uid"])) {
				$url1 = FLORAL_API_LINK."floralapi.php?case=fetchUserAddress&UserID=".$_SESSION["uid"]."&City=".urlencode($city);
				$data1 = $obj->fetch_api_results($url1, 1);
				$data1 = json_decode($data1);

				$url2  = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$_SESSION["uid"];
				$data2 = $obj->fetch_api_results($url2, 1);	
				$data2 = json_decode($data2);

				$url3  = FLORAL_API_LINK."floralapi.php?case=allCountryListing";
				$data3 = $obj->fetch_api_results($url3, 1);	
				$data3 = json_decode($data3);

				$url4  = FLORAL_API_LINK."floralapi.php?case=fetchWalletDetails&UserID=".$_SESSION["uid"]."&TransactionCurrency=INR";
				$data4 = $obj->fetch_api_results($url4, 1);	
				$data4 = json_decode($data4);
			}

			require_once(TEMPLATE.'checkout.php');
			require_once(TEMPLATE.'footer.php');
		BREAK;


		case 'cart':

			require_once(TEMPLATE.'header.php');

			$url = FLORAL_API_LINK."floralapi.php?case=fetchCartByID&".$dataVal."&City=".urlencode($city);
			$data = $obj->fetch_api_results($url, 1);	
			$data = json_decode($data);

			require_once(TEMPLATE.'cart.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;


		case 'checkout-cancel':

			require_once(TEMPLATE.'header.php');

			require_once(TEMPLATE.'transaction-cancel.php');
			
			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'testMail':
			require_once(TEMPLATE.'test-mail.php');
		BREAK;


		case 'checkout-success':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			$OrderID = $params['tx'];

			if(isset($_SESSION["uid"])) {
				$url = FLORAL_API_LINK."floralapi.php?case=fetchSuccessOrder&TransactionID=".$OrderID."&UserID=".$_SESSION['uid'];
				$data = $obj->fetch_api_results($url, 1);	
				$data = json_decode($data);

				$userUrl = FLORAL_API_LINK."floralapi.php?case=fetchUserDetails&ID=".$_SESSION["uid"];
				$userData = $obj->fetch_api_results($userUrl, 1);
				$userData = json_decode($userData);
			}

			require_once(TEMPLATE.'transaction-success.php');

			require_once(TEMPLATE.'footer.php');
		BREAK;
	

		case 'listing':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			$listParam = isset($params['list-type']) ? $params['list-type'] : '';

			if(isset($_SESSION['GUEST_LOGIN']) && !empty($_SESSION['GUEST_LOGIN'])) {
				unset($_SESSION['GUEST_LOGIN']);
				unset($_SESSION['uid']);
			}

			$ProductCategoryID = $params['ProductCategoryID'];
			$listType = isset($params['list-type']) ? $params['list-type'] : '';
			$subMenuOfMenu = isset($params['SubMenuOfMenu']) ? urlencode($params['SubMenuOfMenu']) : '';
			$menuName = isset($params['MenuName']) ? urlencode($params['MenuName']) : '';

			if(!empty($params['CatName'])) {
				$CatName = $params['CatName'];
			} else {
				$CatName = $params['subOfMenu'];
			}

			$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=customCategoryListing&ProductCategoryID=".$ProductCategoryID."&CityID=".$CityID."&UserID=".$userID."&CartUniqueID=".$cartUniqueID."&list-type=".$listType."&ProductSubCategoryName=".$subMenuOfMenu."&MenuName=".$menuName;
			$data = $obj->fetch_api_results($url, 1);
			$data = json_decode($data);

			$url1 = FLORAL_API_LINK."floralapi.php?case=fetchCategoryFilters&ProductCategoryID=".$ProductCategoryID;
			$data1 = $obj->fetch_api_results($url1, 1);	
			$data1 = json_decode($data1);

			$url2 = FLORAL_API_LINK."floralapi.php?case=fetchAllSubCatForCategory&ProductCategoryID=".$ProductCategoryID;
			$subCatOfCat = $obj->fetch_api_results($url2, 1);	
			$subCatOfCat = json_decode($subCatOfCat);

			$url3 = FLORAL_API_LINK."floralapi.php?case=listingPageInfo&ProductCategoryID=".$ProductCategoryID;
			$listingPageInfo = $obj->fetch_api_results($url3, 1);	
			$listingPageInfo = json_decode($listingPageInfo);

			require_once(TEMPLATE.'category-listing.php');

			require_once(TEMPLATE.'footer.php');
		BREAK;


		case 'all-listing':

			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			if(isset($_SESSION['GUEST_LOGIN']) && !empty($_SESSION['GUEST_LOGIN'])) {
				unset($_SESSION['GUEST_LOGIN']);
				unset($_SESSION['uid']);
			}

			$listParam = $params['list-type'];

			$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=customCategoryListing&CityID=".$CityID."&UserID=".$userID."&CartUniqueID=".$cartUniqueID."&list-type=".$listParam;
			$data = $obj->fetch_api_results($url, 1);
			$data = json_decode($data);

			$url1 = FLORAL_API_LINK."floralapi.php?case=fetchCategoryFilters";
			$data1 = $obj->fetch_api_results($url1, 1);	
			$data1 = json_decode($data1);

			$url2 = FLORAL_API_LINK."floralapi.php?case=fetchAllExpressSubCatForCategory";
			$expressSubCatOfCat = $obj->fetch_api_results($url2, 1);	
			$expressSubCatOfCat = json_decode($expressSubCatOfCat);

			require_once(TEMPLATE.'all-category-listing.php');

			require_once(TEMPLATE.'footer.php');
		BREAK;

		case 'business':
			require_once(TEMPLATE.'sellers-form/header.php');
			require_once(TEMPLATE.'sellers-form/seller-registration.php');
			require_once(TEMPLATE.'sellers-form/footer.php');
		BREAK;

		default:
			$case = 'home';
			
			$obj1 	= new Floral($case);
			$tag 	= $obj1->_tags;

			require_once(TEMPLATE.'header.php');

			if(isset($_SESSION['GUEST_LOGIN']) && !empty($_SESSION['GUEST_LOGIN'])) {
				unset($_SESSION['GUEST_LOGIN']);
				unset($_SESSION['uid']);
			}

			if(isset($_SESSION['PromoDiscount']) && !empty($_SESSION['PromoDiscount'])) {
				unset($_SESSION['PromoDiscount']);
			}

			$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
			$cartUniqueID = (isset($_COOKIE['CartUniqueID']) && !isset($_SESSION['uid'])) ? $_COOKIE['CartUniqueID'] : '';

			$url = FLORAL_API_LINK."floralapi.php?case=fetchSelectedProductCat";
			$catData = $obj->fetch_api_results($url, 1);
			$catData = json_decode($catData);

			$url1 = FLORAL_API_LINK."floralapi.php?case=fetchCategoryTag";
			$tagData = $obj->fetch_api_results($url1, 1);
			$tagData = json_decode($tagData);

			$url2 = FLORAL_API_LINK."floralapi.php?case=fetchHasTags";
			$hashTagData = $obj->fetch_api_results($url2, 1);
			$hashTagData = json_decode($hashTagData);

			$url3 = FLORAL_API_LINK."floralapi.php?case=fetchSelectedCategoryHomePage";
			$homePageCatData = $obj->fetch_api_results($url3, 1);
			$homePageCatData = json_decode($homePageCatData);

			// $url4 = FLORAL_API_LINK."floralapi.php?case=fetchProductSubCategory";
			// $homePageSubCatData = $obj->fetch_api_results($url4, 1);
			// $homePageSubCatData = json_decode($homePageSubCatData);

			$url5 = FLORAL_API_LINK."floralapi.php?case=fetchCountryBasedProducts&CityID=".$CityID."&UserID=".$userID."&CartUniqueID=".$cartUniqueID."&ProductCategoryID=".$ID;
			// print_r($url5);
			$countryBasedProduct = $obj->fetch_api_results($url5, 1);
			$countryBasedProduct = json_decode($countryBasedProduct);

			$url6 = FLORAL_API_LINK."floralapi.php?case=specialProducts&CityID=".$CityID."&bs";
			$specialProducts = $obj->fetch_api_results($url6, 1);
			$specialProducts = json_decode($specialProducts);

			require_once(TEMPLATE.'home.php');

			require_once(TEMPLATE.'footer.php');
		BREAK;
	} 
?>