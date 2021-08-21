<?php
	header("Pragma: no-cache");
	header("Cache-Control: no-cache");
	header("Expires: 0");
	// following files need to be included
	require_once("lib/config_paypal.php");

	$DATA_ARRAY = base64_decode($_POST["checkout_data"]);
	$DECODE_DATA_ARRAY = json_decode($DATA_ARRAY);

	$type 			= $_POST["type"];
	$fName 			= $_POST["fName"];
	$lName 			= $_POST["lName"];
	$blockNo 		= $_POST["blockNo"];
	$streetName 	= $_POST["streetName"];
	$areaName 		= $_POST["areaName"];
	$landmark 		= $_POST["landmark"];
	$city 			= $_POST["city"];
	$state 			= $_POST["state"];
	$postCode 		= $_POST["postCode"];
	$country		= $_POST["country"];
	$countryCode	= $_POST["countryCode"];
	$email			= $_POST["userEmail"];
	$mobileNo		= $_POST["mobileNo"];
	$custom			= $_POST["custom"];
	$WalletActive	= $_POST["UWallet"];
	$WalletAmount	= $_POST["tgwllt"];

	if(isset($_POST["dscp"]) && isset($_COOKIE['Pd'])) {
		$promodisc = isset($_POST["dscp"]) ? $_POST["dscp"] : $_SESSION['PromoDiscount'];
	} else {
		$promodisc = 0;
	}

	if($WalletAmount > 0 && $WalletActive === '1') {
		$promodisc += $WalletAmount;
	}

	//print_r($type);
	//print_r('<br/>');

	//print_r('1'. $promodisc); 
	//print_r('<br/>');
	//print_r('2'. $_COOKIE['Pd']);
?>

<html oncontextmenu="return false">
<head>
<title>PayPal Checkout</title>

<script type="text/javascript">
	document.addEventListener('keydown', function() {
		if (event.keyCode == 123) {
			return false;
		} else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
			return false;
		} else if (event.ctrlKey && event.keyCode == 85) {
			return false;
		}
	}, false);

	if (document.addEventListener) {
		document.addEventListener('contextmenu', function(e) {
			e.preventDefault();
		}, false);
	} else {
		document.attachEvent('oncontextmenu', function() {
			window.event.returnValue = false;
		});
	}
</script>
</head>
<body>
	<form method="post" action="<?=PAYPAL_URL?>" name="f1">
		<!-- Identify your business so that you can collect the payments. -->
		<input type="hidden" name="business" value="<?=PAYPAL_ID?>">
		<!-- Specify a Buy Now button. -->
		<input type="hidden" name="cmd" value="_cart">
		<input type="hidden" name="upload" value="1">

		<input type="hidden" name="first_name" value="<?=$fName?>">
		<input type="hidden" name="last_name" value="<?=$lName?>">
		<input type="hidden" name="address2" value="<?=$streetName.', '.$areaName?>">
		<input type="hidden" name="address1" value="<?=$blockNo?>">
		<input type="hidden" name="city" value="<?=$city?>">
		<input type="hidden" name="state" value="<?=$state?>">
		<input type="hidden" name="zip" value="<?=$postCode?>">
		<input type="hidden" name="country" value="<?=$countryCode?>">
		<input type="hidden" name="night_phone_a" value="<?=$mobileNo?>">
		<input type="hidden" name="email" value="<?=$email?>">
		<input type="hidden" name="custom" value="<?=$custom. " mobileNumber=".$mobileNo. " walletActive=".$WalletActive?>">
		<input type="hidden" name="contact_phone" value="<?=$mobileNo?>">

		<?php
			$a = 1;
			$prodprice = 0;
			$shippingChrg = 0;
			$packingChrg = 0;
			$totalpay = 0;

			foreach($DECODE_DATA_ARRAY as $key => $val) {
				$packingChrg = isset($val->PackingPrice) ? $val->PackingPrice : 0;
				$prodprice = ($val->Price + $val->ProductSizePrice) * $val->ProductQty;
				$shippingChrg = isset($val->TimeSlotCharges) ? $val->TimeSlotCharges : 0;
				$totalpay = $prodprice + $packingChrg;

				foreach($val->Addon as $key => $value) {
					$totalpay += $value->Price;
				}

		?>
			<input type="hidden" name="item_name_<?=$a?>" value="<?=$val->ProductName?>">
			<!-- <input type="hidden" name="quantity_<?=$a?>" value="<?=$val->ProductQty?>"> -->
			<input type="hidden" name="amount_<?=$a?>" value="<?=$totalpay?>">
			<input type="hidden" name="shipping_<?=$a?>" value="<?=$shippingChrg?>">
			<input type="hidden" name="item_number_<?=$a?>" value="<?=$val->ProductID?>">
		<?php
			$a++;
			}
		?>

		<input type="hidden" name="currency_code" value="<?=PAYPAL_CURRENCY?>">
		<input type='hidden' name='notify_url' value='<?=PAYPAL_NOTIFY_URL?>'>
		<input type="hidden" name="return" value="<?=PAYPAL_RETURN_URL?>">
		<input type="hidden" name="cancel_return" value="<?=PAYPAL_CANCEL_URL?>">
		<input type="hidden" name="discount_amount_cart" value="<?=$promodisc?>">

		<script type="text/javascript">
			document.f1.submit();
		</script>
	</form>
</body>
</html>