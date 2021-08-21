<?php 

    namespace Listener;

    require_once("./config.php");
    require_once("lib/config_paypal.php");
    require('paypal_ipn_class.php');

    define("LOG_FILE", "PayPal_ipn.log");

    use PaypalIPN;

    $ipn = new PaypalIPN();

    $ipn->useSandbox();
    $verified = $ipn->verifyIPN();
    if ($verified) {

        $customStr = $_POST['custom'];
        preg_match_all('#custom=([^\s]+)#', $customStr, $userKey);

        preg_match_all('#mobileNumber=([^\s]+)#', $customStr, $mobileNumber);

        preg_match_all('#walletActive=([^\s]+)#', $customStr, $walletActive);
        // print_r($userKey[1][0]);

        // Payment data from PayPal
        $transactionID          = $_POST['txn_id'];
        $transactionTotal       = $_POST['mc_gross'];
        $transactionCurrency    = $_POST['mc_currency'];
        $firstname              = $_POST['first_name'];
        $paymentStatus          = $_POST['payment_status'];
        $payerEmail             = $_POST['payer_email'];
        $mobileNo               = $mobileNumber[1][0];
        $userID                 = $userKey[1][0];
        $walletStatus           = $walletActive[1][0];
        $transactionType        = $_POST['txn_type'];
        $productID              = $_POST['item_number'];
        $billingAddress         = $_POST['address_street'].','.$_POST['address_city'].'-'.$_POST['address_zip'].','.$_POST['address_state'].','.$_POST['address_country'];
        $discount               = !empty($_POST['discount_amount_cart']) ? $_POST['discount_amount_cart'] : 0;

        // Check if payment data exists with the same TXN ID.
        $APIURL = DOMAIN_URL.'/admin-panel/API/floralapi.php?case=';

        $APIData = 'fetchTransactionByID&TransactionID='.$transactionID;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $APIURL.$APIData,
            CURLOPT_RETURNTRANSFER 		=> true,
            CURLOPT_ENCODING 			=> "", 
            CURLOPT_HTTP_VERSION 		=> CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST 		=> "GET",
            CURLOPT_HTTPHEADER 			=> array(
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            )
        ));

        $jsonResponse = curl_exec($curl);
        $err_code = curl_error($curl);

        curl_close($curl);

        if ($err_code) {

            // echo "fetchTransactionByID - cURL Error #:" . $err_code;
            error_log(date('[Y-m-d H:i e] '). "fetchTransactionByID - cURL Error #:" . $err_code . PHP_EOL, 3, LOG_FILE);
        } else {

            $resp = json_decode($jsonResponse, true);

            if($resp['error'] == 1) {

                // Transaction ID received from PayPal is dupliate
                error_log(date('[Y-m-d H:i e] '). "duplicte transaction ID (Failed): $APIURL $APIData1" . PHP_EOL, 3, LOG_FILE);
            } else {

                $paymentStatusCode = '0';

                // Insert transaction data that is received from PayPal
                if($paymentStatus == 'Completed') {
                    $paymentStatusCode = '1';
                } elseif($paymentStatus == 'On hold') {
                    $paymentStatusCode = '2';
                } elseif($paymentStatus == 'Held') {
                    $paymentStatusCode = '3';
                } elseif($paymentStatus == 'Temporary hold') {
                    $paymentStatusCode = '4';
                } elseif($paymentStatus == 'Refunded') {
                    $paymentStatusCode = '5';
                } elseif($paymentStatus == 'Returned') {
                    $paymentStatusCode = '6';
                } elseif($paymentStatus == 'Denied') {
                    $paymentStatusCode = '7';
                } elseif($paymentStatus == 'Unclaimed') {
                    $paymentStatusCode = '8';
                }

                $APIData1 = 'insertTransactionDetails&UserID='.urlencode($userID).'&TransactionID='.urlencode($transactionID).'&PayerEmail='.urlencode($payerEmail).'&TransactionType='.urlencode($transactionType).'&PaymentStatus='.urlencode($paymentStatus).'&TransactionTotal='.urlencode($transactionTotal).'&TransactionCurrency='.urlencode($transactionCurrency).'&Discount='.urlencode($discount).'&PaymentStatusCode='.urlencode($paymentStatusCode).'&PaymentGateway=PayPal&UserWalletStatus='.urlencode($walletStatus);

                error_log(date('[Y-m-d H:i e] '). "transaction ID (Passed): $APIURL $APIData1" . PHP_EOL, 3, LOG_FILE);

                $curl1 = curl_init();

                curl_setopt_array($curl1, array(
                    CURLOPT_URL => $APIURL.$APIData1,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER 			=> array(
                        "Cache-Control: no-cache",
                        "Content-Type: application/json"
                    )
                ));

                $response = curl_exec($curl1);
                $errcode = curl_error($curl1);

                curl_close($curl1);
                
                $resp = json_decode($response, true);
                
                error_log(date('[Y-m-d H:i e] '). "transaction detail response: $resp" . PHP_EOL, 3, LOG_FILE);

                if ($errcode['error'] == 1) {

                    // echo "insertTransactionDetails - cURL Error #:" . $errcode;
                    error_log(date('[Y-m-d H:i e] '). "insertTransactionDetails - cURL Error #:" . $err_code . PHP_EOL, 3, LOG_FILE);
                } else {

                    error_log(date('[Y-m-d H:i e] '). "insertOrderDetails #:" . PHP_EOL, 3, LOG_FILE);

                    preg_match_all('#crtid=([^\s]+)#', $customStr, $crtid);

                    $cartids = explode(",", $crtid[1][0]);
                    $OrderID = 'FLRL'.strtoupper(uniqid());



                    // Insert Wallet details in DB

                    $APIData3 = 'insertWalletDetails&UserID='.urlencode($userID).'&TransactionID='.urlencode($transactionID).'&TransactionCurrency='.urlencode($transactionCurrency).'&CartID='.urlencode($crtid[1][0]);

                    error_log(date('[Y-m-d H:i e] '). "insertWalletDetails Success: $APIURL $APIData3" . PHP_EOL, 3, LOG_FILE);

                    $curl2 = curl_init();

                    curl_setopt_array($curl2, array(
                            CURLOPT_URL => $APIURL.$APIData3,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => array(
                                "Cache-Control: no-cache",
                                "Content-Type: application/json"
                            )
                    ));

                    $orderResp = curl_exec($curl2);
                    $orderDtlErr = curl_error($curl2);

                    curl_close($curl2);

                    if ($orderDtlErr) {
                            error_log(date('[Y-m-d H:i e] '). "insertWalletDetails Failed - cURL Error #:" . $orderDtlErr . PHP_EOL, 3, LOG_FILE);
                    }



                    // Insert Order details
                    foreach ($cartids as $val) {

                        // Insert Order details in DB
                        $APIData2 = 'insertOrderDetails&UserID='.urlencode($userID).'&OrderID='.urlencode($OrderID).'&CartID='.urlencode($val).'&PaymentStatus='.urlencode($paymentStatus).'&PaymentStatusCode='.urlencode($paymentStatusCode).'&TransactionID='.urlencode($transactionID).'&BillingAddress='.urlencode($billingAddress).'&MobileNumber='.urlencode($mobileNo);

                        error_log(date('[Y-m-d H:i e] '). "insertOrderDetails Success: $APIURL $APIData2" . PHP_EOL, 3, LOG_FILE);

                        $curl2 = curl_init();

                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $APIURL.$APIData2,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER 	=> array(
                                "Cache-Control: no-cache",
                                "Content-Type: application/json"
                            )
                        ));

                        $orderResp = curl_exec($curl2);
                        $orderDtlErr = curl_error($curl2);

                        curl_close($curl2);

                        if ($orderDtlErr) {
                            error_log(date('[Y-m-d H:i e] '). "insertOrderDetails Failed - cURL Error #:" . $orderDtlErr . PHP_EOL, 3, LOG_FILE);
                        }
                    }
                }

                if(DEBUG) {
                    error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
                }
            }
        }
    }

    // Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
    header("HTTP/1.1 200 OK");
?>