<?php

      // error_reporting(0);
	// error_reporting(E_ALL);
	// ini_set('display_errors', 'on');

      session_start();
      $_SESSION["inLogEmail"] = $_POST["email"];
      $_SESSION["uid"] = $_POST["udf1"];

      //Include payu configuration file
      require_once("./config.php");
      require_once("payu_config.php");

      define("LOG_FILE", "PayU.log");

      /* Start PayU setup for success page */

      $status = $_POST["status"];
      $firstname = $_POST["firstname"];
      $amount = $_POST["amount"];
      $txnid = $_POST["txnid"];
      $posted_hash = $_POST["hash"];
      $key = $_POST["key"];
      $productinfo = $_POST["productinfo"];
      $email = $_POST["email"];
      $address2 = $_POST["address2"];
      $address1 = $_POST["address1"];
      $city = $_POST["city"];
      $state = $_POST["state"];
      $postCode = $_POST["zipcode"];
      $country = $_POST["country"];
      $user_ID = $_POST["udf1"];
      $cartID = $_POST["udf2"];
      $discountValue = $_POST["udf3"];
      $walletUsed = $_POST["udf4"];
      $mode = $_POST["mode"];
      $mobileNo = $_POST["phone"];
      $salt = $SALT;

      // print_r($walletUsed);
      // print_r('<br/>');
      // print_r($status);
      // print_r('<br/>');
      // print_r($firstname);
      // print_r('<br/>');
      // print_r($amount);
      // print_r('<br/>');
      // print_r($txnid);
      // print_r('<br/>');
      // print_r($posted_hash);
      // print_r('<br/>');
      // print_r($key);
      // print_r('<br/>');
      // print_r($productinfo);
      // print_r('<br/>');
      // print_r($email);
      // print_r('<br/>');
      // print_r($address2);
      // print_r('<br/>');
      // print_r($address1);
      // print_r('<br/>');
      // print_r($city);
      // print_r('<br/>');
      // print_r($postCode);
      // print_r('<br/>');
      // print_r($country);
      // print_r('<br/>');
      // print_r($mobileNo);
      // print_r('<br/>');

      // Salt should be same Post Request 
      if (isset($_POST["additionalCharges"])) {
            $additionalCharges = $_POST["additionalCharges"];
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||'.$walletUsed.'|'.$discountValue.'|'.$cartID.'|'.$user_ID.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
      } else {
            $retHashSeq = $salt.'|'.$status.'|||||||'.$walletUsed.'|'.$discountValue.'|'.$cartID.'|'.$user_ID.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
      }
      
      $hash = hash("sha512", $retHashSeq);

      // print_r($hash);
      // print_r('<br/>');

      if ($hash != $posted_hash) {
            echo "Invalid Transaction. Please try again";
      } else {

            if($mode == 'CC') {
                  $txn_type = 'Credit Card';
            } elseif ($mode == 'DC') {
                  $txn_type = 'Debit Card';
            } elseif ($mode == 'NB') {
                  $txn_type = 'Net Banking';
            }

            // Payment data from Payu
            $transactionID          = $txnid;
            $transactionTotal       = $amount;
            $transactionCurrency    = isset($_POST['mc_currency']) ? $_POST['mc_currency'] : 'INR';
            $paymentStatus          = $status;
            $payerEmail             = $email;
            $userID                 = $user_ID;
            $transactionType        = $txn_type;
            $billingAddress         = $address1.','.$address2.'-'.$postCode.','.$state.','.$country;
            $discount               = !empty($discountValue) ? $discountValue : 0;

            // Check if payment data exists with the same TXN ID.
            // $APIURL = 'https://floralindia.com/admin-panel/API/floralapi.php?case=';
            $APIURL = API_URL;

            $paymentStatusCode = '0';

            // Insert transaction data that is received from PayPal
            if($paymentStatus == 'success') {
                  $paymentStatusCode = '1';
            }

            $APIData1 = 'insertTransactionDetails&UserID='.urlencode($userID).'&TransactionID='.urlencode($transactionID).'&PayerEmail='.urlencode($payerEmail).'&TransactionType='.urlencode($transactionType).'&PaymentStatus='.urlencode($paymentStatus).'&TransactionTotal='.urlencode($transactionTotal).'&TransactionCurrency='.urlencode($transactionCurrency).'&Discount='.urlencode($discount).'&PaymentStatusCode='.urlencode($paymentStatusCode).'&PaymentGateway=PayU&UserWalletStatus='.urlencode($walletUsed);

            error_log(date('[Y-m-d H:i e] '). "<br/>transaction ID (Passed): $APIURL $APIData1" . PHP_EOL, 3, LOG_FILE);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                  CURLOPT_URL => $APIURL.$APIData1,
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

            $response = curl_exec($curl);
            $errcode = curl_error($curl);

            curl_close($curl);

            $resp = json_decode($response, true);

            error_log(date('[Y-m-d H:i e] '). "transaction detail response: $resp" . PHP_EOL, 3, LOG_FILE);

            if ($errcode['error'] == 1) {

                  // echo "insertTransactionDetails - cURL Error #:" . $errcode;
                  error_log(date('[Y-m-d H:i e] '). "insertTransactionDetails - cURL Error #:" . $err_code . PHP_EOL, 3, LOG_FILE);
            } else {

                  require_once './phpmailer/class.phpmailer.php';

                  $cartids = explode(",", $cartID);
                  $OrderID = 'FLRL'.strtoupper(uniqid());

                  // Insert Wallet details in DB

                  $APIData3 = 'insertWalletDetails&UserID='.urlencode($userID).'&TransactionID='.urlencode($transactionID).'&TransactionCurrency='.urlencode($transactionCurrency).'&CartID='.urlencode($cartID);

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

                  error_log(date('[Y-m-d H:i e] '). "insertOrderDetails #:" . $response . PHP_EOL, 3, LOG_FILE);


                  foreach ($cartids as $val) {

                        // Insert Order details in DB
                        $APIData2 = 'insertOrderDetails&UserID='.urlencode($userID).'&OrderID='.urlencode($OrderID).'&CartID='.urlencode($val).'&PaymentStatus='.urlencode($paymentStatus).'&PaymentStatusCode='.urlencode($paymentStatusCode).'&TransactionID='.urlencode($transactionID).'&BillingAddress='.urlencode($billingAddress).'&MobileNumber='.urlencode($mobileNo);

                        error_log(date('[Y-m-d H:i e] '). "insertOrderDetails Success: $APIURL $APIData2" . PHP_EOL, 3, LOG_FILE);

                        $curl1 = curl_init();

                        curl_setopt_array($curl1, array(
                              CURLOPT_URL => $APIURL.$APIData2,
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

                        $orderResp = curl_exec($curl1);
                        $orderDtlErr = curl_error($curl1);

                        curl_close($curl1);

                        if ($orderDtlErr) {
                              error_log(date('[Y-m-d H:i e] '). "insertOrderDetails Failed - cURL Error #:" . $orderDtlErr . PHP_EOL, 3, LOG_FILE);
                        }
                  }

                  /***  Mail sending to receiver for successfull received order with details ***/
                  $array = array('OrderID' => $OrderID, 'FirstName' => $firstname);

                  $template = file_get_contents(HOST_URL."/emailers/order-placed.php");
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
                  $mail->addAddress($email);
                  $mail->isHTML(true);
                  $mail->Subject = "Order Placed with order ID ".$OrderID." - FloralIndia";
                  $mail->Body = $template;

                  try {
                        $mail->send();
                        echo "Message has been sent successfully";
                  } catch (Exception $e) {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                  }

                  //whatsapp notification to customer
                  // $arr = json_encode(array(
                  //       "phone"=>"919833189426",
                  //       "body"=>"Hello $firstname. Thank you for placing your order($OrderID) request. Our team has received your order & will confirm shortly."
                  // ));
                  // $url = "https://eu222.chat-api.com/instance219597/messages?token=6r02eshv7jvmrar0";
                  // $ch = curl_init();
                  // curl_setopt($ch, CURLOPT_URL, $url);
                  // curl_setopt($ch, CURLOPT_POST, true);
                  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                  // curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
                  // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  //       'content-type:application/json',
                  //       'content-length:'.strlen($arr)
                  // ));
                  // $result = curl_exec($ch);
                  // curl_close($ch);
                  // echo $result;

                  $_SESSION['isPayuEmail'] = '1';

                  header("Location:".HOST_URL.'/index.php?case=checkout-success&tx='.$transactionID.'&UserID='.$userID);
            }
      }

      /* End PayU setup for success page */
?>