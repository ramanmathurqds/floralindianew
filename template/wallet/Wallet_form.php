<?php
//Include configuration file
require_once("Wallet_config.php");

$DATA_ARRAY = base64_decode($_POST["checkout_data"]);
$DECODE_DATA_ARRAY = json_decode($DATA_ARRAY);

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
$email			= $_POST["userEmail"];
$mobileNo		= isset($_POST["mobileNo"]) ? $_POST["mobileNo"] : '';
$UserID			= $_POST["UserID"];
$CartID			= $_POST["CartID"];
$WalletActive	= $_POST["UWallet"];
$WalletAmount	= $_POST["tgwllt"];
$txnid          = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

if(isset($_POST["dscp"]) && isset($_COOKIE['Pd'])) {
    $promodisc = isset($_POST["dscp"]) ? $_POST["dscp"] : $_SESSION['PromoDiscount'];
} else {
    $promodisc = 0;
}


$prodprice = 0;
$shippingChrg = 0;
$packingChrg = 0;
$totalpay = 0;
$productinfo = 'Products';

foreach($DECODE_DATA_ARRAY as $key => $val) {
    $packingChrg = isset($val->PackingPrice) ? $val->PackingPrice : 0;
    $prodprice = ($val->Price + $val->ProductSizePrice) * $val->ProductQty;
    $shippingChrg = isset($val->TimeSlotCharges) ? $val->TimeSlotCharges : 0;
    $totalpay += $prodprice + $packingChrg + $shippingChrg;

    foreach($val->Addon as $key => $value) {
        $totalpay += $value->Price;
    }
}

if($WalletAmount > 0 && $WalletActive == 1) {
    $totalpay = $WalletAmount - $totalpay;
    $walletUsed = 1;
} else {
    $walletUsed = 0;
}


?>
<html>
  <head>
  <script>
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

    function submitWalletForm() {
        var walletForm = document.forms.walletForm;
        walletForm.submit();
    }
  </script>
  </head>
  <body onload="submitWalletForm()">
     <form action="<?=DOMAIN.'wallet-success'?>" method="post" name="walletForm">
        <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
        <input type="hidden" name="firstname" value="<?=$fName?>">
		<input type="hidden" name="lastname" value="<?=$lName?>">
		<input type="hidden" name="address2" value="<?=$streetName.', '.$areaName?>">
        <input type="hidden" name="address1" value="<?=$blockNo?>">
        <input type="hidden" name="productinfo" value="<?=$productinfo?>">
		<input type="hidden" name="city" value="<?=$city?>">
		<input type="hidden" name="state" value="<?=$state?>">
		<input type="hidden" name="zipcode" value="<?=$postCode?>">
		<input type="hidden" name="country" value="<?=$country?>">
		<input type="hidden" name="phone" value="<?=$mobileNo?>">
        <input type="hidden" name="email" value="<?=$email?>">
        <input type="hidden" name="udf1" value="<?=$UserID?>">
        <input type="hidden" name="udf2" value="<?=$CartID?>">
        <input type="hidden" name="udf3" value="<?=$promodisc?>">
        <input type="hidden" name="udf4" value="<?=$walletUsed?>">

        <input type="hidden" name="amount" value="<?=$totalpay?>">
        <input type="submit" value="Submit" />
    </form>
  </body>
</html>
