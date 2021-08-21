<?php

	error_reporting(E_ALL);
	require_once 'class/floral.php';
	
	$json = json_encode(array("results" =>array("msg" =>"Invalid Parameter Passed","error" =>1)));
	
	$floral = new Floral();
	

	$post_data=array(); 
	 if($_POST)	
	 $post_data =json_decode($_POST['serial'],true);
	 $get_data=array(); 
	 if($_GET)
	 $get_data =$_GET;
	
	$params = array_merge($get_data,$post_data);

	
	/*
	if(API_AUTHENTICATION)
	{
		$floral->authenticate_api($params);
	}
	*/
	$case = $params['case'];
	$params = $floral->getslashes($params);
	SWITCH($params['case'])
	{
		
		CASE 'countryListing':
		CASE 'fetchSelectedCountry':
		CASE 'fetchCitiesByCountry':
		CASE 'menuListing':
		CASE 'signupUser':
		CASE 'clientDetails':
		CASE 'footerDetails':
		CASE 'fetchGlobalDetails':
		CASE 'alertDetails':
		CASE 'globalNumbers':
		CASE 'fetchWebSiteData':
		CASE 'keyFeatures':
		CASE 'setCity':
		CASE 'fetchSelectedOccasion':
		CASE 'fetchSelectedProductCat':
		CASE 'fetchCategoryTag':
		CASE 'fetchHasTags':
		CASE 'fetchSelectedCategoryHomePage':
		CASE 'fetchProductSubCategory':
		CASE 'fetchCountryBasedProducts':
		CASE 'fetchCategoryFilters':
		CASE 'fetchAllSubCatForCategory':
		CASE 'getProductDetail':
		CASE 'addToCart':
		CASE 'delteCart':
		CASE 'fetchCartByID':
		CASE 'updateCartUniqueIDByUserID':
		CASE 'delteCartByDate':
		CASE 'addPhotoCakeImage':
		CASE 'fetchDeliveryStates':
		CASE 'fetchDeliveryTimeSlots':
		CASE 'eventFilter':
		CASE 'editCart':
		CASE 'promoCodeValidator':
		CASE 'fetchUserAddress':
		CASE 'addUserAddress':
		CASE 'editUserAddress':
		CASE 'deleteUserAddress':
		CASE 'updateUserAddress':
		CASE 'removePromoCode':
		CASE 'fetchStateForCity':
		CASE 'fetchUserDetails':
		CASE 'updateUserDetails':
		CASE 'addUserImage':
		CASE 'addUserWishlist':
		CASE 'fetchUserWishList':
		CASE 'passwordChange':
		CASE 'fetchTransactionByID':
		CASE 'insertTransactionDetails':
		CASE 'listingPageInfo':
		CASE 'addProductReview':
		CASE 'fetchProductReviews':
		CASE 'insertOrderDetails':
		CASE 'guestLogin':
		CASE 'searchCityBasedOnCountry':
		CASE 'fetchCityBasedOnCountry':
		CASE 'addSelectedAddToProduct':
		CASE 'insertContactUs':
		CASE 'fetchOrderHistory':
		CASE 'socialLogin':
		CASE 'fetchSuccessOrder':
		CASE 'recentProduct':
		CASE 'specialProducts':
		CASE 'fetchOrdersWithTracking':
		CASE 'search_product_list':
		CASE 'fetchFilteredCustomListing':
		CASE 'fetchAllExpressSubCatForCategory':
		CASE 'customCategoryListing':
		CASE 'fetchFilteredCustomListingCount':
		CASE 'fetchCountryBasedAddonProductsGroupOne':
		CASE 'fetchCountryBasedAddonProductsGroupTwo':
		CASE 'fetchCountryBasedAddonProductsGroupThree':
		CASE 'allCountryListing':
		CASE 'insertWalletDetails':
		CASE 'addonAddToCart':
		CASE 'fetchWalletDetails':
		CASE 'delteUserWalletByDate':
		CASE 'loginFunction':
		CASE 'fetchWalletUserHistory':
		CASE 'addSellersData':
		CASE 'fetchUserReminders':
		CASE 'editReminder':
		CASE 'addReminder':
		CASE 'updateReminder':
		CASE 'deleteReminder':
		CASE 'fetchOccasion':
		CASE 'moreProductDetailImages':

			$floral->$case($params);
			if($floral->data[$case])
			{		
				if($params['format'] =='plugin')
				{
					$json = json_encode(array("files"=>$floral->data[$case]));
				}
				else
				{
					$json = json_encode(array("results"=>$floral->data[$case]));
				}
			}

		BREAK;

		DEFAULT :
		
			$json = '{"results":{},"error":"No Valid Action Defined."}';
	}
	
	header('Content-type: application/json');
	echo $json;	
	exit;
?>
