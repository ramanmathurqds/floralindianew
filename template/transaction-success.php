<?php
    require_once("./phpmailer/class.phpmailer.php");

    if(!isset($_SESSION['uid'])) {
        header("Location:".DOMAIN);
    }

//Get payment information from PayPal 
// $txn_id = $_GET['tx'];
// $payment_gross = $_GET['amt'];
// $currency_code = $_GET['cc'];
// $payment_status = $_GET['st'];

// print_r($txn_id);
// print_r('<br/>');
// print_r($payment_gross);
// print_r('<br/>');
// print_r($currency_code);
// print_r('<br/>');
// print_r($payment_status);
// print_r('<br/>');

?>

<div class="order-success-page mt-w">
    <div class="container custom-container">
        <div class="breadcrumbs">
            <ul>
                <li><a href="<?=rtrim(DOMAIN, '/');?>">Home</a></li>
                <li><a href="javascript:;">Order Placed</a></li>
            </ul>
        </div>

        <div class="order-status-stage success-stage">
            <div class="order-status-img">
                <img src="<?=DOMAIN?>Content/assets/images/common/highfive.gif" alt="highfive" />
            </div>

            <div class="order-status-content">
                <div class="order-status-head text-center">
                    <h1>We've received your order</h1>
                    <h2>Order No - <?=$data->results[0]->OrderID?></h2>
                    <a target="_blank" href="<?=DOMAIN?>index.php?case=track-order&OrderID=<?=$data->results[0]->OrderID?>" class="c-btn c-btn-primary c-btn-semi-smooth mt-4 d-inline-block">Track Order</a>
                </div>

                <div class="order-body">
                    <div class="cart-box">
                        <div class="row">
                            <div class="col-12 custom-grid">
                                <div class="prime-box">
                                    <div class="prime-box-inner">
                                        <section class="box-title common-ele-padding">

                                            <i><img src="<?=DOMAIN?>Content/assets/images/common/shopping-bag.png" alt="shopping-bag"></i>
                                            <span class="spn-title">
                                                <span class="bold">Order Placed</span>
                                            </span>

                                        </section>
                                        <div class="custom-saperator"></div>

                                        <?php 
                                            if(isset($data->results) && !isset($data->results->error)) {
                                                foreach($data->results as $key => $val) {

                                                $bagTotal += ($val->ProductMrp + $val->ProductSizePrice) * $val->ProductQty;
                                                $bagTotalAfterDiscount += ($val->ProductPrice + $val->ProductSizePrice) * $val->ProductQty;
                                                $mrp = ($val->ProductMrp + $val->ProductSizePrice) * $val->ProductQty;
                                                $price = ($val->ProductPrice + $val->ProductSizePrice)  * $val->ProductQty;
                                                $discount = (($mrp - $price) / $mrp) * 100;
                                                $bagDiscount += $mrp - $price;
                                                $shippingCharge += $val->ShippingChrg;
                                                $packingCharge += $val->PackingChrg;
                                                $totalPayable = $bagTotalAfterDiscount + $shippingCharge + $packingCharge;
                                        ?>

                                        <div class="cart-item-wrapper common-ele-padding">
                                            <div class="cart-item">
                                                <div class="row">
                                                    <div class="col-12 col-xl-8">
                                                        <img src="<?=DOMAIN?><?=$val->ProductImage?>" alt="<?=$val->ProductName?>" class="img-product">
                                                        <h3 class="product-title"><?=$val->ProductName?></h3>
                                                        <p class="small-description"><?=$val->Size?></p>
                                                    
                                                        <p class="item-message"><?=$val->SenderMessage?></p>

                                                        <div class="cart-count">
                                                            <h3 class="product-title">Qty <?=$val->ProductQty?></h3>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-xl-4">
                                                        <div class="cart-amt-time">
                                                            <span class="price-and-discount">
                                                                <span class="common-price strike-price">
                                                                    <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="setCurrBasedPrice"><?=$mrp?></span>
                                                                </span>
                                                                <span class="common-price sales-price">
                                                                    <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="setCurrBasedPrice"><?=$price?></span>
                                                                </span>
                                                                <span class="discount color-secondary3">-<?=number_format($discount)?>%</span>
                                                            </span>

                                                            <?php if(!empty($val->ShippingChrg)) { ?>
                                                                <span class="shipping-charge color-secondary3">
                                                                    Shipping charges <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="shippingCharge"><?=$val->ShippingChrg?></span>
                                                                </span>
                                                            <?php } ?>
                                                            
                                                            <?php 
                                                                $input = $val->DeliveryDate;
                                                                $date = date('jS F Y', strtotime($input));
                                                            ?>
                                                            
                                                            <span class="delivery-time"><?=$date?> at <?=$val->DeliveryTimeSlot?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2 custom-saperator"></div>
                                            </div>
                                        </div>

                                        <?php }} ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-7"></div>
                            <div class="col-12 col-lg-5 custom-grid trans-success">
                                <div class="prime-box">
                                    <div class="prime-box-inner">
                                        <section class="box-title common-ele-padding">
                                            <i><span class="webCurrency payment-icon"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span></i> <span class="spn-title">
                                                <span class="color-grey-dark bold">Payment Details</span>
                                            </span>
                                        </section>
                                        <div class="custom-saperator"></div>

                                        <div class="payment-details-wrapper common-ele-padding">
                                            <table class="tbl-payment">
                                                <tbody>
                                                    <tr>
                                                        <td>Bag total</td>
                                                        <td><span class="totalbag"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="setCurrBasedPrice"><?=$bagTotal?></span></span></td>
                                                    </tr>

                                                    <tr id="Bad-Discount">
                                                        <td class="color-secondary3">Bag Discount</td>
                                                        <td class="color-secondary3"><span class="discountbag"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="setCurrBasedPrice"><?=$bagDiscount?></span></span></td>
                                                    </tr>

                                                    <tr id="subtotal">
                                                        <td>Sub total</td>
                                                        <td><span class="subtotalbag"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="setCurrBasedPrice"><?=$bagTotalAfterDiscount?></span></span></td>
                                                    </tr>

                                                    <tr>
                                                        <td>Shipping charges</td>
                                                        <td><span class="shippingbag"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="setCurrBasedPrice"><?=$shippingCharge?></span></span></td>
                                                    </tr>

                                                    <tr id="promoCode" class="d-none">
                                                        <td class="color-secondary3">Promo code discount</td>
                                                        <td class="color-secondary3">
                                                            <span class="promoCodebag"><span class="setDefaultCurrency"><span class="setCurrBasedPrice"><?=$CurrencyLogo?></span></span> </span>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td><strong>Grand Total</strong></td>
                                                        <td><strong><span class="finalbag"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="setCurrBasedPrice"><?=$totalPayable?></span></span></strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php


if(isset($_SESSION['isPaypalEmail']) && $_SESSION['isPaypalEmail'] === '1') {

    /***  Mail sending to receiver for successfull received order with details ***/
    $array = array('OrderID' => $data->results[0]->OrderID, 'FirstName' => $userData->results->FirstName);

    $template = file_get_contents(DOMAIN_URL."/emailers/order-placed.php");
    foreach($array as $key => $value) {
        $template = str_replace("{".$key."}", $value, $template);
    }

    $mail = new PHPMailer(true);

    $mail->SMTPDebug = 1;
    $mail->isSMTP();
    //$mail->SMTPSecure = 'ssl';
    $mail->Host = EMAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL_USERNAME;
    $mail->Password = EMAIL_PASSWORD;
    //$mail->SMTPSecure = "tls";
    //$mail->Port = 587;

    $mail->From = EMAIL_FROM;
    $mail->FromName = EMAIL_FROM_NAME;
    $mail->addAddress($_SESSION["inLogEmail"]);
    $mail->isHTML(true);
    $mail->Subject = "Order Placed with order ID ".$data->results[0]->OrderID." - FloralIndia";
    $mail->Body = $template;

    try {
        $mail->send();
        // echo "Message has been sent successfully";
    } catch (Exception $e) {
        // echo "Mailer Error: " . $mail->ErrorInfo;
    }
}


if((isset($_SESSION['isPaypalEmail']) && $_SESSION['isPaypalEmail'] === '1') || (isset($_SESSION['isPayuEmail']) && $_SESSION['isPayuEmail'] === '1')) {
    // Admin SMS Notification
    $message = '';
    $message .= 'You have received a new order number for '.$data->results[0]->OrderID;
    $message .= '\nDelivery date '.$date;
    $message .= '\nAmount '.$totalPayable;
    $message .= '\nKindly confirm the order at the earliest, customer is waiting for the order confirmation';

    $ch = curl_init(SMS_API_URL.SMS_USER."&apikey=".SMS_HORIZON_API."&mobile=".SMS_SEND_NUMBER."&senderid=".SMS_SENDER_ID."&message=".$message."&type=txt&tid=xyz");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    $error = curl_error($ch);
    

    $message1 = '';
    $message1 .= 'Thank you for placing your order('.$data->results[0]->OrderID.') request. Our team has received your order & will confirm shortly.';

    $ch = curl_init(SMS_API_URL.SMS_USER."&apikey=".SMS_HORIZON_API."&mobile=".$data->results[0]->MobileNumber."&senderid=".SMS_SENDER_ID."&message=".$message1."&type=txt&tid=xyz");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);


    // Admin Email
    /***  Mail sending to receiver for successfull received order with details ***/
    $detailHTML = '';
    foreach($data->results as $key => $val) {

        $totalProductCost = ($val->ProductPrice * $val->ProductQty) + $val->ShippingChrg;

        $detailHTML .= '<div class="detail-wrapper">
            <div style="background-color:transparent;">
                <div class="block-grid"
                    style="min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                    <div
                        style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12"
                            style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top; width: 600px;">
                            <div class="col_cont" style="width:100% !important;">
                                <div
                                    style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                    <div style="color:#555555;line-height:1.2;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                        <div
                                            style="line-height: 1.2; font-size: 12px; color: #555555; mso-line-height-alt: 14px;">
                                            
                                            <p style="background: #eeeeee;padding: 15px;border-radius: 10px;">
                                                <span style="color:#EF4794; font-size:18px;"><b><u>DELIVERY ADDRESS</u></b></span>
                                                <span style="display:block;margin-top:20px;font-size: 14px;color: #000; text-transform:uppercase">'.$val->DeliveryName.'</span>
                                                <span style="display:block;margin-top: 10px;font-size: 14px;color: #000;">'.$val->ShippingAddress.'</span>
                                                <span style="display:block;margin-top: 10px;font-size: 14px;color: #000;">Contact : '.$val->DeliveryContact.'</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background-color:transparent;">
                <div class="row">
                    <div class="col-3">
                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                            <div align="center" class="img-container center autowidth" style="padding-right: 0px;padding-left: 0px;">

                                <img align="center" alt="Alternate text" border="0" class="center autowidth" src='.DOMAIN.$val->ProductImage.'
                                style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 100%; max-width: 160px; display: block;" title="Alternate text" width="160" />

                            </div>
                        </div>
                    </div>
                                
                    <div class="col-9">
                        <div style="padding: 0 10px 0 10px; font-size:13px;">
                            <table class="order-items" style="width:100%">
                                <tr>
                                    <td>'.$val->ProductName.' - '.$val->Size.'</td>
                                    <td>'.$val->ProductQty.'</td>
                                </tr>
                                <tr>
                                    <td>Price</td>
                                    <td>'.$val->ProductPrice.'</td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td>'.$val->ShippingChrg.'</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>'.$totalProductCost.'</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-style:italic;color:#ee398a"><b>'.$val->DeliveryDate.' at '.$val->DeliveryTimeSlot.'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-style:italic; color:#738434"> <br /><b>MESSAGE :- '.$val->SenderMessage.'</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="hr-line"></div>
                </div>
            </div>
        </div>';
    }

    $detailHTML .= '<div style="background-color:transparent;">
                <div class="row">
                    <div class="col-3">
                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                            <div align="center" class="img-container center autowidth" style="padding-right: 0px;padding-left: 0px;">

                            </div>
                        </div>
                    </div>
                                
                    <div class="col-9">
                        <div style="padding: 0 10px 0 10px; font-size:13px;">
                            <table class="order-items" style="width:100%">
                                <tr>
                                    <td>Total Shipping</td>
                                    <td>'.$shippingCharge.'</td>
                                </tr>
                                <tr>
                                    <td>Grand Total</td>
                                    <td>'.$totalPayable.'</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="hr-line"></div>
                </div>
            </div>';

    $array = array('detailWrapper' => $detailHTML, 'orderID' => $data->results[0]->OrderID);

    $template = file_get_contents(HOST."/emailers/order-placed-admin.php");
    foreach($array as $key => $value) {
          $template = str_replace("{".$key."}", $value, $template);
    }


    $mail = new PHPMailer(true);

    $mail->SMTPDebug = 1;
    $mail->isSMTP();
    $mail->Host = EMAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL_USERNAME;
    $mail->Password = EMAIL_PASSWORD;

    $mail->From = EMAIL_FROM;
    $mail->FromName = EMAIL_FROM_NAME;
    $mail->addAddress(EMAIL_FROM);
    $mail->addCC(EMAIL_CC);
    $mail->isHTML(true);
    $mail->Subject = "Order Placed with order ID ".$OrderID." - FloralIndia";
    // $mail->Body = $tmp;
    $mail->Body = $template;

    try {
        $mail->send();
        // echo "Message has been sent successfully";
    } catch (Exception $e) {
        // echo "Mailer Error: " . $mail->ErrorInfo;
    }
}

if(isset($_SESSION['isPayuEmail'])) {
    $_SESSION['isPayuEmail'] == '0';
    unset($_SESSION["isPayuEmail"]);
}

if(isset($_SESSION['isPaypalEmail'])) {
    $_SESSION['isPaypalEmail'] == '0';
    unset($_SESSION["isPaypalEmail"]);
}

?>