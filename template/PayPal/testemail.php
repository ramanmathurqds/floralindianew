<?php

require_once("./config.php");
require_once './phpmailer/class.phpmailer.php';

/***  Mail sending to receiver for successfull received order with details ***/ 
    $emailHTML = '';

    $array = array('OrderID' => $OrderID, 'FirstName' => $firstname);
                            
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
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Order Confirmation - FloralIndia";
    $mail->Body = $template;

    try {
        $mail->send();
        echo "Message has been sent successfully";

        error_log(date('[Y-m-d H:i e] '). "emailer sent Success" . PHP_EOL, 3, LOG_FILE);
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;

        error_log(date('[Y-m-d H:i e] '). "emailer sent failed" . $mail->ErrorInfo . PHP_EOL, 3, LOG_FILE);
    }

    ?>