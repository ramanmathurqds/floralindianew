<?php
require_once('class/Database.php');

class Floral extends Database
{
	private $_domain = "http://localhost/floralindia/admin-panel/admin";
	//private $_domain = "http://floralindia.com/admin-panel/admin";

	public function __construct()
    {

    }

	public function getslashes($d,$avdarray =false)
	{
		$res = array();
		$avdarray = (!empty($avdarray) ? $avdarray : array());

		foreach($d as $key=>$value)
		{	
			if(!in_array($key,$avdarray))
			{
				$res[$key] = addslashes(stripslashes(preg_replace("/[^a-zA-Z0-9 !@#$%^&*()-_+=|:';\"<,>.?\/{}\[\]]+/", "", $value)));  
			}
			else
			{
				$res[$key] = $value; 
			}
		}
		return $res;
	}


	public function loginFunction($d)
	{
		$arr = array();

		if(trim($_REQUEST['Email']))
			$Email		= trim($_REQUEST['Email']);
		else if(trim($_POST['Email']))
			$Email		= trim($_POST['Email']);
		else
			$Email		= '';

		if(trim($_REQUEST['Password']))
			$Password		= trim($_REQUEST['Password']);
		else if(trim($_POST['Password']))
			$Password	= trim($_POST['Password']);
		else
			$Password	= '';

		$md5_pwd	= trim($Password);
		$msg			= '';		

		if($Email != '')
		{   
			if(!preg_match('/@.+\./',$Email))
			{
				$msg = 'Please enter valid email id';
			}
		}
		else
		{
			$msg = 'Please enter your email';
		}

		if($Password == '' && empty($msg))
		{ 
			$msg = 'Please enter your password';
		}

		if(!empty($msg))
		{
			echo json_encode(array('results' => array('msg' => $msg, 'error' => 1)));
			exit;
		}

		$md5_pwd	= trim(md5($Password));

		$options['sql'] = "SELECT * FROM ".FLORAL_DB.".flrl_usertable WHERE Email = '".$Email."' AND Password = '".$md5_pwd."'";

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$row = $rs->fetch(PDO::FETCH_ASSOC);

			$arr['ID']    				= $row['ID'];
			$arr['Email']    			= $row['Email'];
			$arr['FirstName']    		= $row['FirstName'];
			$arr['LastName']    		= $row['LastName'];
			$arr['MobileNo']    		= $row['MobileNo'];
			$arr['msg'] 				= "logged In successfully";
			$arr['error'] 				= 0;
		} else {
			$arr['msg'] = "Email ID or Password is incorrect";
			$arr['error'] = 1;
		}
		$this->data['loginFunction'] = $arr;
	}


	public function signupUser($d)
	{
		$arr = array();

		if(trim($_REQUEST['FirstName']))
			$FirstName		= trim($_REQUEST['FirstName']);
		else if(trim($_POST['FirstName']))
			$FirstName	= trim($_POST['FirstName']);
		else
			$FirstName	= '';

		if(trim($_REQUEST['LastName']))
			$LastName		= trim($_REQUEST['LastName']);
		else if(trim($_POST['LastName']))
			$LastName	= trim($_POST['LastName']);
		else
			$LastName	= '';

		if(trim($_REQUEST['Email']))
			$Email		= trim($_REQUEST['Email']);
		else if(trim($_POST['Email']))
			$Email		= trim($_POST['Email']);
		else
			$Email		= '';

		if(trim($_REQUEST['Password']))
			$Password		= trim($_REQUEST['Password']);
		else if(trim($_POST['Password']))
			$Password	= trim($_POST['Password']);
		else
			$Password	= '';
		
		$msg			= '';

		if($Email != '') {
			if(!preg_match('/@.+\./',$Email))
			{
				$msg = 'Please enter valid email';
			}
		}
		else {
			$msg = 'Please enter your email';
		} 

		if($FirstName == '') {
			$msg = 'Please enter your first name';
		}

		if($LastName == '') {
			$msg = 'Please enter your last name';
		}

		if($Password == '' && empty($msg)) { 
			$msg = 'Please enter your password';
		}

		if(!empty($msg)) {
			echo json_encode(array('results' => array('msg' => $msg, 'error' => 1)));
			exit;
		}

		$md5_pwd	= trim(md5($Password));
		date_default_timezone_set('Asia/Kolkata');

		if(!empty($d['FirstName']) && !empty($d['Email'])) {
			$options['sql'] = "SELECT Email from ".FLORAL_DB.".flrl_usertable WHERE Email = '".$Email."' AND UserType = 'User'";
			$options['barr'] = array(':Email' => $d['Email']);

			$rs1 = $this->sqlexecute($options);

			if($rs1->rowCount() > 0) {
				$arr['msg'] = "Email ID is already registered";
				$arr['error'] = 1;
				$arr['flag'] = 0;
			} else {
				$options['barr'] = array(
					":FirstName" 			=> $d['FirstName'],
					":LastName" 			=> $d['LastName'],
					":Email" 				=> $d['Email'],
					":OldPassword" 			=> $d['OldPassword'],
					":Password" 			=> $md5_pwd,
					":UserType"				=> 'User'
				);

				$options['sql'] = "INSERT INTO ".FLORAL_DB.".flrl_usertable (FirstName, LastName, Email, OldPassword, Password, UserType)  VALUES (:FirstName, :LastName, :Email, :OldPassword, :Password, :UserType)";

				$rs = $this->sqlexecute($options);

				if($rs)
				{
					$arr['msg'] = "User is successfully registered.";
					$arr['error'] = 0;
				}
			}
		}
		$this->data['signupUser'] = $arr;
	}


	public function countryListing($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".countries WHERE isActive='1' ORDER BY CountryName ASC";
		$options['barr'] 	= array(":CountryID" => $d['CountryID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['CountryID']			= $row['CountryID'];
				$arr[$i]['CountryCode']			= $row['CountryCode'];
				$arr[$i]['CountryName']			= $row['CountryName'];
				$arr[$i]['CountryFlag']			= $row['CountryFlag'];
				$arr[$i]['Currency']			= $row['Currency'];
				$arr[$i]['CurrencyCode']		= $row['CurrencyCode'];
				$arr[$i]['PhoneCode']			= $row['PhoneCode'];
				$arr[$i]['CurrencyLogo']		= $row['CurrencyLogo'];
				$i++;
			}	
		}

		$this->data['countryListing'] = $arr;
	}


	public function allCountryListing($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".countries ORDER BY CountryName ASC";
		$options['barr'] 	= array(":CountryID" => $d['CountryID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['CountryID']			= $row['CountryID'];
				$arr[$i]['CountryCode']			= $row['CountryCode'];
				$arr[$i]['CountryName']			= $row['CountryName'];
				$arr[$i]['CountryFlag']			= $row['CountryFlag'];
				$arr[$i]['Currency']			= $row['Currency'];
				$arr[$i]['PhoneCode']			= $row['PhoneCode'];
				$arr[$i]['CurrencyCode']		= $row['CurrencyCode'];

				$i++;
			}	
		}

		$this->data['allCountryListing'] = $arr;
	}

	public function fetchDeliveryStates($d)
	{
		$arr = array();

		$categoryID = $d['CategoryID'];

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_deliverytime WHERE
							CategoryID REGEXP '[[:<:]]".$categoryID."[[:>:]]'
							AND isActive='1' AND CountryCode = :CountryCode
							ORDER BY SequenceNo ASC";

		$options['barr'] 	= array(
			":CountryCode"	 => $d['CountryCode']
		);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$deliveryID = $row['DeliveryID'];

				$arr[$i]['DeliveryID']			= $deliveryID;
				$arr[$i]['DeliveryName']		= $row['DeliveryName'];
				$arr[$i]['Charges']				= $row['Charges'];

				// $options['sql'] 	= "SELECT TimeSlot from ".FLORAL_DB.".flrl_deliverytimeslot WHERE DeliveryID = '".$deliveryID."' AND isActive='1'";

				// $options['barr'] 	= array(":DeliveryID" => $d['DeliveryID']);

				// $rs1 = $this->sqlexecute($options, 1);

				// if($rs1->rowCount() > 0)
				// {
				// 	$a = 0;
				// 	while($row = $rs1->fetch(PDO::FETCH_ASSOC))
				// 	{
				// 		$arr[$i]['slots'][$a]['TimeSlot']	= $row['TimeSlot'];

				// 		$a++;
				// 	}
				// }
				$i++;
			}	
		}

		$this->data['fetchDeliveryStates'] = $arr;
	}


	public function fetchDeliveryTimeSlots($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT ID, TimeSlot, MaxTime, InitiateTime from ".FLORAL_DB.".flrl_deliverytimeslot WHERE DeliveryID = :DeliveryID AND isActive='1'";

		$options['barr'] 	= array(":DeliveryID" => $d['DeliveryID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$a = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$a]['ID']				= $row['ID'];
				$arr[$a]['TimeSlot']		= $row['TimeSlot'];
				$arr[$a]['MaxTime']			= $row['MaxTime'];
				$arr[$a]['InitiateTime']	= $row['InitiateTime'];

				$options['sql'] 	= "SELECT Charges from ".FLORAL_DB.".flrl_deliverytime WHERE DeliveryID =".$d['DeliveryID'];

				$options['barr'] 	= array(":DeliveryID" => $d['DeliveryID']);
 
				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$a]['Charges']		= $row['Charges'];

						$options['sql'] = "SELECT Surcharge FROM mastercategory WHERE DATE(EndDate) ='".$d['DeliveryDate']."'";

						$rs1 = $this->sqlexecute($options, 1);

						if($rs1->rowCount() > 0)
						{
							while($row = $rs1->fetch(PDO::FETCH_ASSOC))
							{
								$arr[$a]['Charges']		+= $row['Surcharge'];
							}
						}
					}
				}

				$a++;
			}
		}

		$this->data['fetchDeliveryTimeSlots'] = $arr;
	}


	public function keyFeatures($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".keyfeatures WHERE isActive='1' ORDER BY SequenceNo ASC";
		$options['barr'] 	= array(":KeyFeaturesID" => $d['KeyFeaturesID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['KeyFeaturesID']				= $row['KeyFeaturesID'];
				$arr[$i]['KeyFeaturesName']				= $row['KeyFeaturesName'];
				$arr[$i]['KeyFeaturesDescription']		= $row['KeyFeaturesDescription'];
				$arr[$i]['KeyFeaturesImage']			= $row['KeyFeaturesImage'];

				$i++;
			}	
		}

		$this->data['keyFeatures'] = $arr;
	}


	public function clientDetails($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".clientdetails WHERE isActive='1' ORDER BY SequenceNo ASC";
		$options['barr'] 	= array(":ClientDetailsID" => $d['ClientDetailsID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['ClientDetailsID']			= $row['ClientDetailsID'];
				$arr[$i]['ClientName']				= $row['ClientName'];
				$arr[$i]['ClientLOGO']				= $row['ClientLOGO'];
				$arr[$i]['SequenceNo']				= $row['SequenceNo'];

				$i++;
			}	
		}

		$this->data['clientDetails'] = $arr;
	}

	
	public function footerDetails($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".footerdetails WHERE isActive='1' ORDER BY SequenceNo ASC";
		$options['barr'] 	= array(":FooterID" => $d['FooterID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$FooterID = $row['FooterID'];

				$arr[$i]['FooterID']			= $FooterID;
				$arr[$i]['FooterHeader']		= $row['FooterHeader'];
				$arr[$i]['FooterURL']			= $row['FooterURL'];

				$options['sql'] 	= "SELECT * from ".FLORAL_DB.".footercontent WHERE FooterID ='$FooterID' AND  isActive='1' ORDER BY SequenceNo ASC";
				$options['barr'] 	= array(":FooterContentID" => $d['FooterContentID']);

				$rs1 = $this->sqlexecute($options,1);

				if($rs1->rowCount() > 0)
				{
					$a = 0;
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$i]['SubLinks'][$a]['FooterContentID']		= $row['FooterContentID'];
						$arr[$i]['SubLinks'][$a]['ItemName']			= $row['ItemName'];
						$arr[$i]['SubLinks'][$a]['ItemURL']				= $row['ItemURL'];

						$a++;
					}
				}

				$i++;
			}	
		}

		$this->data['footerDetails'] = $arr;
	}


	public function alertDetails($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".alert WHERE isActive='1'";
		$options['barr'] 	= array(":AlertID" => $d['AlertID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr['AlertID']					= $row['AlertID'];
				$arr['AlertMessage']			= $row['AlertMessage'];
				$arr['KnowMoreLink']			= $row['KnowMoreLink'];
				$arr['AlertDescription']		= $row['AlertDescription'];
				$arr['CountryCode']				= $row['CountryCode'];
				$arr['AlertStartDate']			= $row['AlertStartDate'];
				$arr['AlertEndDate']			= $row['AlertEndDate'];
			}
		}

		$this->data['alertDetails'] = $arr;
	}


	public function globalNumbers($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".globalnumbers WHERE isActive='1' ORDER BY SequenceNo ASC";
		$options['barr'] 	= array(":GlobalNumbersID" => $d['GlobalNumbersID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$countryCode = $row['CountryCode'];

				$arr[$i]['GlobalNumbersID']			= $row['GlobalNumbersID'];
				$arr[$i]['Number']					= $row['Number'];
				$arr[$i]['CountryCode']				= $countryCode;

				$options['sql'] = "SELECT * from ".FLORAL_DB.".countries WHERE CountryCode ='$countryCode'";
				$options['barr'] = array(":CountryCode" => $d['CountryCode']);

				$rs1 = $this->sqlexecute($options,1);

				if($rs1->rowCount() > 0) {
					while($row = $rs1->fetch(PDO::FETCH_ASSOC)) {
						$arr[$i]['PhoneCode']		= $row['PhoneCode'];
					}
				}

				$i++;
			}	
		}

		$this->data['globalNumbers'] = $arr;
	}


	public function fetchCitiesByCountry($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".cities where CountryCode = :CountryCode AND isActive='1' ORDER BY CityName ASC";
		$options['barr'] 	= array(":CountryCode" => $d['CountryCode']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['CityID']			= $row['CityID'];
				$arr[$i]['CityName']		= $row['CityName'];
				$arr[$i]['CountryCode']		= preg_replace("/\s+/", "", $row['CountryCode']);
				$arr[$i]['StateID']			= $row['StateID'];
				// $arr[$i]['IsTopCity']		= $row['IsTopCity'];

				$i++;
			}
		}

		$this->data['fetchCitiesByCountry'] = $arr;
	}


	public function fetchTransactionByID($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_transactiondetail WHERE TransactionID = :TransactionID";
		$options['barr'] 	= array(":TransactionID" => $d['TransactionID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$arr['msg'] = "Transaction ID present";
			$arr['error'] = 1;
		}

		$this->data['fetchTransactionByID'] = $arr;
	}


	public function fetchSelectedProductCat($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".mastercategory where DisaplyOnHomePage = '1' AND isActive='1' ORDER BY SequenceNo ASC";
		$options['barr'] 	= array(":ID" => $d['ID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['ID']							= $row['ID'];
				$arr[$i]['Name']						= $row['Name'];
				$arr[$i]['DesktopImageURL']				= $row['DesktopImageURL'];
				$arr[$i]['MobileImageURL']				= $row['MobileImageURL'];
				$arr[$i]['ProductListPageImageURL']		= $row['ProductListPageImageURL'];
				$arr[$i]['ProductListPageVideoURL']		= $row['ProductListPageVideoURL'];
				$arr[$i]['Logo']						= $row['Logo'];
				$arr[$i]['CountryCode']					= $row['CountryCode'];
				$arr[$i]['DisaplyOnHomePage']			= $row['DisaplyOnHomePage'];
				
				$i++;
			}
		}

		$this->data['fetchSelectedProductCat'] = $arr;
	}


	public function fetchSelectedCategoryHomePage($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".mastercategory WHERE DisaplyOnHomePage = '1' AND isActive='1' ORDER BY SequenceNo ASC LIMIT 7";
		$options['barr'] 	= array(":ID" => $d['ID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['ID']							= $row['ID'];
				$arr[$i]['Name']						= $row['Name'];
				$arr[$i]['DesktopImageURL']				= $row['DesktopImageURL'];
				$arr[$i]['MobileImageURL']				= $row['MobileImageURL'];
				$arr[$i]['ProductListPageImageURL']		= $row['ProductListPageImageURL'];
				$arr[$i]['ProductListPageVideoURL']		= $row['ProductListPageVideoURL'];
				$arr[$i]['Logo']						= $row['Logo'];
				$arr[$i]['CountryCode']					= $row['CountryCode'];
				$arr[$i]['DisaplyOnHomePage']			= $row['DisaplyOnHomePage'];
				
				$i++;
			}
		}

		$this->data['fetchSelectedCategoryHomePage'] = $arr;
	}


	public function fetchProductSubCategory($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".productsubcategory WHERE isActive='1' ORDER BY SequenceNo ASC LIMIT 32";
		$options['barr'] 	= array(":ProductSubCategoryID" => $d['ProductSubCategoryID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$parentCat = $row['ParentCategory'];
				$subCatID = $row['ProductSubCategoryID'];

				$arr[$i]['SubCatID']			= $subCatID;
				$arr[$i]['SubCatName']			= $row['ProductSubCategoryName'];
				$arr[$i]['ImageURL']			= $row['ImageURL'];
				$arr[$i]['SubCatDesc']			= $row['ProductSubCategoryDescription'];
				$arr[$i]['ParentCat']			= $parentCat;
				$arr[$i]['CatTagIDs']			= $row['CategoryTagIDs'];

				$options['sql'] = "SELECT ID, Name FROM ".FLORAL_DB.".masterCategory WHERE ID = '$parentCat'";
				$options['barr'] = array(":ID" => $d['ID']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$i]['CatID']		= $row['ID'];
						$arr[$i]['CatName']		= $row['Name'];
					}

					$options['sql'] = "SELECT ProductID FROM ".FLORAL_DB.".productcategorysubcategorymapping WHERE ProductSubCategoryID = '$subCatID'";
					$options['barr'] = array(":ProductSubCategoryID" => $d['ProductSubCategoryID']);
	
					$rs2 = $this->sqlexecute($options, 1);
	
					if($rs2->rowCount() > 0)
					{
						while($row = $rs2->fetch(PDO::FETCH_ASSOC))
						{
							$arr[$i]['ProductID']	= $row['ProductID'];
						}
					}

				}
				$i++;
			}
		}

		$this->data['fetchProductSubCategory'] = $arr;
	}


	public function fetchCategoryTag($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".categorytag WHERE isActive='1' ORDER BY SequenceNo ASC";
		$options['barr'] 	= array(":CategoryTagID" => $d['CategoryTagID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['CategoryTagID']			= $row['CategoryTagID'];
				$arr[$i]['CategoryTagName']			= $row['CategoryTagName'];
				$arr[$i]['CountryCode']				= $row['CountryCode'];
				$arr[$i]['IsSelected']				= $row['IsSelected'];

				$i++;
			}
		}

		$this->data['fetchCategoryTag'] = $arr;
	}

	public function fetchHasTags($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".hashtag WHERE IsActive='1' ORDER BY SequenceNo ASC";
		$options['barr'] 	= array(":HashTagID" => $d['HashTagID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['HashTagID']			= $row['HashTagID'];
				$arr[$i]['HashTagName']			= $row['HashTagName'];
				$arr[$i]['HashTagURL']			= $row['HashTagURL'];
				$arr[$i]['CountryCode']			= $row['CountryCode'];

				$i++;
			}
		}

		$this->data['fetchHasTags'] = $arr;
	}


	public function removePromoCode($d)
	{
		$arr = array();

		$arr['msg'] = "Promo Code removed";
		$arr['error'] = 0;

		$this->data['removePromoCode'] = $arr;
	}


	public function guestLogin($d)
	{
		$arr = array();

		if(trim($_REQUEST['guestEmail']))
			$guestEmail		= trim($_REQUEST['guestEmail']);
		else if(trim($_POST['guestEmail']))
			$guestEmail		= trim($_POST['guestEmail']);
		else
			$guestEmail		= '';

		if($guestEmail != '') {
			if(!preg_match('/@.+\./',$guestEmail))
			{
				$msg = 'Please enter valid email';
			}
		}
		else {
			$msg = 'Please enter your email';
		}

		if(!empty($msg)) {
			echo json_encode(array('results' => array('msg' => $msg, 'error' => 1)));
			exit;
		}

		date_default_timezone_set('Asia/Kolkata');

		if(!empty($d['guestEmail'])) {
			$options['sql'] = "SELECT * from ".FLORAL_DB.".flrl_usertable WHERE Email = '".$guestEmail."' AND UserType = 'Guest'";
			$options['barr'] = array(':Email' => $d['guestEmail']);

			$rs = $this->sqlexecute($options);

			if($rs->rowCount() > 0) {
				while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
					$arr['msg'] = "User is successfully logged In";
					$arr['error'] = 0;
					$arr['guestLoggedIn'] = $d['guestEmail'];
					$arr['ID'] = $row['ID'];
				}
			} else {
				$options['barr'] = array(
					":FirstName" 			=> 'Guest-'.uniqid(),
					":LastName" 			=> '',
					":guestLoggedIn" 		=> $d['guestEmail'],
					":UserType" 			=> 'Guest'
				);

				$options['sql'] = "INSERT INTO ".FLORAL_DB.".flrl_usertable (FirstName, LastName, Email, UserType)  VALUES (:FirstName, :LastName, :guestLoggedIn, :UserType)";

				$rs1 = $this->sqlexecute($options);

				if($rs1)
				{
					$options['sql'] = "SELECT * from ".FLORAL_DB.".flrl_usertable WHERE Email = '".$guestEmail."' AND UserType = 'Guest'";
					$options['barr'] = array(':Email' => $d['guestEmail']);

					$rs2 = $this->sqlexecute($options);

					if($rs2->rowCount() > 0) {
						while($row = $rs2->fetch(PDO::FETCH_ASSOC)) {
							$arr['ID'] = $row['ID'];
						}
					}

					$arr['msg'] = "User is successfully logged In";
					$arr['error'] = 0;
					$arr['guestLoggedIn'] = $d['guestEmail'];
				}
			}
		}
		$this->data['guestLogin'] = $arr;
	}


	public function socialLogin($d)
	{
		$arr = array();

		if($d['loginType'] === 'fbLogin') {
			$UserType = 'fb-User';
		} else if($d['loginType'] === 'gmailLogin') {
			$UserType = 'gmail-User';
		} else if($d['loginType'] === 'amazon') {
			$UserType = 'amazon-User';
		}

		$socialEmail = $d['socialEmail'];

		date_default_timezone_set('Asia/Kolkata');

		if(!empty($socialEmail)) {
			$options['sql'] = "SELECT * from ".FLORAL_DB.".flrl_usertable WHERE Email = '".$socialEmail."' AND UserType ='".$UserType."'";
			$options['barr'] = array(':Email' => $socialEmail);

			// $arr['sql'] = $options['sql'];

			$rs = $this->sqlexecute($options);

			if ($rs->rowCount() > 0) {

				$options['sql'] = "UPDATE ".FLORAL_DB.".flrl_usertable set FirstName= '".$d['first_name']."', LastName= '".$d['last_name']."', ProfileImage= '".$d['ProfileImage']."' WHERE Email = '".$socialEmail."' AND UserType ='".$UserType."'";

				$rs3 = $this->sqlexecute($options);

				if($rs3) {
					while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
						$arr['msg'] = "User is successfully logged In";
						$arr['error'] = 0;
						$arr['socailLoggedIn'] = $socialEmail;
						$arr['FirstName'] = $row['FirstName'];
						$arr['ID'] = $row['ID'];
					}
				}
			} else {
				$options['barr'] = array(
					":FirstName" 			=> $d['first_name'],
					":LastName" 			=> $d['last_name'],
					":socailLoggedIn" 		=> $socialEmail,
					":UserType" 			=> $UserType,
					":ProfileImage" 		=> $d['ProfileImage']
				);

				$options['sql'] = "INSERT INTO ".FLORAL_DB.".flrl_usertable (FirstName, LastName, Email, UserType, ProfileImage)  VALUES (:FirstName, :LastName, :socailLoggedIn, :UserType, :ProfileImage)";

				$rs1 = $this->sqlexecute($options);

				if($rs1)
				{
					$options['sql'] = "SELECT * from ".FLORAL_DB.".flrl_usertable WHERE Email = '".$socialEmail."' AND UserType ='".$UserType."'";
					$options['barr'] = array(':Email' => $socialEmail);

					$rs2 = $this->sqlexecute($options);

					if($rs2->rowCount() > 0) {
						while($row = $rs2->fetch(PDO::FETCH_ASSOC)) {
							$arr['ID'] = $row['ID'];
							$arr['FirstName'] = $row['FirstName'];
						}
					}

					$arr['msg'] = "User is successfully logged In";
					$arr['error'] = 0;
					$arr['socailLoggedIn'] = $socialEmail;
					$arr['FirstName'] = $d['first_name'];
				}
			}
		}
		$this->data['socialLogin'] = $arr;
	}


	public function fetchSelectedCountry($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".countries where CountryCode = :CountryCode";
		$options['barr'] 	= array(":CountryCode" => $d['CountryCode']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$CountryCode = $row['CountryCode'];

				$arr['CountryID']			= $row['CountryID'];
				$arr['CountryCode']			= $CountryCode;
				$arr['CountryName']			= $row['CountryName'];
				$arr['CountryFlag']			= $row['CountryFlag'];
				$arr['CurrencyLogo']		= $row['CurrencyLogo'];
				$arr['Currency']			= $row['Currency'];
				$arr['PhoneCode']			= $row['PhoneCode'];

				$options['sql'] 	= "SELECT CityName, CityID from ".FLORAL_DB.".cities WHERE IsDefaultCity='1' AND  CountryCode = '$CountryCode'";
				$options['barr'] 	= array(":CountryCode" => $d['CountryCode']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr['CityID']			= $row['CityID'];
						$arr['CityName']		= $row['CityName'];
					}
				}
			}
		}

		$this->data['fetchSelectedCountry'] = $arr;
	}


	public function fetchUserAddress($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_addressbook WHERE UserID = '".$d['UserID']."'";

		$options['barr'] = array(":UserID" => $d['UserID']);

		if(!empty($d["City"]) && isset($d["City"])) {
			$options['sql'] .= " AND City ='".$d['City']."'";
		}

		// $arr['sql'] = $options['sql'];

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['ID']					= $row['ID'];
				$arr[$i]['Title']				= $row['Title'];
				$arr[$i]['FirstName']			= $row['FirstName'];
				$arr[$i]['LastName']			= $row['LastName'];
				$arr[$i]['BuildingName']		= $row['BuildingName'];
				$arr[$i]['StreetName']			= $row['StreetName'];
				$arr[$i]['AreaName']			= $row['AreaName'];
				$arr[$i]['Landmark']			= $row['Landmark'];
				$arr[$i]['City']				= $row['City'];
				$arr[$i]['State']				= $row['State'];
				$arr[$i]['Postcode']			= $row['Postcode'];
				$arr[$i]['Country']				= $row['Country'];
				$arr[$i]['MobileNumber']		= $row['MobileNumber'];
				$arr[$i]['AlternateNumber']		= $row['AlternateNumber'];
				$arr[$i]['SpecialInstruction']	= $row['SpecialInstruction'];

				$i++;
			}
		}

		$this->data['fetchUserAddress'] = $arr;
	}


	public function promoCodeValidator($d)
	{
		$arr = array();

		if(trim($_REQUEST['PromoCode']))
			$PromoCode		= trim($_REQUEST['PromoCode']);
		else if(trim($_POST['PromoCode']))
			$PromoCode		= trim($_POST['PromoCode']);
		else
			$PromoCode		= '';


		if(trim($_REQUEST['UserID']))
		$UserID		= trim($_REQUEST['UserID']);
		else if(trim($_POST['UserID']))
			$UserID		= trim($_POST['UserID']);
		else
			$UserID		= '';

		date_default_timezone_set('Asia/Kolkata');	
		$promoCodeDate	= date("Y-m-d h:i:s");

		$msg	= '';

		if($PromoCode == '')
		{
			$msg = 'Please enter valid promo code';
			$flag = 0; 
		}

		if(!empty($msg))
		{
			echo json_encode(array('results' => array('msg' => $msg, 'error' => 1)));
			exit;
		}

		if(!empty($PromoCode))
		{
			$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_promocode WHERE PromoCode ='".$PromoCode."'";
			$options['barr'] 	= array(':PromoCode' => $PromoCode);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$ID 	 			= $row['ID'];
					$PromoExpiry 		= $row['PromoExpiry'];
					$DiscountMaxValue 	= $row['DiscountMaxValue'];
					$RedeemType			= $row['RedeemType'];
					$DiscountAmount 	= $row['DiscountAmount'];
					$DiscountPercent 	= $row['DiscountPercent'];
					$DiscountType 		= $row['DiscountType'];
					$CategoryID 		= $row['CategoryID'];
					$UsageLimit 		= $row['UsageLimit'];
					$IsActive 			= $row['IsActive'];

					// Check if promo code is active
					if($IsActive == 0) {
						$arr['msg'] = "Promo Code is invalid";
						$arr['error'] = 1;
					} else {
						// Check expiry date of coupon
						if($PromoExpiry <= $promoCodeDate) {
							$arr['msg'] = "Promo Code is expired";
							$arr['error'] = 1;
						} else {
							// Check Usage limit of coupon
							if($UsageLimit == 0) {
								$arr['msg'] = "Promo Code usage limit crossed";
								$arr['error'] = 1;
							} else {

								// Check if coupon is already used by User
								$options['sql'] = "SELECT * from ".FLORAL_DB.".flrl_UserPromocode WHERE PromoCode ='".$PromoCode."' AND UserID = '".$UserID."'";

								$rs3 = $this->sqlexecute($options, 1);
			
								if($rs3->rowCount() > 0)
								{
									$arr['msg'] = "Promo Code already used";
									$arr['error'] = 1;
								} 
								else {
									$arr['msg'] = "Promo Code Applied";
									$arr['error'] = 0;

									$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_cartdetails WHERE UserID = '".$UserID."'";

									$rs1 = $this->sqlexecute($options, 1);
				
									if($rs1->rowCount() > 0)
									{
										while($row = $rs1->fetch(PDO::FETCH_ASSOC))
										{
											$productId 				= $row['ProductID'];
											$productQty 			= $row['ProductQty'];
											$price 					= $row['Price'];
											$productSizePrice 		= $row['ProductSizePrice'];
											$productCategoryID 		= $row['ProductCategoryID'];

											if($DiscountType === 'Category' && $CategoryID === $productCategoryID) {
												if($RedeemType === 'amt') {
													$promoDiscount += $DiscountAmount;
													$discount += $promoDiscount;
												}else{
													$promoDiscount += ($DiscountPercent / 100) * (($price  + $productSizePrice) * $productQty);
													$discount	+= round($promoDiscount);
												}

												// $arr['PromoDiscount']	+= number_format($promoDiscount);

												if($discount > $DiscountMaxValue) {
													$arr['PromoDiscount'] = $DiscountMaxValue;
													$arr['Promo'] = $promoDiscount;
													$arr['price'] += ($price  + $productSizePrice) * $productQty;
													$arr['type'] = $RedeemType;
												} else {
													$arr['PromoDiscount'] += $discount;
													$arr['Promo'] = $promoDiscount;
													$arr['price'] += ($price  + $productSizePrice) * $productQty;
													$arr['type'] = $RedeemType;
												}
											} else {
												if ($DiscountType === 'Cart') {
													if($RedeemType === 'amt') {
														$promoDiscount = $DiscountAmount;
														$discount += $promoDiscount;
													}else{
														$promoDiscount = ($DiscountPercent / 100) * (($price  + $productSizePrice) * $productQty);
														$discount	= round($promoDiscount);
													}

													if($discount > $DiscountMaxValue) {
														$arr['PromoDiscount'] = $DiscountMaxValue;
														$arr['Promo'] = $promoDiscount;
														$arr['price'] += ($price  + $productSizePrice) * $productQty;
														$arr['type'] = $RedeemType;
													} else {
														$arr['PromoDiscount'] += $discount;
														$arr['Promo'] = $promoDiscount;
														$arr['price'] += ($price  + $productSizePrice) * $productQty;
														$arr['type'] = $RedeemType;
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			} else {
				$arr['msg'] = "Invalid Promo Code";
				$arr['error'] = 1;
			}
		}

		$this->data['promoCodeValidator'] = $arr;
	}


	public function fetchCountryBasedProducts($d)
	{
		$arr = array();

		if(!empty($d['CityID'])) {
			$options['sql'] 	= "SELECT * from ".FLORAL_DB.".productcitymapping WHERE CityID =:CityID AND IsActive='1' ORDER BY RAND() LIMIT 5";
			$options['barr'] 	= array(":CityID" => $d['CityID']);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				$i = 0;
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$productId = $row['ProductID'];

					$arr[$i]['ProductID']		= $productId;
					$arr[$i]['CityID']			= $row['CityID'];

					$options['sql'] 	= "SELECT * from ".FLORAL_DB.".productcategorysubcategorymapping WHERE ProductID = '$productId' AND IsActive='1' AND ProductCategoryID=".$d['ProductCategoryID'];
					$options['barr'] 	= array(":ProductID" => $d['ProductID']);
			
					$rs1 = $this->sqlexecute($options, 1);
			
					if($rs1->rowCount() > 0)
					{
						while($row = $rs1->fetch(PDO::FETCH_ASSOC))
						{
							$arr[$i]['ProductCategoryID']		= $row['ProductCategoryID'];


							$options['sql'] 	= "SELECT * from ".FLORAL_DB.".product WHERE ProductID = '$productId' AND IsActive='1'";
							$options['barr'] 	= array(":ProductID" => $d['ProductID']);
					
							$rs2 = $this->sqlexecute($options, 1);
					
							if($rs2->rowCount() > 0)
							{
								while($row = $rs2->fetch(PDO::FETCH_ASSOC))
								{
									if(!empty($row['ProductName']) && $row['isTemp'] != '1') {
										$arr[$i]['ProductName']					= htmlspecialchars($row['ProductName']);
										$arr[$i]['ProductIamge']				= $row['ProductIamge'];
										$arr[$i]['ProductDescription']			= $row['ProductDescription'];
										$arr[$i]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
										$arr[$i]['Price']						= $row['Price'];
										$arr[$i]['Mrp']							= $row['Mrp'];
										$arr[$i]['ProductRating']				= $row['ProductRating'];
										$arr[$i]['ProductRatingCount']			= $row['ProductRatingCount'];

										// Active Whistlist products
										if($d['UserID'] != '0') {
											$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productId." AND UserID = '".$d['UserID']."'";
											$options['barr'] 	= array(
												":ProductID" => $productId,
												":UserID"	 => $d['UserID']
											);
										} else {
											$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productId." AND CartUniqueID = '".$d['CartUniqueID']."'";
											$options['barr'] 	= array(
												":ProductID" 	=> $productId,
												":CartUniqueID" => $d['CartUniqueID']
											);
										}

										$rs3 = $this->sqlexecute($options, 1);

										if($rs3->rowCount() > 0)
										{
											while($row = $rs3->fetch(PDO::FETCH_ASSOC))
											{
												$arr[$i]['ActiveWishList']		= $row['IsActive'];
											}
										} else {
											$arr[$i]['ActiveWishList']		= '0';
										}
									}
								}
							}
						}
					}
					$i++;
				}
			} else {
				$options['sql'] 	= "SELECT * from ".FLORAL_DB.".product WHERE CountryCode = :CountryCode AND IsActive='1' LIMIT 5";
				$options['barr'] 	= array(":CountryCode" => $d['CountryCode']);
		
				$rs1 = $this->sqlexecute($options, 1);
		
				if($rs1->rowCount() > 0)
				{
					$a = 0;
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$productID = $row['ProductID'];

						$arr[$a]['ProductID']					= $productID;
						$arr[$a]['ProductName']					= htmlspecialchars($row['ProductName']);
						$arr[$a]['ProductIamge']				= $row['ProductIamge'];
						$arr[$a]['ProductDescription']			= $row['ProductDescription'];
						$arr[$a]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
						$arr[$a]['Price']						= $row['Price'];
						$arr[$a]['Mrp']							= $row['Mrp'];

						// Active Whistlist products
						if($d['UserID'] != '0') {
							$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND UserID = '".$d['UserID']."'";
							$options['barr'] 	= array(
								":ProductID" => $productID,
								":UserID"	 => $d['UserID']
							);
						} else {
							$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND CartUniqueID = '".$d['CartUniqueID']."'";
							$options['barr'] 	= array(
								":ProductID" 	=> $productID,
								":CartUniqueID" => $d['CartUniqueID']
							);
						}

						$rs2 = $this->sqlexecute($options, 1);

						if($rs2->rowCount() > 0)
						{
							while($row = $rs2->fetch(PDO::FETCH_ASSOC))
							{
								$arr[$a]['ActiveWishList']		= $row['IsActive'];
							}
						} else {
							$arr[$a]['ActiveWishList']		= '0';
						}

						$a++;
					}
				}
			}
		}
		$this->data['fetchCountryBasedProducts'] = $arr;
	}


	// For Flowers, Plants, Party Accessories, Balloons 
	public function fetchCountryBasedAddonProductsGroupOne($d)
	{
		$arr = array();

		if(!empty($d['CityID'])) {
			$options['sql'] 	= "SELECT *
			FROM ".FLORAL_DB.".productcitymapping pcm
				INNER JOIN ".FLORAL_DB.".productcategorysubcategorymapping pcsm
					ON pcm.ProductID = pcsm.ProductID
				INNER JOIN ".FLORAL_DB.".product p
					ON pcsm.ProductID = p.ProductID
					WHERE pcm.CityID =:CityID AND pcm.IsActive='1' AND p.ProductID = pcsm.ProductID AND p.IsActive='1' AND p.Price < 1000 AND pcsm.ProductCategoryID IN (91,100,94) ORDER BY RAND()";

			$options['barr'] 	= array(":CityID" => $d['CityID']);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				$i = 0;
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$productId 	= $row['ProductID'];
					$cityID 	= $row['CityID'];
					$productCategoryID 	= $row['ProductCategoryID'];

					if(!empty($row['ProductName']) && $row['isTemp'] != '1') {

						$filterID = $row['FilterIDs'];
						$filterArray = array_filter(array_map('trim', explode('|', $filterID)));
						sort($filterArray);
						$filterIDs = implode('|', $filterArray);

						$productPrice = $row['Price'];

						$arr[$i]['ProductName']					= htmlspecialchars($row['ProductName']);
						$arr[$i]['ProductImage']				= $row['ProductIamge'];
						$arr[$i]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
						$arr[$i]['Price']						= $productPrice;
						$arr[$i]['Mrp']							= $row['Mrp'];
						$arr[$i]['ProductRating']				= $row['ProductRating'];
						$arr[$i]['ProductRatingCount']			= $row['ProductRatingCount'];
						$arr[$i]['ProductID']					= $productId;
						$arr[$i]['CityID']						= $cityID;
						$arr[$i]['ProductCategoryID']			= $productCategoryID;
						$arr[$i]['FilterIDs']					= $filterIDs;

						// Filters
						$Filter = explode("|", $filterIDs);

						$d = 0;
						foreach ($Filter as $val) {
							$options['sql'] 	= "SELECT * from ".FLORAL_DB.".categoryfilter WHERE CategoryFilterID = '".$val."' AND IsActive='1'";

							$rs2 = $this->sqlexecute($options, 1);

							if($rs2->rowCount() > 0)
							{
								while($row = $rs2->fetch(PDO::FETCH_ASSOC))
								{
									$filterName = str_replace(' ', '', $row['FilterName']);
									$categoryID = $row['CategoryID'];
									$filterN = $row['FilterName'];
									$categoryFilterID = $row['CategoryFilterID'];

									if($filterName === 'SizeAvailability' || $filterName === 'SizeOfBoquet') {
										if($filterName === 'SizeAvailability' || $filterName === 'SizeOfBoquet') {
											$filterPrice = round(($productPrice * $row['FilterPrice']) / 100);
										} else {
											$filterPrice = $row['FilterPrice'];
										}

										$arr[$i]['Filters'][$d]['CategoryFilterID']		= $categoryFilterID;
										$arr[$i]['Filters'][$d]['CategoryID']			= $categoryID;
										$arr[$i]['Filters'][$d]['FilterName']			= $filterN;
										$arr[$i]['Filters'][$d]['FilterValue']			= $row['FilterValue'];
										$arr[$i]['Filters'][$d]['FilterPrice']			= $filterPrice;
										$arr[$i]['Filters'][$d]['SizeInfo']				= $row['SizeInfo'];
										$arr[$i]['Filters'][$d]['XFactor']				= $row['XFactor'];
										$arr[$i]['Filters'][$d]['Active']				= '1';
										
										$d++;
									}
								}
							}
						}

					}
					$i++;
				}
			}
		}
		$this->data['fetchCountryBasedAddonProductsGroupOne'] = $arr;
	}


	// Cake, Chocolates, Hampers, Indian Sweets
	public function fetchCountryBasedAddonProductsGroupTwo($d)
	{
		$arr = array();

		if(!empty($d['CityID'])) {
			$options['sql'] 	= "SELECT *
			FROM ".FLORAL_DB.".productcitymapping pcm
				INNER JOIN ".FLORAL_DB.".productcategorysubcategorymapping pcsm
					ON pcm.ProductID = pcsm.ProductID
				INNER JOIN ".FLORAL_DB.".product p
					ON pcsm.ProductID = p.ProductID
					WHERE pcm.CityID =:CityID AND pcm.IsActive='1' AND p.ProductID = pcsm.ProductID AND p.IsActive='1' AND p.Price < 1000 AND pcsm.ProductCategoryID IN (92,111,97,106) ORDER BY RAND()";

			$options['barr'] 	= array(":CityID" => $d['CityID']);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				$i = 0;
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$productId 	= $row['ProductID'];
					$cityID 	= $row['CityID'];
					$productCategoryID 	= $row['ProductCategoryID'];

					if(!empty($row['ProductName']) && $row['isTemp'] != '1') {

						$filterID = $row['FilterIDs'];
						$filterArray = array_filter(array_map('trim', explode('|', $filterID)));
						sort($filterArray);
						$filterIDs = implode('|', $filterArray);

						$productPrice = $row['Price'];

						$arr[$i]['ProductName']					= htmlspecialchars($row['ProductName']);
						$arr[$i]['ProductImage']				= $row['ProductIamge'];
						$arr[$i]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
						$arr[$i]['Price']						= $productPrice;
						$arr[$i]['Mrp']							= $row['Mrp'];
						$arr[$i]['ProductRating']				= $row['ProductRating'];
						$arr[$i]['ProductRatingCount']			= $row['ProductRatingCount'];
						$arr[$i]['ProductID']					= $productId;
						$arr[$i]['CityID']						= $cityID;
						$arr[$i]['ProductCategoryID']			= $productCategoryID;
						$arr[$i]['FilterIDs']					= $filterIDs;

						// Filters
						$Filter = explode("|", $filterIDs);

						$d = 0;
						foreach ($Filter as $val) {
							$options['sql'] 	= "SELECT * from ".FLORAL_DB.".categoryfilter WHERE CategoryFilterID = '".$val."' AND IsActive='1'";

							$rs2 = $this->sqlexecute($options, 1);

							if($rs2->rowCount() > 0)
							{
								while($row = $rs2->fetch(PDO::FETCH_ASSOC))
								{
									$filterName = str_replace(' ', '', $row['FilterName']);
									$categoryID = $row['CategoryID'];
									$filterN = $row['FilterName'];
									$categoryFilterID = $row['CategoryFilterID'];

									if($filterName === 'SizeAvailability' || $filterName === 'SizeOfBoquet') {
										if($filterName === 'SizeAvailability' || $filterName === 'SizeOfBoquet') {
											$filterPrice = round(($productPrice * $row['FilterPrice']) / 100);
										} else {
											$filterPrice = $row['FilterPrice'];
										}

										$arr[$i]['Filters'][$d]['CategoryFilterID']		= $categoryFilterID;
										$arr[$i]['Filters'][$d]['CategoryID']			= $categoryID;
										$arr[$i]['Filters'][$d]['FilterName']			= $filterN;
										$arr[$i]['Filters'][$d]['FilterValue']			= $row['FilterValue'];
										$arr[$i]['Filters'][$d]['FilterPrice']			= $filterPrice;
										$arr[$i]['Filters'][$d]['SizeInfo']				= $row['SizeInfo'];
										$arr[$i]['Filters'][$d]['XFactor']				= $row['XFactor'];
										$arr[$i]['Filters'][$d]['Active']				= '1';
										
										$d++;
									}
								}
							}
						}

					}
					$i++;
				}
			}
		}
		$this->data['fetchCountryBasedAddonProductsGroupTwo'] = $arr;
	}


	// Perfumes, Artifical Jewelery, Soft Toys, Greeting Card
	public function fetchCountryBasedAddonProductsGroupThree($d)
	{
		$arr = array();

		if(!empty($d['CityID'])) {
			$options['sql'] 	= "SELECT *
			FROM ".FLORAL_DB.".productcitymapping pcm
				INNER JOIN ".FLORAL_DB.".productcategorysubcategorymapping pcsm
					ON pcm.ProductID = pcsm.ProductID
				INNER JOIN ".FLORAL_DB.".product p
					ON pcsm.ProductID = p.ProductID
					WHERE pcm.CityID =:CityID AND pcm.IsActive='1' AND p.ProductID = pcsm.ProductID AND p.IsActive='1' AND p.Price < 1000 AND pcsm.ProductCategoryID IN (98,99,101,102) ORDER BY RAND()";

			$options['barr'] 	= array(":CityID" => $d['CityID']);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				$i = 0;
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$productId 	= $row['ProductID'];
					$cityID 	= $row['CityID'];
					$productCategoryID 	= $row['ProductCategoryID'];

					if(!empty($row['ProductName']) && $row['isTemp'] != '1') {

						$filterID = $row['FilterIDs'];
						$filterArray = array_filter(array_map('trim', explode('|', $filterID)));
						sort($filterArray);
						$filterIDs = implode('|', $filterArray);

						$productPrice = $row['Price'];

						$arr[$i]['ProductName']					= htmlspecialchars($row['ProductName']);
						$arr[$i]['ProductImage']				= $row['ProductIamge'];
						$arr[$i]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
						$arr[$i]['Price']						= $productPrice;
						$arr[$i]['Mrp']							= $row['Mrp'];
						$arr[$i]['ProductRating']				= $row['ProductRating'];
						$arr[$i]['ProductRatingCount']			= $row['ProductRatingCount'];
						$arr[$i]['ProductID']					= $productId;
						$arr[$i]['CityID']						= $cityID;
						$arr[$i]['ProductCategoryID']			= $productCategoryID;
						$arr[$i]['FilterIDs']					= $filterIDs;

						// Filters
						$Filter = explode("|", $filterIDs);

						$d = 0;
						foreach ($Filter as $val) {
							$options['sql'] 	= "SELECT * from ".FLORAL_DB.".categoryfilter WHERE CategoryFilterID = '".$val."' AND IsActive='1'";

							$rs2 = $this->sqlexecute($options, 1);

							if($rs2->rowCount() > 0)
							{
								while($row = $rs2->fetch(PDO::FETCH_ASSOC))
								{
									$filterName = str_replace(' ', '', $row['FilterName']);
									$categoryID = $row['CategoryID'];
									$filterN = $row['FilterName'];
									$categoryFilterID = $row['CategoryFilterID'];

									if($filterName === 'SizeAvailability' || $filterName === 'SizeOfBoquet') {
										if($filterName === 'SizeAvailability' || $filterName === 'SizeOfBoquet') {
											$filterPrice = round(($productPrice * $row['FilterPrice']) / 100);
										} else {
											$filterPrice = $row['FilterPrice'];
										}

										$arr[$i]['Filters'][$d]['CategoryFilterID']		= $categoryFilterID;
										$arr[$i]['Filters'][$d]['CategoryID']			= $categoryID;
										$arr[$i]['Filters'][$d]['FilterName']			= $filterN;
										$arr[$i]['Filters'][$d]['FilterValue']			= $row['FilterValue'];
										$arr[$i]['Filters'][$d]['FilterPrice']			= $filterPrice;
										$arr[$i]['Filters'][$d]['SizeInfo']				= $row['SizeInfo'];
										$arr[$i]['Filters'][$d]['XFactor']				= $row['XFactor'];
										$arr[$i]['Filters'][$d]['Active']				= '1';

										$d++;
									}
								}
							}
						}
					}
					$i++;
				}
			}
		}
		$this->data['fetchCountryBasedAddonProductsGroupThree'] = $arr;
	}


	public function fetchWebSiteData($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".sitedetails where CountryCode = :CountryCode";
		$options['barr'] 	= array(":CountryCode" => $d['CountryCode']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr['SiteName']				= $row['SiteName'];
				$arr['CountryCode']				= $row['CountryCode'];
				$arr['Logo']					= $row['Logo'];
				$arr['MobileNo']				= $row['MobileNo'];
				$arr['DeliverCharges']			= $row['DeliverCharges'];
				$arr['HashTagImage1']			= $row['HashTagImage1'];
				$arr['HashTagImage2']			= $row['HashTagImage2'];
				$arr['PickProductMaxOrderTimeForSameDayDelivery']	= $row['PickProductMaxOrderTimeForSameDayDelivery'];
			}
		}

		$this->data['fetchWebSiteData'] = $arr;
	}


	public function fetchGlobalDetails($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".globaldetails WHERE isActive='1'";
		$options['barr'] 	= array(":GlobalDetailsID" => $d['GlobalDetailsID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr['GlobalDetailsID']			= $row['GlobalDetailsID'];
				$arr['Email']					= $row['Email'];
				$arr['InstagramAccountID']		= $row['InstagramAccountID'];
				$arr['SEOTitle']				= $row['SEOTitle'];
				$arr['SEODescription']			= $row['SEODescription'];
				$arr['SEOKeywords']				= $row['SEOKeywords'];
			}
		}

		$this->data['fetchGlobalDetails'] = $arr;
	}


	public function fetchUserDetails($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_usertable WHERE ID =".$d['ID'];
		$options['barr'] 	= array(":ID" => $d['ID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr['ID']						= $row['ID'];
				$arr['FirstName']				= $row['FirstName'];
				$arr['LastName']				= $row['LastName'];
				$arr['Email']					= $row['Email'];
				$arr['MobileNo']				= $row['MobileNo'];
				$arr['DOB']						= $row['DOB'];
				$arr['Gender']					= $row['Gender'];
				$arr['walletMoney']				= $row['walletMoney'];
				$arr['creditBalance']			= $row['creditBalance'];
				$arr['ProfileImage']			= $row['ProfileImage'];
				$arr['UserType']				= $row['UserType'];
			}
		}

		$this->data['fetchUserDetails'] = $arr;
	}

	
	public function fetchSelectedOccasion($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".mastercategory WHERE CountryCode = :CountryCode AND IsActive='1'";
		$options['barr'] 	= array(":CountryCode" => $d['CountryCode']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['EndDate']				= $row['EndDate'];
				$arr[$i]['StartDate']			= $row['StartDate'];
				$arr[$i]['OccasionImage']		= $row['OccasionImage'];
				$arr[$i]['OccasionBanner']		= $row['OccasionBanner'];
				$arr[$i]['Name']				= $row['Name'];
				$arr[$i]['ID']					= $row['ID'];
				$arr[$i]['Name']				= $row['Name'];
				$arr[$i]['Description']			= $row['Description'];
				$arr[$i]['HashTagName']			= $row['HashTagName'];
				$arr[$i]['IsDefaultActive']		= $row['IsDefaultActive'];

				$i++;
			}
		}

		$this->data['fetchSelectedOccasion'] = $arr;
	}


	public function setCity($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".cities where CityID = :CityID";
		$options['barr'] 	= array(":CityID" => $d['CityID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr['CityID']				= $row['CityID'];
				$arr['CountryCode']			= preg_replace("/\s+/", "", $row['CountryCode']);
				$arr['CityName']			= $row['CityName'];
				$arr['StateID']				= $row['StateID'];
			}
		}

		$this->data['setCity'] = $arr;
	}


	public function eventFilter($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT FilterPrice, FilterName from ".FLORAL_DB.".categoryfilter WHERE CategoryFilterID = :CategoryFilterID";
		$options['barr'] 	= array(":CategoryFilterID" => $d['CategoryFilterID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$FilterName = $row['FilterName'];
				$FilterPrice = $row['FilterPrice'];

				$options['sql'] 	= "SELECT Price from ".FLORAL_DB.".product WHERE ProductID = ".$d['ProductID'];
				$options['barr'] 	= array(":ProductID" => $d['ProductID']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$price			= $row['Price'];
					}
				}

				if($FilterName === 'Size Availability' || $FilterName === 'Size Of Boquet') {

					$filtrPrice = round(($price * $FilterPrice) / 100);

					$arr['sizePrice']	= $filtrPrice;
					// $arr['price']	= $price;
					$arr['error']	= 0;
				}
				else if($FilterName === 'Packing') {
					$arr['packingPrice']	= $FilterPrice;
					$arr['error']	= 0;
				}
			}
		}

		$this->data['eventFilter'] = $arr;
	}

	
	public function moreProductDetailImages($d)
	{
		$arr = array();

		// Get other product images
		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".otherimagelist WHERE ProductId = :ProductID";
		$options['barr'] 	= array(":ProductID" => $d['ProductID']);	

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$k = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$k]['ImageUrl']			= $row['ImageUrl'];
				$arr[$k]['IsVideo']				= $row['IsVideo'];
				$k++;
			}
		}

		$this->data['moreProductDetailImages'] = $arr;
	}


	public function getProductDetail($d)
	{
		$arr = array();

		if(!empty($d['ProductID'])) {
			$options['sql'] 	= "SELECT * from ".FLORAL_DB.".product WHERE ProductID = :ProductID AND IsActive='1'";
			$options['barr'] 	= array(":ProductID" => $d['ProductID']);	

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$similarProductIDs 	= $row['SimilarProductIDs'];
					$filterIDs			= $row['FilterIDs'];
					$waterMarkID		= $row['WaterMarkID'];
					$productID			= $row['ProductID'];
					$productPrice		= $row['Price'];

					$arr['ProductID']					= $productID;
					$arr['ProductName']					= htmlspecialchars($row['ProductName']);
					$arr['IsDeliveryTimeRestricted']	= $row['IsDeliveryTimeRestricted'];
					$arr['ProductImage']				= $row['ProductIamge'];
					$arr['IsVideo']						= $row['IsVideo'];
					$arr['VideoThumb']					 =$row['VideoThumb'];
					$arr['ProductDescription']			= $row['ProductDescription'];
					$arr['ProductShortDescription']		= preg_replace('/[^a-zA-Z0-9_ -]/s','',$row['ProductShortDescription']);
					$arr['Price']						= $productPrice;
					$arr['Mrp']							= $row['Mrp'];
					$arr['ProductType']					= $row['ProductType'];
					$arr['Inclusion']					= $row['Inclusion'];
					$arr['Substitution']				= $row['Substitution'];
					$arr['DeliveryDescription']			= $row['DeliveryDescription'];
					$arr['ProductCode']					= $row['ProductCode'];
					$arr['WaterMarkID']					= $waterMarkID;
					$arr['SimilarProductIDs']			= $similarProductIDs;
					$arr['FilterIDs']					= $filterIDs;
					$arr['SeoTitle']					= preg_replace('/[^a-zA-Z0-9_ -]/s','',$row['SeoTitle']);
					$arr['SeoMetaKeywords']				= preg_replace('/[^a-zA-Z0-9_ -]/s','',$row['SeoMetaKeywords']);
					$arr['SeoMetaDescription']			= preg_replace('/[^a-zA-Z0-9_ -]/s','',$row['SeoMetaDescription']);
					$arr['ProductRating']				= $row['ProductRating'];
					$arr['ProductRatingCount']			= $row['ProductRatingCount'];
					$arr['IsOneDayDelivery']			= $row['isOneDayDelivery'];
					$arr['MinDeliveryDay']				= $row['MinDeliveryDay'];


					// Similar Products
					$options['sql'] = "SELECT * FROM ".FLORAL_DB.".productcategorysubcategorymapping WHERE ProductID='.$productID.' AND IsActive='1' AND NOT ProductSubCategoryID = 0 LIMIT 1";

					$options['barr'] 	= array(":ProductID"	=> $productID);

					// $arr['sqlquery']	= $options['sql'];

					$rs8 = $this->sqlexecute($options, 1);

					if($rs8->rowCount() > 0)
					{
						$c = 0;

						while($row = $rs8->fetch(PDO::FETCH_ASSOC))
						{
							$productCategoryID = $row['ProductCategoryID'];
							$productSubCategoryID = $row['ProductSubCategoryID'];

							$arr['ProductCategoryID']			= $productCategoryID;
							$arr['ProductSubCategoryID']		= $productSubCategoryID;
						}
					}

					// Similar Products
					$options['sql'] = "SELECT * FROM ".FLORAL_DB.".productcitymapping pcm INNER JOIN ".FLORAL_DB.".productcategorysubcategorymapping sm ON pcm.ProductID = sm.ProductID WHERE pcm.CityID = :CityID AND sm.ProductCategoryID = :ProductCategoryID AND sm.IsActive='1' ORDER BY RAND() LIMIT 4";

					$options['barr'] 	= array(
						":CityID" 				=> $d['CityID'],
						":ProductCategoryID" 	=> $d['ProductCategoryID']
					);

					$rs5 = $this->sqlexecute($options, 1);

					if($rs5->rowCount() > 0)
					{
						$c = 0;

						while($row = $rs5->fetch(PDO::FETCH_ASSOC))
						{
							$productID 	= $row['ProductID'];
		
							// Category products
							$options['sql'] = "SELECT * FROM ".FLORAL_DB.".product WHERE ProductID = ".$productID." AND IsActive='1'";
		
							$rs6 = $this->sqlexecute($options, 1);
		
							if($rs6->rowCount() > 0)
							{
								while($row = $rs6->fetch(PDO::FETCH_ASSOC))
								{
									$productID			= $row['ProductID'];

									$arr['SimilarProducts'][$c]['ProductID']					= $productID;
									$arr['SimilarProducts'][$c]['ProductName']					= htmlspecialchars($row['ProductName']);
									$arr['SimilarProducts'][$c]['ProductImage']					= $row['ProductIamge'];
									$arr['SimilarProducts'][$c]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
									$arr['SimilarProducts'][$c]['Price']						= $row['Price'];
									$arr['SimilarProducts'][$c]['Mrp']							= $row['Mrp'];
									$arr['SimilarProducts'][$c]['ProductRatingCount']			= $row['ProductRatingCount'];

									// Active Whistlist products
									if($d['UserID'] != '0') {
										$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID ='".$productID."' AND UserID = '".$d['UserID']."'";
										$options['barr'] 	= array(
											":ProductID" => $productID,
											":UserID"	 => $d['UserID']
										);
									} else {
										$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID ='".$productID."' AND CartUniqueID = '".$d['CartUniqueID']."'";
										$options['barr'] 	= array(
											":ProductID" 	=> $productID,
											":CartUniqueID" => $d['CartUniqueID']
										);
									}

									$rs3 = $this->sqlexecute($options, 1);

									if($rs3->rowCount() > 0)
									{
										while($row = $rs3->fetch(PDO::FETCH_ASSOC))
										{
											$arr['SimilarProducts'][$c]['ActiveWishList']	= $row['IsActive'];
										}
									} else {
										$arr['SimilarProducts'][$c]['ActiveWishList']		= '0';
									}
								}
							}
							$c++;
						}
					}

					// Get Water Mark
					$options['sql'] 	= "SELECT * from ".FLORAL_DB.".watermark WHERE WaterMarkID = '".$waterMarkID."'";

					$rs4 = $this->sqlexecute($options, 1);

					if($rs4->rowCount() > 0)
					{
						while($row = $rs4->fetch(PDO::FETCH_ASSOC))
						{
							$arr['WaterMarkImage']			= $row['WaterMarkImage'];
						}
					}

					// Get category name from master category
					$options['sql'] = "SELECT * FROM ".FLORAL_DB.".mastercategory WHERE ID = '".$productCategoryID."' AND IsActive='1'";

					$rs7 = $this->sqlexecute($options, 1);

					if($rs7->rowCount() > 0)
					{
						while($row = $rs7->fetch(PDO::FETCH_ASSOC))
						{
							$arr['CategoryName']		= $row['Name'];
						}
					}

					// Filters
					$Filter = explode("|", $filterIDs);

					$d = 0;
					foreach ($Filter as $val) {
						$options['sql'] 	= "SELECT * from ".FLORAL_DB.".categoryfilter WHERE CategoryFilterID = '".$val."' AND IsActive='1'";

						$rs2 = $this->sqlexecute($options, 1);

						if($rs2->rowCount() > 0)
						{
							while($row = $rs2->fetch(PDO::FETCH_ASSOC))
							{
								$filterName = str_replace(' ', '', $row['FilterName']);
								$categoryID = $row['CategoryID'];
								$filterN = $row['FilterName'];
								$categoryFilterID = $row['CategoryFilterID'];

								if($filterName === 'SizeAvailability' || $filterName === 'SizeOfBoquet') {
									$filterPrice = round(($productPrice * $row['FilterPrice']) / 100);
								} else {
									$filterPrice = $row['FilterPrice'];
								}

								$arr['Filters'][$filterName][$d]['CategoryFilterID']	= $categoryFilterID;
								$arr['Filters'][$filterName][$d]['CategoryID']			= $categoryID;
								$arr['Filters'][$filterName][$d]['FilterName']			= $filterN;
								$arr['Filters'][$filterName][$d]['FilterValue']			= $row['FilterValue'];
								$arr['Filters'][$filterName][$d]['FilterPrice']			= $filterPrice;
								$arr['Filters'][$filterName][$d]['SizeInfo']			= $row['SizeInfo'];
								$arr['Filters'][$filterName][$d]['XFactor']				= $row['XFactor'];
								$arr['Filters'][$filterName][$d]['Active']				= '1';
							}
						}
						$d++;
					}

					// Check for inactive filters and add in filters list
					if($categoryID !== '92') {
						$options['sql'] 	= "SELECT * from ".FLORAL_DB.".categoryfilter WHERE CategoryID = '.$categoryID.' AND FilterName = '".$filterN."' AND IsActive='1'";

						$rs3 = $this->sqlexecute($options, 1);

						if($rs3->rowCount() > 0)
						{
							while($row = $rs3->fetch(PDO::FETCH_ASSOC))
							{
								$filterName = str_replace(' ', '', $row['FilterName']);
								
								if($filterName === 'SizeAvailability' || $filterName === 'SizeOfBoquet') {
									$filterPrice = round(($productPrice * $row['FilterPrice']) / 100);
								} else {
									$filterPrice = $row['FilterPrice'];
								}

								$arr['Filters'][$filterName][$d]['CategoryFilterID']	= $row['CategoryFilterID'];
								$arr['Filters'][$filterName][$d]['CategoryID']			= $categoryID;
								$arr['Filters'][$filterName][$d]['FilterName']			= $filterN;
								$arr['Filters'][$filterName][$d]['FilterValue']			= $row['FilterValue'];
								$arr['Filters'][$filterName][$d]['FilterPrice']			= $filterPrice;
								$arr['Filters'][$filterName][$d]['SizeInfo']			= $row['SizeInfo'];
								$arr['Filters'][$filterName][$d]['XFactor']				= $row['XFactor'];
								$arr['Filters'][$filterName][$d]['Active']				= '0';
							}
						}
					}
				}
			}
		}

		$this->data['getProductDetail'] = $arr;
	}


	public function recentProduct($d)
	{
		$arr = array();

		if(!empty($d['ProductID'])) {

			$product = explode(',', $d["ProductID"]);

			$c = 0;
			foreach ($product as $val) {
				// recent Products
				$options['sql'] = "SELECT * FROM ".FLORAL_DB.".product WHERE ProductID = ".$val." AND IsActive='1'";

				$options['barr'] 	= array(
					":ProductID"	=> $val
				);

				$rs = $this->sqlexecute($options, 1);

				if($rs->rowCount() > 0)
				{
					while($row = $rs->fetch(PDO::FETCH_ASSOC))
					{
						$productID 	= $row['ProductID'];

						$arr[$c]['ProductID']					= $productID;
						$arr[$c]['ProductName']					= htmlspecialchars($row['ProductName']);
						$arr[$c]['ProductImage']				= $row['ProductIamge'];
						$arr[$c]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
						$arr[$c]['Price']						= $row['Price'];
						$arr[$c]['Mrp']							= $row['Mrp'];
						$arr[$c]['ProductRatingCount']			= $row['ProductRatingCount'];

						// Active Whistlist products
						if($d['UserID'] != '0') {
							$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID ='".$productID."' AND UserID = '".$d['UserID']."'";
							$options['barr'] 	= array(
								":ProductID" => $productID,
								":UserID"	 => $d['UserID']
							);
						} else {
							$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID ='".$productID."' AND CartUniqueID = '".$d['CartUniqueID']."'";
							$options['barr'] 	= array(
								":ProductID" 	=> $productID,
								":CartUniqueID" => $d['CartUniqueID']
							);
						}

						$rs2 = $this->sqlexecute($options, 1);

						if($rs2->rowCount() > 0)
						{
							while($row = $rs2->fetch(PDO::FETCH_ASSOC))
							{
								$arr[$c]['ActiveWishList']	= $row['IsActive'];
							}
						} else {
							$arr[$c]['ActiveWishList']		= '0';
						}
					}
				}
				$c++;
			}
		}
	
		$this->data['recentProduct'] = $arr;
	}


	public function passwordChange($d)
	{
		$arr = array(); 

		if(trim($_REQUEST['UserID']))
			$UserID		= trim($_REQUEST['UserID']);
		else if(trim($_POST['UserID']))
			$UserID		= trim($_POST['UserID']);
		else
			$UserID		= '';

		if(trim($_REQUEST['OldPassword']))
			$OldPassword		= trim($_REQUEST['OldPassword']);
		else if(trim($_POST['OldPassword']))
			$OldPassword		= trim($_POST['OldPassword']);
		else
			$OldPassword		= '';

		if(trim($_REQUEST['Password']))
			$Password		= trim($_REQUEST['Password']);
		else if(trim($_POST['Password']))
			$Password	= trim($_POST['Password']);
		else
			$Password	= '';

		$msg		= '';


		if($OldPassword == '' && empty($msg))
		{
			$msg = 'Please enter your old password';
		}

		if($Password == '' && empty($msg))
		{
			$msg = 'Please enter new password';
		}


		if(!empty($msg))
		{
			echo json_encode(array('results' => array('msg' => $msg, 'error' => 1)));
			exit;
		}

		$md5_pwd		= trim(md5($Password));
		$md5_old_pwd	= trim(md5($OldPassword));
		
		$options['sql'] = "SELECT * from ".FLORAL_DB.".flrl_usertable WHERE ID = '".$UserID."' AND OldPassword= '".$md5_old_pwd."'";
		$options['barr'] = array(
			':email' => $d['email'],
			':ID' => $d['UserID'],
			':OldPassword' => $d['OldPassword']
		);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0) {
			$options['sql'] = "UPDATE ".FLORAL_DB.".flrl_usertable set password= '".$md5_pwd."', OldPassword= '".$md5_pwd."' WHERE ID = '".$UserID."'";

			$message = "Password changed successfully";
			$error = 0;

			$rs1 = $this->sqlexecute($options);	

			if($rs1)
			{
				$arr['msg'] = "Password changed successfully";
				$arr['error'] = $error; 
			}
		} else {
			$message = "Email ID is incorrect";
			$error = 1;

			if($rs->rowCount() == 0) {
				$message = "Old password entered is incorrect";
				$error = 1;
			}
		}

		if($rs)
		{
			$arr['msg'] = $message;
			$arr['error'] = $error;
		}

		$this->data['passwordChange'] = $arr;
	}


	public function menuListing($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".menu WHERE IsActive= '1' AND ShowInMainMenu = '1' AND CountryCode = '".$d['CountryCode']."' ORDER BY SequenceNo ASC";

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$menuID = $row['MenuID'];
				$countryCode = $row['CountryCode'];

				$arr[$i]['MenuID']				= $menuID;
				$arr[$i]['MenuName']			= strtolower($row['MenuName']);
				$arr[$i]['MenuURL']			= strtolower($row['MenuURL']);
				$arr[$i]['AnchorTarget']			= $row['AnchorTarget'];
				// $arr[$i]['DisplayOnHomePage']	= $row['DisplayOnHomePage'];
				$arr[$i]['IsGlobal']			= $row['IsGlobal'];
				$arr[$i]['CountryCode']			= $countryCode;

				$options['sql'] = "SELECT *
				FROM ".FLORAL_DB.".menusubmenumapping msm
					INNER JOIN ".FLORAL_DB.".submenu sm
						ON msm.SubMenuID = sm.SubMenuID WHERE msm.MenuID = $menuID";

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					$a = 0;
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$subMenu = $row['SubMenuID'];

						$arr[$i]['SubMenu'][$a]['SubMenuID']		= $subMenu;
						$arr[$i]['SubMenu'][$a]['SubMenuName']		= strtolower($row['SubMenuName']);

						$options['sql'] = "SELECT * FROM ".FLORAL_DB.".mastercategory mc
							WHERE mc.CountryCode = '".$d['CountryCode']."' AND mc.SubMenuID = $subMenu AND IsActive = '1'";

						$rs2 = $this->sqlexecute($options,1);

						if($rs2->rowCount() > 0)
						{
							$b = 0;
							while($row = $rs2->fetch(PDO::FETCH_ASSOC))
							{
								$arr[$i]['SubMenu'][$a]['SubMenuOfMenu'][$b]['ID']						= $row['ID'];
								$arr[$i]['SubMenu'][$a]['SubMenuOfMenu'][$b]['IsDefaultActive']			= $row['IsDefaultActive'];
								$arr[$i]['SubMenu'][$a]['SubMenuOfMenu'][$b]['Name'] 					= strtolower($row['Name']);
								$arr[$i]['SubMenu'][$a]['SubMenuOfMenu'][$b]['Description']				= $row['Description'];
								$arr[$i]['SubMenu'][$a]['SubMenuOfMenu'][$b]['ProductListPageImageURL']	= $row['ProductListPageImageURL'];
								$arr[$i]['SubMenu'][$a]['SubMenuOfMenu'][$b]['ProductListPageVideoURL']	= $row['ProductListPageVideoURL'];
								// $arr[$i]['SubMenu'][$a]['SubMenuOfMenu'][$b]['OccasionStartDate']	= $row['OccasionStartDate'];
								// $arr[$i]['SubMenu'][$a]['SubMenuOfMenu'][$b]['OccasionEndDate']		= $row['OccasionEndDate'];

								$b++;
							}	
						}
						$a++;
					}	
				}
				$i++;
			}	
		}
		$this->data['menuListing'] = $arr;
	}


	public function searchCityBasedOnCountry($d)
	{
		$arr = array();
		
		$CityName = $d["CityName"];
		
		$options['sql'] = "SELECT * from ".FLORAL_DB.".cities WHERE CityName like '%$CityName%' AND CountryCode = :CountryCode AND IsActive = '1' ORDER BY CityName ASC";
		$options['barr'] 	= array(":CountryCode" 	=> $d['CountryCode']);

		$rs = $this->sqlexecute($options, 1);
		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['CityID']    			= $row['CityID'];
				$arr[$i]['CityName']    	 	= $row['CityName'];
				$arr[$i]['StateID']    			= $row['StateID'];
				$arr[$i]['IsDefaultCity']    	= $row['IsDefaultCity'];
				
				$i++;
			}	
		}
		
		$this->data['searchCityBasedOnCountry'] = $arr;
	}

	public function fetchCityBasedOnCountry($d)
	{
		$arr = array();
		
		$CityName = $d["CityName"];
		
		$options['sql'] = "SELECT * from ".FLORAL_DB.".cities WHERE CountryCode = :CountryCode AND IsActive = '1' ORDER BY CityName ASC";
		$options['barr'] 	= array(":CountryCode" 	=> $d['CountryCode']);

		$rs = $this->sqlexecute($options, 1);
		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['CityID']    			= $row['CityID'];
				$arr[$i]['CityName']    	 	= $row['CityName'];
				$arr[$i]['StateID']    			= $row['StateID'];
				$arr[$i]['IsDefaultCity']    	= $row['IsDefaultCity'];
				
				$i++;
			}	
		}
		
		$this->data['fetchCityBasedOnCountry'] = $arr;
	}


	public function fetchCategoryFilters($d)
	{
		$arr = array();

		$options['sql'] = "SELECT DISTINCT FilterName, CategoryID FROM ".FLORAL_DB.".categoryfilter";

		if(isset($d['ProductCategoryID'])) {
			$options['sql'] .= " WHERE CategoryID = :ProductCategoryID";
			$options['barr'] 	= array(":ProductCategoryID" => $d['ProductCategoryID']);
		}

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$a = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$filterName = $row['FilterName'];
				$categoryID = $row['CategoryID'];

				$arr[$a]['FilterName']		= $filterName;
				$arr[$a]['CategoryID']		= $categoryID;

				$options['sql'] = "SELECT FilterValue, FilterPrice, CategoryFilterID FROM ".FLORAL_DB.".categoryfilter WHERE CategoryID = '".$categoryID."' AND FilterName ='".$filterName."'";
				$options['barr'] 	= array(":FilterName" => $d['FilterName'], ":CategoryID" => $d['CategoryID']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					$b = 0;
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$a]['Filters'][$b]['CategoryFilterID']		= $row['CategoryFilterID'];
						$arr[$a]['Filters'][$b]['FilterValue']			= $row['FilterValue'];
						$arr[$a]['Filters'][$b]['FilterPrice']			= $row['FilterPrice'];

						$options['sql'] = "SELECT Name FROM ".FLORAL_DB.".mastercategory WHERE ID = '".$categoryID."'";
						$options['barr'] 	= array(":ID" => $d['ID']);

						$rs2 = $this->sqlexecute($options, 1);

						if($rs2->rowCount() > 0)
						{
							while($row = $rs2->fetch(PDO::FETCH_ASSOC))
							{
								$arr[$a]['CategoryName']	= $row['Name'];
							}
						}
						$b++;
					}
				}
				$a++;
			}
		}

		$this->data['fetchCategoryFilters'] = $arr;
	}


	public function fetchAllExpressSubCatForCategory($d)
	{
		$arr = array();

		$options['sql'] = "SELECT DISTINCT ProductSubCategoryID FROM ".FLORAL_DB.".productcategorysubcategorymapping WHERE IsActive='1' ORDER BY RAND() LIMIT 20";

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$a = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$subCategoryID = $row['ProductSubCategoryID'];

				$arr[$a]['ProductCategoryID']				= $row['ProductCategoryID'];

				$options['sql']		= "SELECT * FROM ".FLORAL_DB.".productsubcategory WHERE ProductSubCategoryID ='".$subCategoryID."' AND IsActive='1' ORDER BY SequenceNo ASC";
				$options['barr'] 	= array(":ProductSubCategoryID" => $d['ProductSubCategoryID']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					$i = 0;
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$a]['ProductSubCategoryID']			= $row['ProductSubCategoryID'];
						$arr[$a]['ProductSubCategoryName']			= $row['ProductSubCategoryName'];
						$arr[$a]['ImageURL']						= $row['ImageURL'];
						$arr[$a]['IconURL']							= $row['IconURL'];
						$arr[$a]['ProductSubCategoryDescription']	= $row['ProductSubCategoryDescription'];

						$i++;
					}
				}
				$a++;
			}
		}

		$this->data['fetchAllExpressSubCatForCategory'] = $arr;
	}


	public function fetchAllSubCatForCategory($d)
	{
		$arr = array();

		$options['sql'] = "SELECT DISTINCT ProductSubCategoryID FROM ".FLORAL_DB.".productcategorysubcategorymapping WHERE ProductCategoryID = :ProductCategoryID AND IsActive='1'";
		$options['barr'] 	= array(":ProductCategoryID" => $d['ProductCategoryID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$a = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$subCategoryID = $row['ProductSubCategoryID'];

				$options['sql']		= "SELECT * FROM ".FLORAL_DB.".productsubcategory WHERE ProductSubCategoryID ='".$subCategoryID."' AND IsActive='1' ORDER BY SequenceNo ASC";
				$options['barr'] 	= array(":ProductSubCategoryID" => $d['ProductSubCategoryID']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					$i = 0;
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$a]['ProductSubCategoryID']			= $row['ProductSubCategoryID'];
						$arr[$a]['ProductSubCategoryName']			= $row['ProductSubCategoryName'];
						$arr[$a]['ImageURL']						= $row['ImageURL'];
						$arr[$a]['IconURL']							= $row['IconURL'];
						$arr[$a]['ProductSubCategoryDescription']	= $row['ProductSubCategoryDescription'];

						$i++;
					}
				}
				$a++;
			}
		}

		$this->data['fetchAllSubCatForCategory'] = $arr;
	}


	public function fetchStateForCity($d)
	{
		$arr = array();

		$options['sql'] = "SELECT StateID FROM ".FLORAL_DB.".cities WHERE CityID = :CityID";
		$options['barr'] 	= array(":CityID" => $d['CityID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$stateID = $row['StateID'];

				$options['sql']		= "SELECT StateName, CountryCode FROM ".FLORAL_DB.".states WHERE StateID ='".$stateID."'";
				$options['barr'] 	= array(":StateID" => $d['StateID']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$countryCode = $row['CountryCode'];

						$arr['StateName']		= $row['StateName'];

						$options['sql']		= "SELECT CountryName, CountryCode FROM ".FLORAL_DB.".countries WHERE CountryCode ='".$countryCode."'";
						$options['barr'] 	= array(":CountryCode" => $d['CountryCode']);

						$rs2 = $this->sqlexecute($options, 1);

						if($rs2->rowCount() > 0)
						{
							while($row = $rs2->fetch(PDO::FETCH_ASSOC))
							{
								$arr['CountryName']		= $row['CountryName'];
								$arr['CountryCode']		= $row['CountryCode'];
							}
						}
					}
				}
			}
		}

		$this->data['fetchStateForCity'] = $arr;
	}


	public function fetchFilteredCustomListingCount($d)
	{
		$arr = array();
		$limit = 32;
		$page = 1;
		$flag = 0;

		if($d['page'] > 1)
		{
			$start 	= (($d['page'] - 1) * $limit);
			$page 	= $d['page'];
		}
		else
		{
			$start = 0;
		}

		$options['sql'] = "SELECT * FROM ".FLORAL_DB.".productcitymapping pcm";


		if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID'])) {
			$options['sql'] .= " INNER JOIN ".FLORAL_DB.".productcategorysubcategorymapping pcsm ON pcm.ProductID = pcsm.ProductID";
		}

		$options['sql'] .= " INNER JOIN ".FLORAL_DB.".product p ON pcm.ProductID = p.ProductID";

		if(isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
			$options['sql'] .= " INNER JOIN ".FLORAL_DB.".productsubcategory psc ON psc.ProductSubCategoryName = :ProductSubCategoryName";
		}

		$options['sql'] .= " WHERE pcm.CityID = :CityID AND p.IsActive='1'";

		if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID'])) {
			$options['sql'] .= " AND pcsm.IsActive='1' AND pcsm.ProductCategoryID = :ProductCategoryID";
		}

		if(isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
			$options['sql'] .= " AND psc.ProductSubCategoryID = pcsm.ProductSubCategoryID";
		}

		if(isset($d["minimum_price"], $d["maximum_price"]) && !empty($d["minimum_price"]) && !empty($d["maximum_price"]))
		{
			$options['sql'] .= " AND Price BETWEEN '".$d["minimum_price"]."' AND '".$d["maximum_price"]."'";

			if($d["minimum_price"] > 300 || $d["maximum_price"] < 30000) {
				$flag = 1;
			}
		}

		if(isset($d["oneDay"]) && !empty($d["oneDay"]))
		{
			$options['sql'] .= " AND isOneDayDelivery= '1'";
		}

		if(isset($d["filter_items"]) && !empty($d["filter_items"]))
		{
			$filters = explode(',', $d["filter_items"]);
			foreach ($filters as $val) {
				$options['sql'] .= " AND FilterIDs REGEXP '[[:<:]]".$val."[[:>:]]'";
			}
		}

		if(isset($d['list-type']) && $d['list-type'] === 'express-delivery') {
			$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]7[[:>:]]' GROUP BY p.ProductID";
		} elseif (isset($d['list-type']) && $d['list-type'] === 'midnight-delivery') {
			$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]8[[:>:]]' GROUP BY p.ProductID";
		} elseif (isset($d['list-type']) && $d['list-type'] === 'sameday-delivery') {
			$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]2[[:>:]]' GROUP BY p.ProductID";
		} elseif (isset($d['MenuName']) && $d['MenuName'] === 'combo') {
			$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]3[[:>:]]'";
		}

		if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && empty($d['list-type'])) {
			$options['sql'] .= " GROUP BY pcm.ProductID";
		}

		if(isset($d["sort"]) && !empty($d["sort"]))
		{
			if($d["sort"] === 'priceASC') {
				$options['sql'] .= " ORDER BY p.Price ASC";
			}
			if($d["sort"] === 'priceDESC') {
				$options['sql'] .= " ORDER BY p.Price DESC";
			}
			if($d["sort"] === 'nameASC') {
				$options['sql'] .= " ORDER BY p.ProductName ASC";
			}
			if($d["sort"] === 'nameDESC') {
				$options['sql'] .= " ORDER BY p.ProductName DESC";
			}
			if($d["sort"] === 'bestSeller') {
				$options['sql'] .= " ORDER BY p.SoldCount DESC";
			}
		}

		if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
			$options['barr'] = array(
				":CityID"					=> $d['CityID'],
				":ProductCategoryID"		=> $d['ProductCategoryID'],
				":ProductSubCategoryName"	=> $d['ProductSubCategoryName']
			);
		}
		elseif(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && empty($d['ProductSubCategoryName'])) {
			$options['barr'] = array(":CityID"	=> $d['CityID'], ":ProductCategoryID"	=> $d['ProductCategoryID']);
		}
		else {
			$options['barr'] = array(":CityID"	=> $d['CityID']);
		}

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$b = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$b++;

				$arr['TotalProducts']		= $b;
				$arr['TotalLinks']			= ceil($rs->rowCount() / $limit);

				$arr['Page']				= $page;
				$arr['Start']				= $start;
			}

			$output = '';

			$output = '<br /><ul class="c-pagination">';

			$total_links = $arr['TotalLinks'];
			$page = $arr['Page'];
			$previous_link = '';
			$next_link = '';
			$page_link = '';

			// echo $total_links;

			if($total_links > 7)
			{
				if($page < 8)
				{
					for($count = 1; $count <= 8; $count++)
					{
						$page_array[] = $count;
					}
					$page_array[] = '...';
					$page_array[] = $total_links;
				}
				else
				{
					$end_limit = $total_links - 8;
					if($page > $end_limit)
					{
						$page_array[] = 1;
						$page_array[] = '...';
						for($count = $end_limit; $count <= $total_links; $count++)
						{
							$page_array[] = $count;
						}
					}
					else
					{
						$page_array[] = 1;
						$page_array[] = '...';
						for($count = $page - 1; $count <= $page + 1; $count++)
						{
							$page_array[] = $count;
						}
						$page_array[] = '...';
						$page_array[] = $total_links;
					}
				}
			}
			else
			{
				for($count = 1; $count <= $total_links; $count++)
				{
					$page_array[] = $count;
				}
			}

			for($count = 0; $count < count($page_array); $count++)
			{
				if($page == $page_array[$count])
				{
					$page_link .= '<li class="c-page-item active"><a class="c-page-link" href="javascript:void(0)">'.$page_array[$count].' <span class="sr-only">(current)</span></a></li>';

					$previous_id = $page_array[$count] - 1;
					if($previous_id > 0)
					{
						$previous_link = '<li class="c-page-item"><a class="c-page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'"><i class="fa fa-angle-left"></i></a></li>';
					}
					else
					{
						$previous_link = '<li class="c-page-item disabled"><a class="c-page-link" href="javascript:void(0)"><i class="fa fa-angle-left"></i></a></li>';
					}
					$next_id = $page_array[$count] + 1;
					if($next_id >= $total_links)
					{
						$next_link = '<li class="c-page-item disabled"><a class="c-page-link" href="javascript:void(0)"><i class="fa fa-angle-right"></i></a></li>';
					}
					else
					{
						$next_link = '<li class="c-page-item"><a class="c-page-link" href="javascript:void(0)" data-page_number="'.$next_id.'"><i class="fa fa-angle-right"></i></a></li>';
					}
				}
				else
				{
					if($page_array[$count] == '...')
					{
						$page_link .= '<li class="c-page-item disabled"><a class="c-page-link" href="javascript:void(0)">...</a></li>';
					}
					else
					{
						$page_link .= '<li class="c-page-item"><a class="c-page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>';
					}
				}
			}

			$output .= $previous_link . $page_link . $next_link;
			$output .= '</ul>';

			//echo $output;


			$arr['PaginationHtml']		= $output;
		}
		$this->data['fetchFilteredCustomListingCount'] = $arr;
	}


	public function fetchFilteredCustomListing($d)
	{
		$arr = array();
		$limit = 32;
		$page = 1;
		$flag = 0;

		if($d['page'] > 1)
		{
			$start 	= (($d['page'] - 1) * $limit);
			$page 	= $d['page'];
		}
		else
		{
			$start = 0;
		}

		$options['sql'] = "SELECT * FROM ".FLORAL_DB.".productcitymapping pcm";

		//if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID'])) {
			$options['sql'] .= " INNER JOIN ".FLORAL_DB.".productcategorysubcategorymapping pcsm ON pcm.ProductID = pcsm.ProductID";
		//}

		$options['sql'] .= " INNER JOIN ".FLORAL_DB.".product p ON pcm.ProductID = p.ProductID";

		if(isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
			$options['sql'] .= " INNER JOIN ".FLORAL_DB.".productsubcategory psc ON psc.ProductSubCategoryName = :ProductSubCategoryName";
		}

		$options['sql'] .= " WHERE pcm.CityID = :CityID AND p.IsActive='1'";

		if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID'])) {
			$options['sql'] .= " AND pcsm.IsActive='1' AND pcsm.ProductCategoryID = :ProductCategoryID";
		}

		if(isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
			$options['sql'] .= " AND psc.ProductSubCategoryID = pcsm.ProductSubCategoryID";
		}

		if(isset($d["minimum_price"], $d["maximum_price"]) && !empty($d["minimum_price"]) && !empty($d["maximum_price"]))
		{
			$options['sql'] .= " AND p.Price BETWEEN '".$d["minimum_price"]."' AND '".$d["maximum_price"]."'";

			if($d["minimum_price"] > 300 || $d["maximum_price"] < 30000) {
				$flag = 1;
			}
		}

		if(isset($d["oneDay"]) && !empty($d["oneDay"]))
		{
			$flag = 1;

			$options['sql'] .= " AND p.isOneDayDelivery= '1'";
		}

		if(isset($d["filter_items"]) && !empty($d["filter_items"]))
		{
			$flag = 1;

			$filters = explode(',', $d["filter_items"]);
			foreach ($filters as $val) {
				$options['sql'] .= " AND p.FilterIDs REGEXP '[[:<:]]".$val."[[:>:]]'";
			}
		}

		if(isset($d['list-type']) && $d['list-type'] === 'express-delivery') {
			$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]7[[:>:]]' GROUP BY p.ProductID";
		} elseif (isset($d['list-type']) && $d['list-type'] === 'midnight-delivery') {
			$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]8[[:>:]]' GROUP BY p.ProductID";
		} elseif (isset($d['list-type']) && $d['list-type'] === 'sameday-delivery') {
			$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]2[[:>:]]' GROUP BY p.ProductID";
		} elseif (isset($d['MenuName']) && $d['MenuName'] === 'combo') {
			$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]3[[:>:]]'";
		}

		if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && empty($d['list-type'])) {
			$options['sql'] .= " GROUP BY pcm.ProductID";
		}

		if(isset($d["sort"]) && !empty($d["sort"]))
		{
			if($d["sort"] === 'priceASC') {
				$options['sql'] .= " ORDER BY p.Price ASC";
			}
			if($d["sort"] === 'priceDESC') {
				$options['sql'] .= " ORDER BY p.Price DESC";
			} 
			if($d["sort"] === 'nameASC') {
				$options['sql'] .= " ORDER BY p.ProductName ASC";
			} 
			if($d["sort"] === 'nameDESC') {
				$options['sql'] .= " ORDER BY p.ProductName DESC";
			} 
			if($d["sort"] === 'bestSeller') {
				$options['sql'] .= " ORDER BY p.SoldCount DESC";
			}
		}

		$options['sql'] .= " LIMIT $limit OFFSET $start";

		if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
			$options['barr'] = array(
				":CityID"					=> $d['CityID'],
				":ProductCategoryID"		=> $d['ProductCategoryID'],
				":ProductSubCategoryName"	=> $d['ProductSubCategoryName']
			);
		}
		elseif(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && empty($d['ProductSubCategoryName'])) {
			$options['barr'] = array(":CityID"	=> $d['CityID'], ":ProductCategoryID"	=> $d['ProductCategoryID']);
		}
		else {
			$options['barr'] = array(":CityID"	=> $d['CityID']);
		}

		$rs3 = $this->sqlexecute($options, 1);

		if($rs3->rowCount() > 0)
		{
			$a = 0;

			while($row = $rs3->fetch(PDO::FETCH_ASSOC))
			{

				$productID = $row['ProductID'];
				$productCategoryID = $row['ProductCategoryID'];

				$options['sql'] = "SELECT DISTINCT * FROM ".FLORAL_DB.".product WHERE ProductID=".$productID." AND IsActive='1'";

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{

						$productID = $row['ProductID'];

						$arr['Page']				= $page;
						$arr['Start']				= $start;

						$arr['Products'][$a]['ProductName']					= htmlspecialchars($row['ProductName']);
						$arr['Products'][$a]['ProductCategoryID']			= $productCategoryID;
						$arr['Products'][$a]['ProductID']					= $productID;
						$arr['Products'][$a]['ProductIamge']				= $row['ProductIamge'];
						$arr['Products'][$a]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
						$arr['Products'][$a]['Price']						= $row['Price'];
						$arr['Products'][$a]['Mrp']							= $row['Mrp'];
						$arr['Products'][$a]['isOneDayDelivery']			= $row['isOneDayDelivery'];
						$arr['Products'][$a]['IsDeliveryTimeRestricted']	= $row['IsDeliveryTimeRestricted'];
						$arr['Products'][$a]['FilterIDs']					= $row['FilterIDs'];
						$arr['Products'][$a]['ProductRating']				= $row['ProductRating'];
						$arr['Products'][$a]['ProductRatingCount']			= $row['ProductRatingCount'];


						// Active Whistlist products
						if($d['UserID'] != '0') {
							$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND UserID = '".$d['UserID']."'";
						} else {
							$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND CartUniqueID = '".$d['CartUniqueID']."'";
						}

						$rs2 = $this->sqlexecute($options, 1);

						if($rs2->rowCount() > 0)
						{
							$arr['Products'][$a]['ActiveWishList']	= $row['IsActive'];
						} else {
							$arr['Products'][$a]['ActiveWishList']	= '0';
						}
					}
				}
				$a++;
			}
		}
		$this->data['fetchFilteredCustomListing'] = $arr;
	}


	public function listingPageInfo($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".mastercategory WHERE ID= :ProductCategoryID AND IsActive='1'";
		$options['barr'] 	= array(":ProductCategoryID" => $d['ProductCategoryID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr['ProductSubCategoryID']		= $row['ProductSubCategoryID'];
				$arr['Name']						= $row['Name'];
				$arr['DesktopImageURL']				= $row['DesktopImageURL'];
				$arr['MobileImageURL']				= $row['MobileImageURL'];
				$arr['ProductListPageImageURL']		= $row['ProductListPageImageURL'];
				$arr['ProductListPageVideoURL']		= $row['ProductListPageVideoURL'];
				$arr['Logo']						= $row['Logo'];
				$arr['SeoTitle']					= $row['SeoTitle'];
				$arr['SeoMetaKeywords']				= $row['SeoMetaKeywords'];
				$arr['SeoMetaDescription']			= $row['SeoMetaDescription'];
			}
		}

		$this->data['listingPageInfo'] = $arr;
	}


	public function customCategoryListing($d)
	{
		$arr = array();
		$limit = 32;
		$page = 1;

		if($d['page'] > 1)
		{
			$start 	= (($d['page'] - 1) * $limit);
			$page 	= $d['page'];
		}
		else
		{
			$start = 0;
		}

		if(!empty($d['CityID'])) {

			$options['sql'] = "SELECT * FROM ".FLORAL_DB.".productcitymapping pcm";

			if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID'])) {
				$options['sql'] .= " INNER JOIN ".FLORAL_DB.".productcategorysubcategorymapping pcsm ON pcm.ProductID = pcsm.ProductID";
			}

			$options['sql'] .= " INNER JOIN ".FLORAL_DB.".product p ON pcm.ProductID = p.ProductID";

			if(isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
				$options['sql'] .= " INNER JOIN ".FLORAL_DB.".productsubcategory psc ON psc.ProductSubCategoryName = :ProductSubCategoryName";
			}

			$options['sql'] .= " WHERE pcm.CityID = :CityID AND p.IsActive='1'";

			if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID'])) {
				$options['sql'] .= " AND pcsm.IsActive='1' AND pcsm.ProductCategoryID = :ProductCategoryID";
			}

			if(isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
				$options['sql'] .= " AND psc.ProductSubCategoryID = pcsm.ProductSubCategoryID";
			}

			if(isset($d['list-type']) && $d['list-type'] === 'express-delivery') {
				$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]7[[:>:]]' GROUP BY p.ProductID";
			} elseif (isset($d['list-type']) && $d['list-type'] === 'midnight-delivery') {
				$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]8[[:>:]]' GROUP BY p.ProductID";
			} elseif (isset($d['list-type']) && $d['list-type'] === 'sameday-delivery') {
				$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]2[[:>:]]' GROUP BY p.ProductID";
			} elseif (isset($d['MenuName']) && $d['MenuName'] === 'combo') {
				$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]3[[:>:]]'";
			}

			if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && empty($d['list-type'])) {
				$options['sql'] .= " GROUP BY pcm.ProductID";
			}

			if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
				$options['barr'] = array(
					":CityID"					=> $d['CityID'],
					":ProductCategoryID"		=> $d['ProductCategoryID'],
					":ProductSubCategoryName"	=> $d['ProductSubCategoryName']
				);
			}
			elseif(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && empty($d['ProductSubCategoryName'])) {
				$options['barr'] = array(":CityID"	=> $d['CityID'], ":ProductCategoryID"	=> $d['ProductCategoryID']);
			}
			else {
				$options['barr'] = array(":CityID"	=> $d['CityID']);
			}

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				$arr['TotalProducts']		= $rs->rowCount();
				$arr['TotalLinks']			= ceil($rs->rowCount() / $limit);
			}

			$options['sql'] = "SELECT * FROM ".FLORAL_DB.".productcitymapping pcm";

			$options['sql'] .= " INNER JOIN ".FLORAL_DB.".productcategorysubcategorymapping pcsm ON pcm.ProductID = pcsm.ProductID";

			$options['sql'] .= " INNER JOIN ".FLORAL_DB.".product p ON pcm.ProductID = p.ProductID";

			if(isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
				$options['sql'] .= " INNER JOIN ".FLORAL_DB.".productsubcategory psc ON psc.ProductSubCategoryName = :ProductSubCategoryName";
			}

			$options['sql'] .= " WHERE pcm.CityID = :CityID AND p.IsActive='1'";

			if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID'])) {
				$options['sql'] .= " AND pcsm.IsActive='1' AND pcsm.ProductCategoryID = :ProductCategoryID";
			}

			if(isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
				$options['sql'] .= " AND psc.ProductSubCategoryID = pcsm.ProductSubCategoryID";
			}


			if(isset($d['list-type']) && $d['list-type'] === 'express-delivery') {
				$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]7[[:>:]]' GROUP BY p.ProductID";
			} elseif (isset($d['list-type']) && $d['list-type'] === 'midnight-delivery') {
				$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]8[[:>:]]' GROUP BY p.ProductID";
			} elseif (isset($d['list-type']) && $d['list-type'] === 'sameday-delivery') {
				$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]2[[:>:]]' GROUP BY p.ProductID";
			} elseif (isset($d['MenuName']) && $d['MenuName'] === 'combo') {
				$options['sql'] .= " AND p.MenuIDs REGEXP '[[:<:]]3[[:>:]]'";
			}

			if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && empty($d['list-type'])) {
				$options['sql'] .= " GROUP BY pcm.ProductID";
			}

			$options['sql'] .= " LIMIT $limit OFFSET $start";

			if(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && isset($d['ProductSubCategoryName']) && !empty($d['ProductSubCategoryName'])) {
				$options['barr'] = array(
					":CityID"					=> $d['CityID'],
					":ProductCategoryID"		=> $d['ProductCategoryID'],
					":ProductSubCategoryName"	=> $d['ProductSubCategoryName']
				);
			}
			elseif(isset($d['ProductCategoryID']) && !empty($d['ProductCategoryID']) && empty($d['ProductSubCategoryName'])) {
				$options['barr'] = array(":CityID"	=> $d['CityID'], ":ProductCategoryID"	=> $d['ProductCategoryID']);
			}
			else {
				$options['barr'] = array(":CityID"	=> $d['CityID']);
			}

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				$b = 1;

				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$productID 			= $row['ProductID'];
					$city 				= $row['CityID'];

					$arr['Products'][$b]['ProductCategoryID']	= $row['ProductCategoryID'];
					$arr['Products'][$b]['id']	= $productID;

					// Category products
					$options['sql'] = "SELECT * FROM ".FLORAL_DB.".product WHERE ProductID = ".$productID." AND IsActive='1'";

					$rs2 = $this->sqlexecute($options, 1);

					if($rs2->rowCount() > 0)
					{
						while($row = $rs2->fetch(PDO::FETCH_ASSOC))
						{
							$arr['Page']				= $page;
							$arr['Start']				= $start;

							$filterIDs = $row['FilterIDs'];

							$arr['Products'][$b]['ProductName']					= htmlspecialchars($row['ProductName']);
							$arr['Products'][$b]['ProductID']					= $productID;
							$arr['Products'][$b]['CityID']						= $city;
							$arr['Products'][$b]['ProductIamge']				= $row['ProductIamge'];
							$arr['Products'][$b]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
							$arr['Products'][$b]['Price']						= $row['Price'];
							$arr['Products'][$b]['Mrp']							= $row['Mrp'];
							$arr['Products'][$b]['isOneDayDelivery']			= $row['isOneDayDelivery'];
							$arr['Products'][$b]['IsDeliveryTimeRestricted']	= $row['IsDeliveryTimeRestricted'];
							$arr['Products'][$b]['FilterIDs']					= $filterIDs;
							$arr['Products'][$b]['ProductRating']				= $row['ProductRating'];
							$arr['Products'][$b]['ProductRatingCount']			= $row['ProductRatingCount'];


							// Active Whistlist products
							if($d['UserID'] != '0') {
								$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND UserID = '".$d['UserID']."'";
							} else {
								$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND CartUniqueID = '".$d['CartUniqueID']."'";
							}

							$rs3 = $this->sqlexecute($options, 1);

							if($rs3->rowCount() > 0)
							{
								$arr['Products'][$b]['ActiveWishList']	= $row['IsActive'];
							} else {
								$arr['Products'][$b]['ActiveWishList']	= '0';
							}

							// Fetch filters
							$filters = $filterIDs; 
							$filters_arr = explode("|", $filters);

							$c = 0;
							foreach ($filters_arr as $val) {
								$options['sql'] = "SELECT FilterValue FROM ".FLORAL_DB.".categoryfilter WHERE CategoryFilterID = '".$val."'";

								$rs4 = $this->sqlexecute($options, 1);

								if($rs4->rowCount() > 0)
								{
									while($row = $rs4->fetch(PDO::FETCH_ASSOC))
									{
										$arr['Products'][$b]['Filters'][$c]['FilterValue']	= $row['FilterValue'];
									}
								}
								$c++;
							}
						}
					}
					$b++;
				}
			}
		}
		$this->data['customCategoryListing'] = $arr;
	}


	public function specialProducts($d)
	{
		if(!empty($d['CityID'])) {

			$options['sql'] = "SELECT *
			FROM ".FLORAL_DB.".productcitymapping pcm
				INNER JOIN ".FLORAL_DB.".product p
					ON pcm.ProductID = p.ProductID

				INNER JOIN ".FLORAL_DB.".productcategorysubcategorymapping pcsm
					ON p.ProductID = pcsm.ProductID
					
					WHERE pcm.CityID = :CityID AND p.IsActive='1' AND pcsm.ProductCategoryID = :ProductCategoryID GROUP BY p.ProductID";

			if(isset($d['bs'])) {
				$options['sql'] .= " ORDER BY p.SoldCount DESC";
			} elseif (isset($d['tr'])) {
				$options['sql'] .= " ORDER BY p.createdDate DESC";
			} elseif (isset($d['lb'])) {
				$options['sql'] .= " ORDER BY p.price ASC";
			}

			$options['sql'] .= " LIMIT 16";

			$options['barr'] 	= array(
				":CityID"				=> $d['CityID'],
				":ProductCategoryID"	=> $d['ProductCategoryID'] == '' ? '91' : $d['ProductCategoryID'] // pass default category flower(91) if its empty
			);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				$b = 1;

				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$productID 	= $row['ProductID'];
					$city 		= $row['CityID'];
					$arr[$b]['ProductCategoryID']			= $row['ProductCategoryID'];
					$arr[$b]['ProductSubCategoryID']		= $row['ProductSubCategoryID'];

					// Category products
					$options['sql'] = "SELECT * FROM ".FLORAL_DB.".product WHERE ProductID = ".$productID." AND IsActive='1'";

					$rs2 = $this->sqlexecute($options, 1);

					if($rs2->rowCount() > 0)
					{
						while($row = $rs2->fetch(PDO::FETCH_ASSOC))
						{

							$filterIDs = $row['FilterIDs'];

							$arr[$b]['ProductName']					= htmlspecialchars($row['ProductName']);
							$arr[$b]['ProductID']					= $productID;
							$arr[$b]['CityID']						= $city;
							$arr[$b]['ProductIamge']				= $row['ProductIamge'];
							$arr[$b]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
							$arr[$b]['Price']						= $row['Price'];
							$arr[$b]['Mrp']							= $row['Mrp'];
							$arr[$b]['isOneDayDelivery']			= $row['isOneDayDelivery'];
							$arr[$b]['IsDeliveryTimeRestricted']	= $row['IsDeliveryTimeRestricted'];
							$arr[$b]['FilterIDs']					= $filterIDs;
							$arr[$b]['ProductRating']				= $row['ProductRating'];
							$arr[$b]['ProductRatingCount']			= $row['ProductRatingCount'];
							$arr[$b]['SoldCount']					= $row['SoldCount'];


							// Active Whistlist products
							if($d['UserID'] != '0') {
								$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND UserID = '".$d['UserID']."'";
							} else {
								$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND CartUniqueID = '".$d['CartUniqueID']."'";
							}

							$rs3 = $this->sqlexecute($options, 1);

							if($rs3->rowCount() > 0)
							{
								$arr[$b]['ActiveWishList']	= $row['IsActive'];
							} else {
								$arr[$b]['ActiveWishList']	= '0';
							}

						}
					}
					$b++;
				}
			}
		}
		$this->data['specialProducts'] = $arr;
	}


	public function updateCartUniqueIDByUserID($d)
	{
		$arr = array();

		if(!empty($d['UserID']) && !empty($d['CartUniqueID']))
		{
			$options['barr'] = array(
				":UserID"			=> $d['UserID'],
				":CartUniqueID"		=> $d['CartUniqueID']
			);	

			$options['sql'] = "UPDATE ".FLORAL_DB.".flrl_cartdetails SET UserID = :UserID WHERE CartUniqueID = :CartUniqueID";
			$options['barr'][":CartUniqueID"] = $d['CartUniqueID'];
		}

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "User ID updated with reference to CartUniqueID";
			$arr['error'] = 0;

			$options['barr'] = array(
				":Blank"		=> $d['Blank']
			);

			$options['sql'] = "UPDATE ".FLORAL_DB.".flrl_cartdetails SET CartUniqueID = :Blank WHERE UserID = :UserID";
			$options['barr'][":UserID"] = $d['UserID'];

			$rs1 = $this->sqlexecute($options);

			if($rs1)
			{
				$arr['update'] = 0;
			}

			
		}
		$this->data['updateCartUniqueIDByUserID'] = $arr; 
	}


	public function updateUserDetails($d)
	{
		$arr = array();

		if(!empty($d['ID']) && isset($d['ID']))
		{
			$options['barr'] = array(
				":ID"				=> $d['ID'],
				":editFirstname"	=> $d['editFirstname'],
				":editLastname"		=> $d['editLastname'],
				":editEmail"		=> $d['editEmail'],
				":editMobile"		=> $d['editMobile'],
				":editGender"		=> $d['editGender'],
				":editDOB"			=> $d['editDOB']
			);	

			$options['sql'] = "UPDATE ".FLORAL_DB.".flrl_usertable SET FirstName =:editFirstname, LastName =:editLastname, Email =:editEmail, MobileNo =:editMobile, Gender =:editGender, DOB =:editDOB, UpdatedDate = NOW() WHERE ID =:ID";
			$options['barr'][":ID"] = $d['ID'];
		}

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "User profile updated";
			$arr['error'] = 0;
		}
		$this->data['updateUserDetails'] = $arr; 
	}


	public function addUserImage($d)
	{
		$arr = array();

		if(!empty($d['ID']) && isset($d['ID']))
		{
			$options['barr'] = array(
				":ID"				=> $d['ID'],
				":ProfileImage"		=> $d['ProfileImage']
			);	

			$options['sql'] = "UPDATE ".FLORAL_DB.".flrl_usertable SET ProfileImage =:ProfileImage, UpdatedDate = NOW() WHERE ID =:ID";
			$options['barr'][":ID"] = $d['ID'];
		}

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "User profile picture updated";
			$arr['error'] = 0;
		}
		$this->data['addUserImage'] = $arr; 
	}


	public function editCart($d)
	{
		$arr = array();

		$options['barr'] = array(
			":CartID" 			=> $d['CartID'],
			":ProductQty" 		=> $d['ProductQty'],
			":SenderMessage" 	=> $d['SenderMessage']
		);

		if(!empty($d['CartID']) && !empty($d['ProductQty']))
		{
			$options['sql'] ="UPDATE ".FLORAL_DB.".flrl_cartdetails SET ProductQty = :ProductQty, SenderMessage = :SenderMessage WHERE CartID = :CartID";
		}

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$options['sql'] 	= "SELECT * FROM ".FLORAL_DB.".flrl_cartdetails WHERE CartID = :CartID";
			$options['barr'] 	= array(":CartID" => $d['CartID']);

			$rs1 = $this->sqlexecute($options, 1);

			if($rs1->rowCount() > 0)
			{
				while($row = $rs1->fetch(PDO::FETCH_ASSOC))
				{
					$arr['Mrp']					= $row['Mrp'];
					$arr['Price']				= $row['Price'];
					$arr['ProductSizePrice']	= $row['ProductSizePrice'];
				}
			}

			$arr['msg'] = "Cart updated";
			$arr['error'] = 0;
		}
		$this->data['editCart'] = $arr; 
	}


	public function fetchCartByID($d)
	{
		$arr = array();

		$options['sql']		= "SELECT * FROM ".FLORAL_DB.".flrl_cartdetails WHERE City ='".$d['City']."' AND ParentCartID = '0'";

		if(isset($d["CartUniqueID"]) && !empty($d["CartUniqueID"])) {
			$options['sql'] .= " AND CartUniqueID = :CartUniqueID";
			$options['barr'] = array(":CartUniqueID" => $d['CartUniqueID']);
		}
		if(isset($d["UserID"]) && !empty($d["UserID"])) {
			$options['sql'] .= " AND UserID = :UserID";
			$options['barr'] = array(":UserID" => $d['UserID']);
		}

		$options['sql'] .= " ORDER BY CreatedDate ASC";

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$productID 		= $row['ProductID'];
				$parentCartID 	= $row['ParentCartID'];
				$cartID 		= $row['CartID'];

				$arr[$i]['CartID']				= $cartID;
				$arr[$i]['ParentCartID']		= $parentCartID;
				$arr[$i]['ProductID']			= $productID;
				$arr[$i]['ProductName']			= htmlspecialchars($row['ProductName']);
				$arr[$i]['CartUniqueID']		= $row['CartUniqueID'];
				$arr[$i]['Price']				= $row['Price'];
				$arr[$i]['Mrp']					= $row['Mrp'];
				$arr[$i]['ProductSizePrice']	= $row['ProductSizePrice'];
				$arr[$i]['PackingPrice']		= $row['PackingPrice'];
				$arr[$i]['TimeSlotCharges']		= $row['TimeSlotCharges'];
				$arr[$i]['ProductIamge']		= $row['ProductIamge'];
				$arr[$i]['photoImage']			= $row['photoImage'];
				$arr[$i]['ProductQty']			= $row['ProductQty'];
				$arr[$i]['DeliveryTimeSlot']	= $row['DeliveryTimeSlot'];
				$arr[$i]['DeliveryTimeText']	= $row['DeliveryTimeText'];
				$arr[$i]['DeliveryDate']		= $row['DeliveryDate'];
				$arr[$i]['Size']				= $row['Size'];
				$arr[$i]['Feature']				= $row['Feature'];
				$arr[$i]['Type']				= $row['Type'];
				$arr[$i]['CustomType']			= $row['CustomType'];
				$arr[$i]['CaptionMessage']		= $row['CaptionMessage'];
				$arr[$i]['SenderMessage']		= $row['SenderMessage'];
				$arr[$i]['SenderName']			= $row['SenderName'];
				$arr[$i]['AnonymousPerson']		= $row['AnonymousPerson'];
				$arr[$i]['AddressSelectedID']	= $row['AddressSelectedID'];
				$arr[$i]['HSN']					= $row['HSN'];
				$arr[$i]['GSTPercent']			= $row['GSTPercent'];
				$arr[$i]['City']				= $row['City'];
				$arr[$i]['CreatedDate']			= $row['CreatedDate'];
				$arr[$i]['UpdatedDate']			= $row['UpdatedDate'];


				$options['sql'] 	= "SELECT ProductShortDescription from ".FLORAL_DB.".product WHERE ProductID =".$productID;
				$options['barr'] 	= array(":ProductID" => $d['ProductID']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$i]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);

						$options['sql'] 	= "SELECT CityID from ".FLORAL_DB.".productcitymapping WHERE ProductID =".$productID;
						$options['barr'] 	= array(":ProductID" => $d['ProductID']);

						$rs2 = $this->sqlexecute($options, 1);

						if($rs2->rowCount() > 0)
						{
							$b = 0;
							while($row = $rs2->fetch(PDO::FETCH_ASSOC))
							{
								$cityID = $row['CityID'];

								$arr[$i]['Cities'][$b]['CityID']		= $cityID;

								$options['sql'] 	= "SELECT CityName from ".FLORAL_DB.".cities WHERE CityID =".$cityID;
								$options['barr'] 	= array(":CityID" => $d['CityID']);

								$rs3 = $this->sqlexecute($options, 1);

								if($rs3->rowCount() > 0)
								{
									while($row = $rs3->fetch(PDO::FETCH_ASSOC))
									{
										$arr[$i]['Cities'][$b]['CityName']		= $row['CityName'];
									}
								}

								$b++;
							}
						}
					}
				}


				$options['sql']		= "SELECT * FROM ".FLORAL_DB.".flrl_cartdetails WHERE City ='".$d['City']."' AND ParentCartID ='".$cartID."'";

				if(isset($d["CartUniqueID"]) && !empty($d["CartUniqueID"])) {
					$options['sql'] .= " AND CartUniqueID = :CartUniqueID";
					$options['barr'] = array(":CartUniqueID" => $d['CartUniqueID']);
				}
				if(isset($d["UserID"]) && !empty($d["UserID"])) {
					$options['sql'] .= " AND UserID = :UserID";
					$options['barr'] = array(":UserID" => $d['UserID']);
				}

				$options['sql'] .= " ORDER BY CreatedDate ASC";

				$rs4 = $this->sqlexecute($options, 1);

				if($rs4->rowCount() > 0)
				{
					$b = 0;
					while($row = $rs4->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$i]['Addon'][$b]['CartID']				= $row['CartID'];
						$arr[$i]['Addon'][$b]['ParentCartID']		= $row['ParentCartID'];
						$arr[$i]['Addon'][$b]['ProductID']			= $productID;
						$arr[$i]['Addon'][$b]['ProductName']		= htmlspecialchars($row['ProductName']);
						$arr[$i]['Addon'][$b]['CartUniqueID']		= $row['CartUniqueID'];
						$arr[$i]['Addon'][$b]['Price']				= $row['Price'];
						$arr[$i]['Addon'][$b]['Mrp']				= $row['Mrp'];
						$arr[$i]['Addon'][$b]['ProductSizePrice']	= $row['ProductSizePrice'];
						$arr[$i]['Addon'][$b]['PackingPrice']		= $row['PackingPrice'];
						$arr[$i]['Addon'][$b]['TimeSlotCharges']	= $row['TimeSlotCharges'];
						$arr[$i]['Addon'][$b]['ProductIamge']		= $row['ProductIamge'];
						$arr[$i]['Addon'][$b]['photoImage']			= $row['photoImage'];
						$arr[$i]['Addon'][$b]['ProductQty']			= $row['ProductQty'];
						$arr[$i]['Addon'][$b]['DeliveryTimeSlot']	= $row['DeliveryTimeSlot'];
						$arr[$i]['Addon'][$b]['DeliveryTimeText']	= $row['DeliveryTimeText'];
						$arr[$i]['Addon'][$b]['DeliveryDate']		= $row['DeliveryDate'];
						$arr[$i]['Addon'][$b]['Size']				= $row['Size'];
						$arr[$i]['Addon'][$b]['Feature']			= $row['Feature'];
						$arr[$i]['Addon'][$b]['Type']				= $row['Type'];
						$arr[$i]['Addon'][$b]['CustomType']			= $row['CustomType'];
						$arr[$i]['Addon'][$b]['CaptionMessage']		= $row['CaptionMessage'];
						$arr[$i]['Addon'][$b]['SenderMessage']		= $row['SenderMessage'];
						$arr[$i]['Addon'][$b]['SenderName']			= $row['SenderName'];
						$arr[$i]['Addon'][$b]['AnonymousPerson']	= $row['AnonymousPerson'];
						$arr[$i]['Addon'][$b]['AddressSelectedID']	= $row['AddressSelectedID'];
						$arr[$i]['Addon'][$b]['HSN']				= $row['HSN'];
						$arr[$i]['Addon'][$b]['GSTPercent']			= $row['GSTPercent'];
						$arr[$i]['Addon'][$b]['City']				= $row['City'];
						$arr[$i]['Addon'][$b]['CreatedDate']		= $row['CreatedDate'];
						$arr[$i]['Addon'][$b]['UpdatedDate']		= $row['UpdatedDate'];

						$b++;
					}
				}
				$i++;
			}
		}

		$this->data['fetchCartByID'] = $arr;
	}


	public function addPhotoCakeImage($d)
	{
		$arr = array();

		if(!empty($d['CakePhotoFile']))
		{
			$arr['msg'] = "Image uploaded";
			$arr['error'] = 0;
			$arr['filename'] = $d['CakePhotoFile'];
		}

		$this->data['addPhotoCakeImage'] = $arr;
	}


	public function addonAddToCart($d)
	{
		$arr = array();

		if(($d["action"] === "addon") && !empty($d['ProductID']))
		{
			$feature = '';
			$type = '';
			$captionMessage = '';
			$customType = '';

			// $TimeSlotCharges		= $d['TimeSlotCharges'];
			// $ProductSizePrice		= $d['ProductSizePrice'];
			// $DeliveryDate			= $d['DeliveryDate'];


			// if($d['productType'] === '91' || $d['productType'] === '94' || $d['productType'] === '92'){
			// 	if(!empty($d['Feature']) && isset($d['Feature'])) {
			// 		$feature = $d['Feature'];
			// 		$type = $d['Type'];
			// 	}
			// }


			// if($d['productType'] === '92'){
			// 	if(!empty($d['CustomType']) && isset($d['CustomType'])) {
			// 		$customType = $d['CustomType'];
			// 	}
			// }

			if($d['UserID'] === '0' && isset($d['UserID'])) {
				$cartUniqueID = $d['CartUniqueID'];
			} else {
				$cartUniqueID = '';
			}

			$options['sql'] 	= "SELECT * from ".FLORAL_DB.".product WHERE ProductID = ".$d['ProductID']." AND IsActive = '1'";
			$options['barr'] 	= array(":ProductID" => $d['ProductID']);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$ProductPrice			= $row['Price'];
					$ProductMrp				= $row['Mrp'];
					$ProductImage			= $row['ProductIamge'];
					$ProductName			= $row['ProductName'];
				}
			}

			$options['sql'] 	= "SELECT * from ".FLORAL_DB.".mastercategory WHERE ID = :productType AND IsActive = '1'";
			$options['barr'] 	= array(":productType" => $d['productType']);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$GSTPercent			= $row['GSTPercent'];
					$HSN				= $row['HSN'];
				}
			}

			$options['sql'] 	= "SELECT CartID from ".FLORAL_DB.".flrl_cartdetails WHERE ProductID = '".$d['ParentProductID']."' AND ProductCategoryID = '".$d['ProductCategoryID']."' AND UserID = '".$d['UserID']."' AND DeliveryDate = '".$d['ParentDeliveryDate']."' AND TimeSlotCharges = '".$d['ParentTimeSlotCharges']."'";

			$rs1 = $this->sqlexecute($options, 1);

			if($rs1->rowCount() > 0)
			{
				while($row = $rs1->fetch(PDO::FETCH_ASSOC))
				{
					$ParentCartID		= $row['CartID'];
				}
			}

			$options['barr'] = array(
				":UserID" 				=> $d['UserID'],
				":ProductID" 			=> $d['ProductID'],
				":ParentCartID"			=> $ParentCartID,
				":ProductIamge" 		=> $ProductImage,
				":ProductName" 			=> $ProductName,
				":ProductCartType"		=> 'Addon Product',
				":photoImage" 			=> '',
				":Price" 				=> $ProductPrice,
				":Mrp" 					=> $ProductMrp,
				":ProductSizePrice" 	=> $d['ProductSizePrice'],
				":PackingPrice" 		=> !empty($d['PackingPrice']) ? $d['PackingPrice'] : 0,
				":TimeSlotCharges" 		=> $d['TimeSlotCharges'],
				":ProductQty" 			=> 1,
				":DeliveryTimeText" 	=> $d['DeliveryTimeText'],
				":DeliveryTimeSlot" 	=> $d['DeliveryTimeSlot'],
				":DeliveryDate" 		=> $d['DeliveryDate'],
				":Size" 				=> $d['Size'],
				":SenderMessage" 		=> $d['SenderMessage'],
				":Feature" 				=> $feature,
				":Type" 				=> $type,
				":SenderName" 			=> $d['SenderName'],
				":AnonymousPerson" 		=> $d['AnonymousPerson'],
				":CustomType" 			=> $customType,
				":CaptionMessage" 		=> $captionMessage,
				":CartUniqueID" 		=> $cartUniqueID,
				":HSN" 					=> $HSN,
				":GSTPercent" 			=> $GSTPercent,
				":City" 				=> $d['City'],
				":ProductCategoryID"	=> $d['ParentProductType'],
				":RecieverName"			=> $d['RecieverName']
			);

			$options['sql'] ="INSERT INTO ".FLORAL_DB.".flrl_cartdetails (UserID, ProductID, ParentCartID, ProductIamge, ProductName, ProductCartType, photoImage, Price, Mrp, ProductSizePrice, PackingPrice, TimeSlotCharges, ProductQty, DeliveryTimeText, DeliveryTimeSlot, DeliveryDate, Size, SenderMessage, SenderName, AnonymousPerson, Feature, Type, CustomType, CaptionMessage, CreatedDate, UpdatedDate, CartUniqueID, HSN, GSTPercent, City, ProductCategoryID, RecieverName) VALUES (:UserID, :ProductID, :ParentCartID, :ProductIamge, :ProductName, :ProductCartType, :photoImage, :Price, :Mrp, :ProductSizePrice, :PackingPrice, :TimeSlotCharges, :ProductQty, :DeliveryTimeText, :DeliveryTimeSlot, :DeliveryDate, :Size, :SenderMessage, :SenderName, :AnonymousPerson, :Feature, :Type, :CustomType, :CaptionMessage, NOW(), NOW(), :CartUniqueID, :HSN, :GSTPercent, :City, :ProductCategoryID, :RecieverName)";

			$rs1 = $this->sqlexecute($options);

			if($rs1)
			{
				$arr['msg'] = "Addon product added to Cart";
				$arr['error'] = 0;
			}
		}

		$this->data['addonAddToCart'] = $arr;
	}


	public function addToCart($d)
	{
		$arr = array();

		if(($d["action"] === "add" || $d["action"] === "checkout") && !empty($d['ProductID']))
		{
			$feature = '';
			$type = '';
			$captionMessage = '';
			$customType = '';
			$productPrice = '';
			$productMrp = '';
			$productImage = '';
			$productName = '';

			$TimeSlotCharges		= $d['TimeSlotCharges'];
			$ProductSizePrice		= $d['ProductSizePrice'];

			if(trim($_REQUEST['DeliveryDate']))
				$DeliveryDate		= trim($_REQUEST['DeliveryDate']);
			else if(trim($_POST['DeliveryDate']))
				$DeliveryDate		= trim($_POST['DeliveryDate']);
			else
				$DeliveryDate		= '';


			$msg			= '';

			if($DeliveryDate == '')
			{
				$msg = 'Please select delivery date';

				echo json_encode(array('results' => array('msg' => $msg, 'error' => 1)));
				exit;
			}

			if($TimeSlotCharges === '')
			{
				$msg = 'Please select your delivery time';

				echo json_encode(array('results' => array('msg' => $msg, 'error' => 1)));
				exit;
			}

			if($ProductSizePrice === '' && isset($ProductSizePrice) && $ProductSizePrice != '0')
			{
				$msg = 'Please select size';

				echo json_encode(array('results' => array('msg' => $msg, 'error' => 1)));
				exit;
			}

			if($d['productType'] === '91' || $d['productType'] === '92' || $d['productType'] === '94' || $d['productType'] === '97'){
				if(!empty($d['Feature']) && isset($d['Feature'])) {
					$feature = $d['Feature'];
					$type = $d['Type'];
				}
			}

			if($d['productType'] === '94' || $d['productType'] === '92'){
				if(!empty($d['CaptionMessage']) && isset($d['CaptionMessage'])) {
					$captionMessage = $d['CaptionMessage'];
				}
			}

			if($d['productType'] === '92'){
				if(!empty($d['CustomType']) && isset($d['CustomType'])) {
					$customType = $d['CustomType'];
				}
			}

			if($d['UserID'] === '0' && isset($d['UserID'])) {
				$cartUniqueID = $d['CartUniqueID'];
			} else {
				$cartUniqueID = '';
			}

			$options['sql'] 	= "SELECT * from ".FLORAL_DB.".mastercategory WHERE ID = :productType AND IsActive = '1'";
			$options['barr'] 	= array(":productType" => $d['productType']);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$GSTPercent			= $row['GSTPercent'];
					$HSN				= $row['HSN'];
				}
			}

			// fetch product details
			$options['sql'] 	= "SELECT * from ".FLORAL_DB.".product WHERE ProductID = :ProductID AND IsActive = '1'";
			$options['barr'] 	= array(":ProductID" => $d['ProductID']);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					$productPrice 		= $row['Price'];
					$productMrp 		= $row['Mrp'];
					$productImage 		= $row['ProductIamge'];
					$productName 		= $row['ProductName'];
				}
			}

			$options['barr'] = array(
				":UserID" 				=> $d['UserID'],
				":ProductID" 			=> $d['ProductID'],
				":ProductIamge" 		=> $productImage,
				":ProductName" 			=> $productName,
				":ProductCartType"		=> 'Normal Product',
				":photoImage" 			=> $d['photoImage'],
				":Price" 				=> $productPrice,
				":Mrp" 					=> $productMrp,
				":ProductSizePrice" 	=> $d['ProductSizePrice'],
				":PackingPrice" 		=> !empty($d['PackingPrice']) ? $d['PackingPrice'] : 0,
				":TimeSlotCharges" 		=> $d['TimeSlotCharges'],
				":ProductQty" 			=> $d['ProductQty'],
				":DeliveryTimeText" 	=> $d['DeliveryTimeText'],
				":DeliveryTimeSlot" 	=> $d['DeliveryTimeSlot'],
				":DeliveryDate" 		=> $d['DeliveryDate'],
				":Size" 				=> $d['Size'],
				":SenderMessage" 		=> $d['SenderMessage'],
				":Feature" 				=> $feature,
				":Type" 				=> $type,
				":SenderName" 			=> $d['SenderName'],
				":AnonymousPerson" 		=> $d['AnonymousPerson'],
				":CustomType" 			=> $customType,
				":CaptionMessage" 		=> $captionMessage,
				":CartUniqueID" 		=> $cartUniqueID,
				":HSN" 					=> $HSN,
				":GSTPercent" 			=> $GSTPercent,
				":City" 				=> $d['City'],
				":ProductCategoryID"	=> $d['ProductCategoryID'],
				":RecieverName"			=> $d['RecieverName'],
				":ParentCartID"			=> $d['ParentProductID']
			);

			$options['sql'] ="INSERT INTO ".FLORAL_DB.".flrl_cartdetails (UserID, ProductID, ProductIamge, ProductName, ProductCartType, photoImage, Price, Mrp, ProductSizePrice, PackingPrice, TimeSlotCharges, ProductQty, DeliveryTimeText, DeliveryTimeSlot, DeliveryDate, Size, SenderMessage, SenderName, AnonymousPerson, Feature, Type, CustomType, CaptionMessage, CreatedDate, UpdatedDate, CartUniqueID, HSN, GSTPercent, City, ProductCategoryID, RecieverName, ParentCartID) VALUES (:UserID, :ProductID, :ProductIamge, :ProductName, :ProductCartType, :photoImage, :Price, :Mrp, :ProductSizePrice, :PackingPrice, :TimeSlotCharges, :ProductQty, :DeliveryTimeText, :DeliveryTimeSlot, :DeliveryDate, :Size, :SenderMessage, :SenderName, :AnonymousPerson, :Feature, :Type, :CustomType, :CaptionMessage, NOW(), NOW(), :CartUniqueID, :HSN, :GSTPercent, :City, :ProductCategoryID, :RecieverName, :ParentCartID)";

			$rs1 = $this->sqlexecute($options);

			if($rs1)
			{
				$arr['msg'] = "Product added to Cart";
				$arr['error'] = 0;
			}
		}

		$this->data['addToCart'] = $arr;
	}


	public function addUserWishlist($d)
	{
		$arr = array();

		if(isset($d["action"]))
		{
			if($d["action"] === "add")
			{
				$options['barr'] = array(
					":UserID" 				=> $d['UserID'],
					":ProductID" 			=> $d['ProductID'],
					":CartUniqueID" 		=> $d['CartUniqueID']
				);

				$options['sql'] ="INSERT INTO ".FLORAL_DB.".flrl_whishlist (UserID, ProductID, CartUniqueID, IsActive, CreatedDate) VALUES (:UserID, :ProductID, :CartUniqueID, '1', NOW())";

				$rs = $this->sqlexecute($options);

				if($rs)
				{
					$arr['msg'] = "Product added to wishlist";
					$arr['error'] = 0;
				}
			} else {
				if($d["action"] === "remove") {
					if($d['UserID'] != 0) {
						$options['barr'] = array(
							":UserID"			=> $d['UserID'],
							":ProductID" 		=> $d['ProductID']
						);	
			
						$options['sql'] = "DELETE FROM ".FLORAL_DB.".flrl_whishlist WHERE UserID = :UserID AND ProductID = :ProductID";
						$options['barr'][":UserID"] = $d['UserID'];
					} else {
						$options['barr'] = array(
							":CartUniqueID"		=> $d['CartUniqueID'],
							":ProductID" 		=> $d['ProductID']
						);	
			
						$options['sql'] = "DELETE FROM ".FLORAL_DB.".flrl_whishlist WHERE CartUniqueID = :CartUniqueID AND ProductID = :ProductID";
						$options['barr'][":CartUniqueID"] = $d['CartUniqueID'];
					}

					$rs = $this->sqlexecute($options);

					if($rs)
					{
						$arr['msg'] = "Product removed from wishlist";
						$arr['error'] = 0;
					}
				}
			}
		}

		$this->data['addUserWishlist'] = $arr;
	}


	public function fetchProductReviews($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_productratings WHERE ProductID = :ProductID AND IsActive = '1'";
		$options['barr'] 	= array(":ProductID" => $d['ProductID']);

		$rs = $this->sqlexecute($options,1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['ID']					= $row['ID'];
				$arr[$i]['UserID']				= $row['UserID'];
				$arr[$i]['ProductID']			= $row['ProductID'];
				$arr[$i]['Rating']				= $row['Rating'];
				$arr[$i]['ReviewSubject']		= $row['ReviewSubject'];
				$arr[$i]['ReviewMessage']		= $row['ReviewMessage'];
				$arr[$i]['CreatedDate']			= $row['CreatedDate'];
				$arr[$i]['UserName']			= $row['UserName'];

				$i++;
			}
		}

		$this->data['fetchProductReviews'] = $arr;
	}


	public function fetchSuccessOrder($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_orderdetails WHERE UserID = :UserID AND TransactionID = :TransactionID";
		$options['barr'] 	= array(
			":UserID" => $d['UserID'],
			":TransactionID" => $d['TransactionID']
		);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['ID']						= $row['ID'];
				$arr[$i]['UserID']					= $row['UserID'];
				$arr[$i]['ProductID']				= $row['ProductID'];
				$arr[$i]['ProductQty']				= $row['ProductQty'];
				$arr[$i]['ProductName']				= htmlspecialchars($row['ProductName']);
				$arr[$i]['ProductPrice']			= $row['ProductPrice'];
				$arr[$i]['ProductMrp']				= $row['ProductMrp'];
				$arr[$i]['ShippingChrg']			= $row['ShippingChrg'];
				$arr[$i]['PackingChrg']				= $row['PackingChrg'];
				$arr[$i]['ProductSizePrice']		= $row['ProductSizePrice'];
				$arr[$i]['ProductImage']			= $row['ProductImage'];
				$arr[$i]['photoImage']				= $row['photoImage'];
				$arr[$i]['DeliveryTimeSlot']		= $row['DeliveryTimeSlot'];
				$arr[$i]['DeliveryTimeText']		= $row['DeliveryTimeText'];
				$arr[$i]['DeliveryDate']			= $row['DeliveryDate'];
				$arr[$i]['Size']					= $row['Size'];
				$arr[$i]['Feature']					= $row['Feature'];
				$arr[$i]['Type']					= $row['Type'];
				$arr[$i]['CustomType']				= $row['CustomType'];
				$arr[$i]['CaptionMessage']			= $row['CaptionMessage'];
				$arr[$i]['SenderMessage']			= $row['SenderMessage'];
				$arr[$i]['AnonymousPerson']			= $row['AnonymousPerson'];
				$arr[$i]['ShippingAddressId']		= $row['ShippingAddressId'];
				$arr[$i]['SenderName']				= $row['SenderName'];
				$arr[$i]['BillingAddress']			= $row['BillingAddress'];
				$arr[$i]['OrderID']					= $row['OrderID'];
				$arr[$i]['PaymentStatus']			= $row['PaymentStatus'];
				$arr[$i]['PaymentStatusCode']		= $row['PaymentStatusCode'];
				$arr[$i]['CreatedDate']				= $row['CreatedDate'];
				$arr[$i]['MobileNumber']			= $row['MobileNumber'];
				$arr[$i]['DeliveryName']			= $row['DeliveryName'];
				$arr[$i]['ShippingAddress']			= $row['ShippingAddress'];
				$arr[$i]['DeliveryContact']			= $row['DeliveryContact'];

				$i++;
			}
		}

		$this->data['fetchSuccessOrder'] = $arr;
	}


	public function fetchWalletUserHistory($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_wallet WHERE UserID = :UserID";
		$options['barr'] 	= array(
			":UserID"	=>	$d['UserID']
		);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['UserID']				= $row['UserID'];
				$arr[$i]['TransactionID']		= $row['TransactionID'];
				$arr[$i]['CreditValue']			= $row['CreditValue'];
				$arr[$i]['IsActve']				= $row['IsActve'];
				$arr[$i]['WalletStatus']		= $row['WalletStatus'];
				$arr[$i]['CreatedDate']			= $row['CreatedDate'];
				$arr[$i]['UpdatedDate']			= $row['UpdatedDate'];

				$i++;
			}
		}

		$this->data['fetchWalletUserHistory'] = $arr;
	}


	public function fetchWalletDetails($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_wallet WHERE UserID = :UserID AND IsActve='1' AND TransactionCurrency = :TransactionCurrency";
		$options['barr'] 	= array(
			":UserID"				=> $d['UserID'],
			":TransactionCurrency" 	=> $d['TransactionCurrency']
		);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr['UserID']							= $row['UserID'];
				$arr['TransactionID']					= $row['TransactionID'];
				$arr['IsActve']							= $row['IsActve'];
				$arr['CreditValue']						+= $row['CreditValue'];

				$i++;
			}
		}

		$this->data['fetchWalletDetails'] = $arr;
	}


	public function fetchOrdersWithTracking($d)
	{
		$arr = array();

		$options['sql'] = "SELECT *
					FROM ".FLORAL_DB.".flrl_tracking_history th
						INNER JOIN ".FLORAL_DB.".flrl_orderdetails od
							ON th.OrderID = od.OrderID WHERE od.OrderID = :OrderID GROUP BY od.ID ORDER BY od.CreatedDate ASC";

		$options['barr'] 	= array(
			":OrderID" => $d['OrderID']
		);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$orderID = $row['OrderID'];
				$productID = $row['ProductID'];

				$arr[$i]['ID']						= $row['ID'];
				$arr[$i]['UserID']					= $row['UserID'];
				$arr[$i]['ProductID']				= $productID;
				$arr[$i]['ProductQty']				= $row['ProductQty'];
				$arr[$i]['ProductName']				= htmlspecialchars($row['ProductName']);
				$arr[$i]['ProductPrice']			= $row['ProductPrice'];
				$arr[$i]['ProductMrp']				= $row['ProductMrp'];
				$arr[$i]['ShippingChrg']			= $row['ShippingChrg'];
				$arr[$i]['PackingChrg']				= $row['PackingChrg'];
				$arr[$i]['ProductSizePrice']		= $row['ProductSizePrice'];
				$arr[$i]['ProductImage']			= $row['ProductImage'];
				$arr[$i]['DeliveryTimeSlot']		= $row['DeliveryTimeSlot'];
				$arr[$i]['DeliveryTimeText']		= $row['DeliveryTimeText'];
				$arr[$i]['DeliveryDate']			= $row['DeliveryDate'];
				$arr[$i]['OrderID']					= $orderID;
				$arr[$i]['PaymentStatus']			= $row['PaymentStatus'];
				//$arr[$i]['TrackingSubject']		= $trackingSubject;
				//$arr[$i]['TrackingDate']			= $row['TrackingDate'];
				$arr[$i]['CreatedDate']				= $row['CreatedDate'];

				$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_tracking_history WHERE OrderID ='$orderID' AND ProductID = '$productID'";
				$options['barr'] 	= array(
					":OrderID" 		=> $d['OrderID'],
					":ProductID" 	=> $productID
				);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					$a = 0;
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$i]['TrackingSubject'][$a]['OrderID']				= $row['OrderID'];
						$arr[$i]['TrackingSubject'][$a]['State']				= $row['TrackingSubject'];
						$arr[$i]['TrackingSubject'][$a]['TrackingDate']			= $row['TrackingDate'];
						$arr[$i]['TrackingSubject'][$a]['TrackingTime']			= $row['TrackingTime'];

						$a++;
					}
				}

				$i++;
			}
		}

		$this->data['fetchOrdersWithTracking'] = $arr;
	}


	public function fetchOrderHistory($d)
	{
		$arr = array();

		// if($d['state'] === 'ongoing-orders') {
		// 	$paymentStatusCode = '2';
		// } elseif($d['state'] === 'all-orders') {
		// 	$paymentStatusCode = '1';
		// } elseif($d['state'] === 'cancelled-orders') {
		// 	$paymentStatusCode = '2';
		// }

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_orderdetails WHERE UserID = :UserID";
		$options['barr'] 	= array(":UserID" => $d['UserID']);

		if(isset($d["Month"]) && !empty($d["Month"]))
		{
			if($d["Month"] === '1') {
				$options['sql'] .= "
					AND CreatedDate > DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
				";
			} elseif ($d["Month"] === '3') {
				$options['sql'] .= "
					AND CreatedDate > DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
				";
			} elseif ($d["Month"] === '6') {
				$options['sql'] .= "
					AND CreatedDate > DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
				";
			} elseif ($d["Month"] === '12') {
				$options['sql'] .= "
					AND CreatedDate > DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
				";
			} elseif ($d["Month"] > '12') {
				$options['sql'] .= "
					AND CreatedDate > DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
				";
			}
		}

		$options['sql'] .= "
			ORDER BY CreatedDate DESC
		";

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			$a = 1;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$address = $row['ShippingAddress'];

				$arr['Orders'][$i]['ID']						= $row['ID'];
				$arr['Orders'][$i]['UserID']					= $row['UserID'];
				$arr['Orders'][$i]['ProductID']					= $row['ProductID'];
				$arr['Orders'][$i]['ProductQty']				= $row['ProductQty'];
				$arr['Orders'][$i]['ProductName']				= htmlspecialchars($row['ProductName']);
				$arr['Orders'][$i]['ProductPrice']				= $row['ProductPrice'];
				$arr['Orders'][$i]['ProductMrp']				= $row['ProductMrp'];
				$arr['Orders'][$i]['ShippingChrg']				= $row['ShippingChrg'];
				$arr['Orders'][$i]['PackingChrg']				= $row['PackingChrg'];
				$arr['Orders'][$i]['ProductSizePrice']			= $row['ProductSizePrice'];
				$arr['Orders'][$i]['ProductImage']				= $row['ProductImage'];
				$arr['Orders'][$i]['photoImage']				= $row['photoImage'];
				$arr['Orders'][$i]['DeliveryTimeSlot']			= $row['DeliveryTimeSlot'];
				$arr['Orders'][$i]['DeliveryTimeText']			= $row['DeliveryTimeText'];
				$arr['Orders'][$i]['DeliveryDate']				= $row['DeliveryDate'];
				$arr['Orders'][$i]['Size']						= $row['Size'];
				$arr['Orders'][$i]['Feature']					= $row['Feature'];
				$arr['Orders'][$i]['Type']						= $row['Type'];
				$arr['Orders'][$i]['CustomType']				= $row['CustomType'];
				$arr['Orders'][$i]['CaptionMessage']			= $row['CaptionMessage'];
				$arr['Orders'][$i]['SenderMessage']				= $row['SenderMessage'];
				$arr['Orders'][$i]['AnonymousPerson']			= $row['AnonymousPerson'];
				$arr['Orders'][$i]['ShippingAddressId']			= $row['ShippingAddressId'];
				//$arr['Orders'][$i]['ShippingAddress']			= $address;
				$arr['Orders'][$i]['SenderName']				= $row['SenderName'];
				$arr['Orders'][$i]['BillingAddress']			= $row['BillingAddress'];
				$arr['Orders'][$i]['OrderID']					= $row['OrderID'];
				$arr['Orders'][$i]['PaymentStatus']				= $row['PaymentStatus'];
				$arr['Orders'][$i]['PaymentStatusCode']			= $row['PaymentStatusCode'];
				$arr['Orders'][$i]['CreatedDate']				= $row['CreatedDate'];
				$arr['TotalCount']								= $a;

				// fetch shipping address
				$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_addressbook WHERE UserID = :UserID AND ID = ".$row['ShippingAddressId'];
				$options['barr'] 	= array(":UserID" => $d['UserID']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr['Orders'][$i]['ShippingAddress']['Address']				= $address;
						$arr['Orders'][$i]['ShippingAddress']['Title']					= $row['Title'];
						$arr['Orders'][$i]['ShippingAddress']['FirstName']				= $row['FirstName'];
						$arr['Orders'][$i]['ShippingAddress']['LastName']				= $row['LastName'];
						// $arr['Orders'][$i]['ShippingAddress']['BuildingName']		= $row['BuildingName'];
						// $arr['Orders'][$i]['ShippingAddress']['StreetName']			= $row['StreetName'];
						// $arr['Orders'][$i]['ShippingAddress']['AreaName']			= $row['AreaName'];
						// $arr['Orders'][$i]['ShippingAddress']['Landmark']			= $row['Landmark'];
						// $arr['Orders'][$i]['ShippingAddress']['City']				= $row['City'];
						$arr['Orders'][$i]['ShippingAddress']['State']					= $row['State'];
						// $arr['Orders'][$i]['ShippingAddress']['Postcode']			= $row['Postcode'];
						// $arr['Orders'][$i]['ShippingAddress']['Country']				= $row['Country'];
						$arr['Orders'][$i]['ShippingAddress']['MobileNumber']			= $row['MobileNumber'];
						$arr['Orders'][$i]['ShippingAddress']['AlternateNumber']		= $row['AlternateNumber'];
						$arr['Orders'][$i]['ShippingAddress']['SpecialInstruction']		= $row['SpecialInstruction'];
					}
				}
				$a++;
				$i++;
			}
		}

		$this->data['fetchOrderHistory'] = $arr;
	}


	public function search_product_list($d)
	{
		$arr = array();

		$query = !empty($d["query"]) ? $d["query"] : '';

		$options['sql'] ="SELECT * FROM product p INNER JOIN productcitymapping pcm ON p.ProductID = pcm.ProductID INNER JOIN productcategorysubcategorymapping pcsm ON p.ProductID = pcsm.ProductID INNER JOIN mastercategory mc ON pcsm.ProductCategoryID = mc.ID INNER JOIN productsubcategory psc ON pcsm.ProductSubCategoryID = psc.ProductSubCategoryID WHERE pcm.CityID = :CityID AND p.IsActive='1' AND p.ProductName LIKE '%$query%' OR mc.Name LIKE '%$query%' OR psc.ProductSubCategoryName LIKE '%$query%' OR p.ProductID LIKE '%$query%' OR p.ProductCode LIKE '%$query%' GROUP BY p.ProductID LIMIT 10";
		$options['barr'] 	= array(":CityID" => $d['CityID']);

		$rs = $this->sqlexecute($options, 1);
		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['ProductID']    				= $row['ProductID'];
				$arr[$i]['ProductImage']				= $row['ProductIamge'];
				$arr[$i]['ProductShortDescription']    	= htmlspecialchars($row['ProductShortDescription']);
				$arr[$i]['ProductName']    				= htmlspecialchars($row['ProductName']);
				$arr[$i]['ProductCategoryID']    		= $row['ProductCategoryID'];
				$arr[$i]['ProductSubCategoryID']    	= $row['ProductSubCategoryID'];
				$arr[$i]['Price']    					= $row['Price'];
				$arr[$i]['Currency']    				= $row['Currency'];
				
				$i++;
			}	
		}
		
		$this->data['search_product_list'] = $arr;
	}

	public function addProductReview($d)
	{
		$arr = array();

		if(!empty($d["UserID"]) && !empty($d["Rating"]) && !empty($d["ProductID"]))
		{
			$options['barr'] = array(
				":UserID" 				=> $d['UserID'],
				":ProductID" 			=> $d['ProductID'],
				":Rating" 				=> $d['Rating'],
				":ReviewSubject" 		=> $d['ReviewSubject'],
				":ReviewMessage" 		=> $d['ReviewMessage'],
				":UserName" 			=> $d['UserName']
			);

			$options['sql'] ="INSERT INTO ".FLORAL_DB.".flrl_productratings (UserID, ProductID, Rating, ReviewSubject, ReviewMessage, IsActive, CreatedDate, CreatedBy, UserName) VALUES (:UserID, :ProductID, :Rating, :ReviewSubject, :ReviewMessage, '0', NOW(), 'User', :UserName)";

			$rs = $this->sqlexecute($options);

			if($rs)
			{
				$arr['msg'] = "Product review added";
				$arr['error'] = 0;
			}
		}

		$this->data['addProductReview'] = $arr;
	}


	public function insertContactUs($d)
	{
		$arr = array();

		if(!empty($d["contactName"]) && !empty($d["contactEmail"]) && !empty($d["contactPhone"]))
		{
			$options['barr'] = array(
				":contactName" 					=> $d['contactName'],
				":contactSurname" 				=> $d['contactSurname'],
				":contactEmail" 				=> $d['contactEmail'],
				":contactPhone" 				=> $d['contactPhone'],
				":contactSubject" 				=> $d['contactSubject'],
				":contactMessage" 				=> $d['contactMessage']
			);

			$options['sql'] ="INSERT INTO ".FLORAL_DB.".flrl_contactus (contactName, contactSurname, contactEmail, contactPhone, contactSubject, contactMessage, CreatedDate) VALUES (:contactName, :contactSurname, :contactEmail, :contactPhone, :contactSubject, :contactMessage, NOW())";

			$rs = $this->sqlexecute($options);

			if($rs)
			{
				$arr['msg'] = "Details submitted";
				$arr['error'] = 0;
			}
		}

		$this->data['insertContactUs'] = $arr;
	}


	public function addUserAddress($d)
	{
		$arr = array();

		if(!empty($d['UserID']) && !empty($d['FirstName']) && !empty($d['MobileNumber']) && !empty($d['Title']))
		{
			$options['barr'] = array(
				":UserID" 				=> $d['UserID'],
				":Title" 				=> $d['Title'],
				":FirstName" 			=> $d['FirstName'],
				":LastName" 			=> $d['LastName'],
				":BuildingName" 		=> $d['BuildingName'],
				":StreetName" 			=> $d['StreetName'],
				":AreaName" 			=> $d['AreaName'],
				":Landmark" 			=> $d['Landmark'],
				":City" 				=> $d['City'],
				":State" 				=> $d['State'],
				":Postcode" 			=> $d['Postcode'],
				":Country" 				=> $d['Country'],
				":MobileNumber" 		=> $d['MobileNumber'],
				":AlternateNumber" 		=> $d['AlternateNumber'],
				":SpecialInstruction" 	=> $d['SpecialInstruction']
			);

			$options['sql'] ="INSERT INTO ".FLORAL_DB.".flrl_addressbook (UserID, Title, FirstName, LastName, BuildingName, StreetName, AreaName, Landmark, City, State, Postcode, Country, MobileNumber, AlternateNumber, SpecialInstruction) VALUES (:UserID, :Title, :FirstName, :LastName, :BuildingName, :StreetName, :AreaName, :Landmark, :City, :State, :Postcode, :Country, :MobileNumber, :AlternateNumber, :SpecialInstruction)";

			$rs = $this->sqlexecute($options);

			if($rs)
			{
				$arr['msg'] = "User address added";
				$arr['error'] = 0;
			}
		}

		$this->data['addUserAddress'] = $arr;
	}


	public function insertTransactionDetails($d)
	{
		$arr = array();

		if(!empty($d['UserID']) && !empty($d['TransactionID']) && !empty($d['TransactionType']))
		{
			$options['barr'] = array(
				":UserID" 					=> $d['UserID'],
				":TransactionID" 			=> $d['TransactionID'],
				":TransactionType" 			=> $d['TransactionType'],
				":PaymentStatus" 			=> $d['PaymentStatus'],
				":TransactionTotal" 		=> $d['TransactionTotal'],
				":Discount" 				=> $d['Discount'],
				":PayerEmail" 				=> $d['PayerEmail'],
				":TransactionCurrency" 		=> $d['TransactionCurrency'],
				":PaymentStatusCode" 		=> $d['PaymentStatusCode'],
				":PaymentGateway" 			=> $d['PaymentGateway']
			);

			$options['sql'] ="INSERT INTO ".FLORAL_DB.".flrl_transactiondetail (UserID, TransactionID, TransactionType, PaymentStatus, TransactionTotal, Discount, PayerEmail, TransactionCurrency, PaymentStatusCode, PaymentGateway) VALUES (:UserID, :TransactionID, :TransactionType, :PaymentStatus, :TransactionTotal, :Discount, :PayerEmail, :TransactionCurrency, :PaymentStatusCode, :PaymentGateway)";

			$rs = $this->sqlexecute($options);

			if($rs)
			{
				$arr['msg'] = "Transaction added";
				$arr['error'] = 0;

				if($d['UserWalletStatus'] == 1) {
					$options['sql']  = "UPDATE ".FLORAL_DB.".flrl_wallet set IsActve = 0, WalletStatus = 'Used', UpdatedDate = NOW() WHERE UserID = :UserID";
					$options['barr'] = array(":UserID" => $d['UserID']);

					$rs = $this->sqlexecute($options);

					if($rs)
					{
						$arr['msg'] = "User wallet updated";
						$arr['error'] = 0;
					}
				}
			}
		}

		$this->data['insertTransactionDetails'] = $arr;
	}


	public function insertWalletDetails($d)
	{
		$arr = array();

		$cart = explode(",", $d['CartID']);

		$UserID					= '';
		$ProductPrice			= '';
		$PackingChrg			= '';
		$ProductSizePrice		= '';
		$TransactionID			= $d['TransactionID'];
		$TransactionCurrency	= $d['TransactionCurrency'];
		$WalletExpiry			= date('Y-m-d H:i:s', strtotime("+3 months", strtotime(date("Y-m-d H:i:s"))));

		$d = 0;
		foreach ($cart as $val) {
			$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_cartdetails WHERE CartID ='".$val."'";
			$options['barr'] 	= array(":CartID" => $d['CartID']);

			$rs = $this->sqlexecute($options, 1);

			if($rs->rowCount() > 0)
			{
				while($row = $rs->fetch(PDO::FETCH_ASSOC))
				{
					if($row['ProductCategoryID'] != '103') {
						$UserID					= $row['UserID'];
						$ProductPrice 			+= $row['Price'];
						$PackingChrg			+= $row['PackingPrice'];
						$ProductSizePrice 		+= $row['ProductSizePrice'];
					}
				}
			}
			$d++;
		}

		if($rs)
		{
			$WalletPrice = ($ProductPrice + $PackingChrg + $ProductSizePrice) * 5 / 100;

			$options['barr'] = array(
				":UserID" 					=> $UserID,
				":TransactionID" 			=> $TransactionID,
				":TransactionCurrency" 		=> $TransactionCurrency,
				":CreditValue" 				=> $WalletPrice,
				":WalletExpiry"				=> $WalletExpiry,
				":WalletStatus"				=> 'Unused'
			);

			$options['sql'] = "INSERT INTO ".FLORAL_DB.".flrl_wallet (UserID, TransactionID, TransactionCurrency, CreditValue, IsActve, WalletExpiry, WalletStatus) VALUES (:UserID, :TransactionID, :TransactionCurrency, :CreditValue, '0', :WalletExpiry, :WalletStatus)";

			$rs2 = $this->sqlexecute($options);

			if($rs2)
			{
				$arr['msg'] = "Wallet added";
				$arr['error'] = 0;
			}
		}

		$this->data['insertWalletDetails'] = $arr;
	}


	public function insertOrderDetails($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_cartdetails WHERE CartID =" .$d['CartID'];
		$options['barr'] 	= array(":CartID" => $d['CartID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$CartID					= $row['CartID'];
				$ProductID				= $row['ProductID'];
				$ParentCartID			= $row['ParentCartID'];
				$ProductName			= htmlspecialchars($row['ProductName']);
				$Price					= $row['Price'];
				$Mrp					= $row['Mrp'];
				$ProductSizePrice		= $row['ProductSizePrice'];
				$PackingPrice			= $row['PackingPrice'];
				$TimeSlotCharges		= $row['TimeSlotCharges'];
				$ProductImage			= $row['ProductIamge'];
				$photoImage				= $row['photoImage'];
				$ProductQty				= $row['ProductQty'];
				$DeliveryTimeSlot		= $row['DeliveryTimeSlot'];
				$DeliveryTimeText		= $row['DeliveryTimeText'];
				$DeliveryDate			= $row['DeliveryDate'];
				$Size					= $row['Size'];
				$Feature				= $row['Feature'];
				$Type					= $row['Type'];
				$CustomType				= $row['CustomType'];
				$CaptionMessage			= $row['CaptionMessage'];
				$SenderMessage			= $row['SenderMessage'];
				$SenderName				= $row['SenderName'];
				$AnonymousPerson		= $row['AnonymousPerson'];
				$AddressSelectedID		= $row['AddressSelectedID'];
				$HSN					= $row['HSN'];
				$GSTPercent				= $row['GSTPercent'];
				$RecieverName			= $row['RecieverName'];

				$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_addressbook WHERE ID =" .$AddressSelectedID;
				$options['barr'] 	= array(":ID" => $AddressSelectedID);
		
				$rs1 = $this->sqlexecute($options, 1);
		
				if($rs1->rowCount() > 0)
				{
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$BuildingName		= $row['BuildingName'];
						$StreetName			= $row['StreetName'];
						$AreaName			= $row['AreaName'];
						$Landmark			= $row['Landmark'];
						$City				= $row['City'];
						$State				= $row['State'];
						$Postcode			= $row['Postcode'];
						$Country			= $row['Country'];
						$MobileNumber		= $row['MobileNumber'];

						if(!empty($d['UserID']))
						{

							$Address = $BuildingName .', '. $StreetName .', '. $AreaName .', Near '. $Landmark .', '. $City .' - '. $Postcode;

							if($d['PaymentStatusCode'] === '1') {
								$LastTrackingStatus = 'Order Placed';
							} else {
								$LastTrackingStatus = 'Order Pending';
							}

							$options['barr'] = array(
								":UserID" 					=> $d['UserID'],
								":ProductID" 				=> $ProductID,
								":ProductQty" 				=> $ProductQty,
								":ProductName" 				=> $ProductName,
								":ProductPrice" 			=> $Price,
								":ProductMrp" 				=> $Mrp,
								":ShippingChrg" 			=> $TimeSlotCharges,
								":PackingChrg" 				=> $PackingPrice,
								":ProductSizePrice" 		=> $ProductSizePrice,
								":ProductImage" 			=> $ProductImage,
								":photoImage" 				=> $photoImage,
								":DeliveryTimeSlot" 		=> $DeliveryTimeSlot,
								":DeliveryTimeText" 		=> $DeliveryTimeText,
								":DeliveryDate" 			=> $DeliveryDate,
								":Size" 					=> $Size,
								":Feature" 					=> $Feature,
								":Type" 					=> $Type,
								":CustomType" 				=> $CustomType,
								":CaptionMessage" 			=> $CaptionMessage,
								":SenderMessage" 			=> $SenderMessage,
								":AnonymousPerson" 			=> $AnonymousPerson,
								":ShippingAddressId" 		=> $AddressSelectedID,
								":ShippingAddress" 			=> $Address,
								":SenderName" 				=> $SenderName,
								":BillingAddress" 			=> $d['BillingAddress'],
								":OrderID" 					=> $d['OrderID'],
								":PaymentStatus" 			=> $d['PaymentStatus'],
								":PaymentStatusCode" 		=> $d['PaymentStatusCode'],
								":Country" 					=> $Country,
								":LastTrackingStatus" 		=> $LastTrackingStatus,
								":HSN" 						=> $HSN,
								":GSTPercent" 				=> $GSTPercent,
								":TransactionID" 			=> $d['TransactionID'],
								":DeliveryContact" 			=> $MobileNumber,
								":DeliveryName" 			=> $RecieverName,
								":MobileNumber" 			=> $d['MobileNumber']
							);

							$options['sql'] = "INSERT INTO ".FLORAL_DB.".flrl_orderdetails (UserID, ProductID, ProductQty, ProductName, ProductPrice, ProductMrp, ShippingChrg, PackingChrg, ProductSizePrice, ProductImage, photoImage, DeliveryTimeSlot, DeliveryTimeText, DeliveryDate, Size, Feature, Type, CustomType, CaptionMessage, SenderMessage, AnonymousPerson, ShippingAddressId, ShippingAddress, SenderName, BillingAddress, CreatedDate, OrderID, PaymentStatus, PaymentStatusCode, Country, LastTrackingStatus, HSN, GSTPercent, TransactionID, DeliveryContact, DeliveryName, MobileNumber) VALUES (:UserID, :ProductID, :ProductQty, :ProductName, :ProductPrice, :ProductMrp, :ShippingChrg, :PackingChrg, :ProductSizePrice, :ProductImage, :photoImage, :DeliveryTimeSlot, :DeliveryTimeText, :DeliveryDate, :Size, :Feature, :Type, :CustomType, :CaptionMessage, :SenderMessage, :AnonymousPerson, :ShippingAddressId, :ShippingAddress, :SenderName, :BillingAddress, NOW(), :OrderID, :PaymentStatus, :PaymentStatusCode, :Country, :LastTrackingStatus, :HSN, :GSTPercent, :TransactionID, :DeliveryContact, :DeliveryName, :MobileNumber)";

							$rs2 = $this->sqlexecute($options);

							if($rs2)
							{
								$options['barr'] = array(
									":OrderID" 					=> $d['OrderID'],
									":ProductID" 				=> $ProductID,
									":LastTrackingStatus" 		=> $LastTrackingStatus,
								);

								$options['sql'] = "INSERT INTO ".FLORAL_DB.".flrl_tracking_history (OrderID, ProductID, TrackingSubject) VALUES (:OrderID, :ProductID, :LastTrackingStatus)";

								$rs3 = $this->sqlexecute($options);

								if($rs3)
								{
									// Delete products from cart
									$options['sql'] = "DELETE FROM ".FLORAL_DB.".flrl_cartdetails WHERE CartID = ".$d['CartID'];

									$rs4 = $this->sqlexecute($options);

									if($rs4)
									{
										$arr['msg'] = "Order details with Tracking added and cart removed";
										$arr['error'] = 0;
									}
								}
							}
						}
					}
				}
			}
		}

		$this->data['insertOrderDetails'] = $arr;
	}


	public function editUserAddress($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * FROM ".FLORAL_DB.".flrl_addressbook WHERE ID = :ID AND UserID = :UserID";
		$options['barr'] 	= array(
			":ID" 		=> $d['ID'],
			":UserID" 	=> $d['UserID']
		);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr['UserID']				= $row['UserID'];
				$arr['Title']				= $row['Title'];
				$arr['FirstName']			= $row['FirstName'];
				$arr['LastName']			= $row['LastName'];
				$arr['BuildingName']		= $row['BuildingName'];
				$arr['StreetName']			= $row['StreetName'];
				$arr['AreaName']			= $row['AreaName'];
				$arr['Landmark']			= $row['Landmark'];
				$arr['City']				= $row['City'];
				$arr['State']				= $row['State'];
				$arr['Postcode']			= $row['Postcode'];
				$arr['Country']				= $row['Country'];
				$arr['MobileNumber']		= $row['MobileNumber'];
				$arr['AlternateNumber']		= $row['AlternateNumber'];
				$arr['SpecialInstruction']	= $row['SpecialInstruction'];
			}
		}

		$this->data['editUserAddress'] = $arr;
	}

	public function deleteUserAddress($d)
	{
		$arr = array();

		$options['sql']  = "DELETE FROM ".FLORAL_DB.".flrl_addressbook WHERE ID = :ID";
		$options['barr'] 	= array(":ID"=> $d['ID']);

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "Address removed";
			$arr['error'] = 0;
		}

		$this->data['deleteUserAddress'] = $arr;
	}


	public function fetchUserWishList($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * FROM ".FLORAL_DB.".flrl_whishlist WHERE UserID = :UserID";
		$options['barr'] 	= array(":UserID" 	=> $d['UserID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$a = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$productID = $row['ProductID'];

				$arr[$a]['ID']					= $row['ID'];
				$arr[$a]['ProductID']			= $productID;
				$arr[$a]['CartUniqueID']		= $row['CartUniqueID'];
				$arr[$a]['UserID']				= $row['UserID'];
				$arr[$a]['IsActive']			= $row['IsActive'];

				$options['sql'] 	= "SELECT * FROM ".FLORAL_DB.".product WHERE ProductID =".$productID;
				$options['barr'] 	= array(":ProductID" 	=> $d['ProductID']);

				$rs1 = $this->sqlexecute($options, 1);

				if($rs1->rowCount() > 0)
				{
					$i = 0;
					while($row = $rs1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$a]['Product'][$i]['ProductName']					= htmlspecialchars($row['ProductName']);
						$arr[$a]['Product'][$i]['ProductID']					= $productID;
						$arr[$a]['Product'][$i]['ProductIamge']					= $row['ProductIamge'];
						$arr[$a]['Product'][$i]['ProductShortDescription']		= htmlspecialchars($row['ProductShortDescription']);
						$arr[$a]['Product'][$i]['Price']						= $row['Price'];
						$arr[$a]['Product'][$i]['Mrp']							= $row['Mrp'];
						$arr[$a]['Product'][$i]['isOneDayDelivery']				= $row['isOneDayDelivery'];
						$arr[$a]['Product'][$i]['IsDeliveryTimeRestricted']		= $row['IsDeliveryTimeRestricted'];
						$arr[$a]['Product'][$i]['ProductRating']				= $row['ProductRating'];
						$arr[$a]['Product'][$i]['ProductRatingCount']			= $row['ProductRatingCount'];

						// Active Whistlist products
						if($d['UserID'] != '0') {
							$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND UserID = '".$d['UserID']."'";
							$options['barr'] 	= array(
								":ProductID" => $productID,
								":UserID"	 => $d['UserID']
							);
						} else {
							$options['sql'] = "SELECT IsActive FROM ".FLORAL_DB.".flrl_whishlist WHERE ProductID =".$productID." AND CartUniqueID = '".$d['CartUniqueID']."'";
							$options['barr'] 	= array(
								":ProductID" 	=> $productID,
								":CartUniqueID" => $d['CartUniqueID']
							);
						}

						$rs2 = $this->sqlexecute($options, 1);

						if($rs2->rowCount() > 0)
						{
							while($row = $rs2->fetch(PDO::FETCH_ASSOC))
							{
								$arr[$a]['Product'][$i]['ActiveWishList']		= $row['IsActive'];
							}
						} else {
							$arr[$a]['Product'][$i]['ActiveWishList']		= '0';
						}

						$i++;
					}
				}
				$a++;
			}
		}

		$this->data['fetchUserWishList'] = $arr;
	}


	public function updateUserAddress($d)
	{
		$arr = array();

		$options['barr'] = array(
			":ID" 					=> $d['ID'],
			":Title" 				=> $d['Title'],
			":FirstName" 			=> $d['FirstName'],
			":LastName" 			=> $d['LastName'],
			":BuildingName" 		=> $d['BuildingName'],
			":StreetName" 			=> $d['StreetName'],
			":AreaName" 			=> $d['AreaName'],
			":Landmark" 			=> $d['Landmark'],
			":City" 				=> $d['City'],
			":State" 				=> $d['State'],
			":Postcode" 			=> $d['Postcode'],
			":Country" 				=> $d['Country'],
			":MobileNumber" 		=> $d['MobileNumber'],
			":AlternateNumber" 		=> $d['AlternateNumber'],
			":SpecialInstruction" 	=> $d['SpecialInstruction']
		);

		if(!empty($d['FirstName']) && !empty($d['Title']) && !empty($d['LastName']))
		{
			$options['sql'] ="UPDATE ".FLORAL_DB.".flrl_addressbook SET Title = :Title, FirstName = :FirstName, LastName = :LastName, BuildingName = :BuildingName, StreetName = :StreetName, AreaName = :AreaName, Landmark = :Landmark, City = :City, State = :State, Postcode = :Postcode, Country = :Country, MobileNumber = :MobileNumber, AlternateNumber = :AlternateNumber, SpecialInstruction = :SpecialInstruction WHERE ID = :ID";

			$options['barr'][":ID"] = $d['ID'];
		}

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "Address updated";
			$arr['error'] = 0;
		}
		$this->data['updateUserAddress'] = $arr; 
	}


	public function addSelectedAddToProduct($d)
	{
		$arr = array();

		$options['barr'] = array(
			":ID"				=> $d['ID'],
			":CartID"			=> $d['CartID']
		);

		if(!empty($d['CartID']) && !empty($d['ID']))
		{
			$options['sql'] ="UPDATE ".FLORAL_DB.".flrl_cartdetails SET AddressSelectedID = :ID WHERE CartID = :CartID";
			$options['barr'][":CartID"] = $d['CartID'];
		}

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$options['sql'] ="UPDATE ".FLORAL_DB.".flrl_cartdetails SET AddressSelectedID = :ID WHERE ParentCartID = :CartID";
			$options['barr'][":CartID"] = $d['CartID'];

			$rs1 = $this->sqlexecute($options);

			$arr['msg'] = "Address selected";
			$arr['error'] = 0;
		}
		$this->data['addSelectedAddToProduct'] = $arr; 
	}


	public function delteCart($d)
	{
		$arr = array();

		if($d["action"] === 'removeCart')
		{
			if($d['CartUniqueID'] !== 0 && isset($d['CartUniqueID'])) {

				$options['sql']  = "DELETE FROM ".FLORAL_DB.".flrl_cartdetails WHERE CartUniqueID = :CartUniqueID";
				$options['barr'] = array(":CartUniqueID" => $d['CartUniqueID']);

			} else {

				$options['sql']  = "DELETE FROM ".FLORAL_DB.".flrl_cartdetails WHERE CartID = :CartID";
				$options['barr'] = array(":CartID" => $d['CartID']);

			}

			$rs = $this->sqlexecute($options);

			if($rs)
			{
				if($d['CartUniqueID'] !== 0 && isset($d['CartUniqueID'])) {

					$options['sql'] = "SELECT * FROM ".FLORAL_DB.".flrl_cartdetails WHERE CartUniqueID = :CartUniqueID AND ParentCartID != 0";
					$options['barr'] = array(":CartUniqueID" => $d['CartUniqueID']);

				} else {

					$options['sql'] = "SELECT * FROM ".FLORAL_DB.".flrl_cartdetails WHERE ParentCartID = :CartID AND ParentCartID != 0";
					$options['barr'] = array(":CartID" => $d['CartID']);

				}

				$rs1 = $this->sqlexecute($options);

				while($row = $rs1->fetch(PDO::FETCH_ASSOC))
				{
					$options['sql']  = "DELETE FROM ".FLORAL_DB.".flrl_cartdetails WHERE ParentCartID = :CartID";
					$options['barr'] = array(":CartID" => $d['CartID']);

					$rs3 = $this->sqlexecute($options);
				}

				$arr['msg'] = "Product removed from cart";
				$arr['error'] = 0;
			}
		}

		$this->data['delteCart'] = $arr;
	}


	public function delteCartByDate($d)
	{
		$arr = array();

		$options['sql']  = "DELETE FROM ".FLORAL_DB.".flrl_cartdetails WHERE DeliveryDate < NOW()";
		$options['barr'] = array(":DeliveryDate" => $d['DeliveryDate']);

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "Cart removed from database";
			$arr['error'] = 0;
		}

		$this->data['delteCartByDate'] = $arr;
	}

	public function delteUserWalletByDate($d)
	{
		$arr = array();

		$options['sql']  = "UPDATE ".FLORAL_DB.".flrl_wallet set IsActve = 0, WalletStatus = 'Expired', UpdatedDate = NOW() WHERE WalletExpiry < NOW()";
		$options['barr'] = array(":WalletExpiry" => $d['WalletExpiry']);

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "Wallet removed from database";
			$arr['error'] = 0;
		}

		$this->data['delteUserWalletByDate'] = $arr;
	}

	public function addSellersData($d)
	{
		$arr = array();		
		$options['barr'] = array(
			":Password"						=> $d['PhoneNumber'],
			":BusinessType" 				=> $d['BusinessType'],
			":SellerName" 					=> $d['SellerName'],
			":BusinessName" 				=> $d['BusinessName'],
			":Email" 						=> $d['Email'],
			":PhoneNumber" 					=> $d['PhoneNumber'],
			":BusinessUrl" 					=> $d['BusinessUrl'],
			":BusinessLocation" 			=> $d['BusinessLocation'],
			":Latitude" 					=> $d['Latitude'],
			":Longtitude" 					=> $d['Longtitude'],
			":IsActive" 					=> '0'
		);

		$options['sql'] ="INSERT INTO ".FLORAL_DB.".flrl_sellers_profile (Password, BusinessType, SellerName, BusinessName, Email, PhoneNumber, BusinessUrl, BusinessLocation, Latitude, Longtitude, IsActive) VALUES (:Password, :BusinessType, :SellerName, :BusinessName, :Email, :PhoneNumber, :BusinessUrl, :BusinessLocation, :Latitude, :Longtitude, :IsActive)";

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "Seller account created";
			$arr['error'] = 0;
		}

		$this->data['addSellersData'] = $arr;
	}

	public function fetchOccasion($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".mastercategory WHERE SubMenuID = '2' AND IsActive='1'";

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['ID']					= $row['ID'];
				$arr[$i]['Name']				= $row['Name'];
				$i++;
			}
		}

		$this->data['fetchOccasion'] = $arr;
	}

	public function fetchUserReminders($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * from ".FLORAL_DB.".flrl_personal_reminder WHERE UserID = '".$d['UserID']."'";
		$options['barr'] = array(":UserID" => $d['UserID']);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			$i = 0;
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr[$i]['ID']					= $row['ID'];
				$arr[$i]['ReminderName']		= $row['ReminderName'];
				$arr[$i]['ReminderDate']		= $row['ReminderDate'];
				$arr[$i]['Event']				= $row['Event'];
				$i++;
			}
		}

		$this->data['fetchUserReminders'] = $arr;
	}

	public function editReminder($d)
	{
		$arr = array();

		$options['sql'] 	= "SELECT * FROM ".FLORAL_DB.".flrl_personal_reminder WHERE ID = :ID ORDER BY ReminderDate ASC";
		$options['barr'] 	= array(
			":ID" 		=> $d['ID']
		);

		$rs = $this->sqlexecute($options, 1);

		if($rs->rowCount() > 0)
		{
			while($row = $rs->fetch(PDO::FETCH_ASSOC))
			{
				$arr['UserID']				= $row['UserID'];
				$arr['ReminderName']		= $row['ReminderName'];
				$arr['LocationName']		= $row['LocationName'];
				$arr['STD']					= $row['STD'];
				$arr['ContactNumber']		= $row['ContactNumber'];
				$arr['Email']				= $row['Email'];
				$arr['EventCode']			= $row['EventCode'];
				$arr['Event']				= $row['Event'];
				$arr['ReminderDate']		= $row['ReminderDate'];
				$arr['Notes']				= $row['Notes'];
				$arr['IsNotified']			= $row['IsNotified'];
				$arr['IsActive']			= $row['IsActive'];
			}
		}

		$this->data['editReminder'] = $arr;
	}

	public function addReminder($d)
	{
		$arr = array();

		$options['barr'] = array(
			":UserID" 				=> $d['UserID'],
			":User_name" 			=> $d['User_name'],
			":ReminderName" 		=> $d['ReminderName'],
			":LocationName" 		=> $d['LocationName'],
			":STD" 					=> $d['STD'],
			":ContactNumber" 		=> $d['ContactNumber'],
			":Email" 				=> $d['Email'],
			":EventCode" 			=> $d['EventCode'],
			":Event" 				=> $d['Event'],
			":ReminderDate" 		=> $d['ReminderDate'],
			":Notes" 				=> $d['Notes'],
			":Preference" 			=> $d['Preference'],
			":IsNotified" 			=> $d['IsNotified'],
			":IsActive" 			=> "1"
		);

		$options['sql'] ="INSERT INTO ".FLORAL_DB.".flrl_personal_reminder (UserID, ReminderType, User_name, ReminderName, LocationName, STD, ContactNumber, Email, EventCode, Event, ReminderDate, Preference, Notes, IsNotified, IsActive) VALUES (:UserID, 'Manual', :User_name, :ReminderName, :LocationName, :STD, :ContactNumber, :Email, :EventCode, :Event, :ReminderDate, :Preference, :Notes, :IsNotified, :IsActive)";

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "Reminder added successfully";
			$arr['error'] = 0;
		}

		$this->data['addReminder'] = $arr;
	}

	public function updateReminder($d)
	{
		$arr = array();

		$options['barr'] = array(
			":ID" 					=> $d['ID'],
			":UserID" 				=> $d['UserID'],
			":User_name" 			=> $d['User_name'],
			":ReminderName" 		=> $d['ReminderName'],
			":LocationName" 		=> $d['LocationName'],
			":STD" 					=> $d['STD'],
			":ContactNumber" 		=> $d['ContactNumber'],
			":Email" 				=> $d['Email'],
			":EventCode" 			=> $d['EventCode'],
			":Event" 				=> $d['Event'],
			":ReminderDate" 		=> $d['ReminderDate'],
			":Notes" 				=> $d['Notes'],
			":Preference" 			=> $d['Preference'],
			":IsNotified" 			=> $d['IsNotified'],
			":IsActive" 			=> $d['IsActive']
		);

		$options['sql'] ="UPDATE ".FLORAL_DB.".flrl_personal_reminder SET ReminderType = 'Manual', User_name = :User_name, ReminderName = :ReminderName, LocationName = :LocationName, STD = :STD, ContactNumber = :ContactNumber, Email = :Email, EventCode = :EventCode, Event = :Event, ReminderDate = :ReminderDate, Notes = :Notes, Preference = :Preference, IsNotified = :IsNotified, IsNotified1 = 0, IsNotified2 = 0, IsActive = :IsActive WHERE ID = :ID AND UserID = :UserID";

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "Reminder updated";
			$arr['error'] = 0;
		}
		$this->data['updateReminder'] = $arr; 
	}

	public function deleteReminder($d)
	{
		$arr = array();

		$options['sql'] ="DELETE FROM ".FLORAL_DB.".flrl_personal_reminder WHERE ID = :ID AND UserID = :UserID";
		
		$options['barr'] = array(
			":UserID"		=> $d['UserID'],
			":ID"			=> $d['ID']
		);

		$rs = $this->sqlexecute($options);

		if($rs)
		{
			$arr['msg'] = "Reminder removed";
			$arr['error'] = 0;
		}
		$this->data['deleteReminder'] = $arr; 
	}
	/**************************************************************************/

}
?>