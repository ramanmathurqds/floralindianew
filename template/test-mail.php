<?php
    require_once("./phpmailer/class.phpmailer.php");
    $mail = new PHPMailer(true);

    $mail->SMTPDebug = 1;
    $mail->isSMTP();
    $mail->Port = 465;
    $mail->Host = EMAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL_USERNAME;
    $mail->Password = EMAIL_PASSWORD;

    $mail->From = EMAIL_FROM;
    $mail->FromName = EMAIL_FROM_NAME;
    $mail->addAddress(EMAIL_FROM);
    $mail->addCC(EMAIL_CC);
    $mail->isHTML(true);
    $mail->Subject = "Test Subjet";
    // $mail->Body = $tmp;
    $mail->Body = "Test Body";

    try {
        $mail->send();
        echo "Message has been sent successfully";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
?>