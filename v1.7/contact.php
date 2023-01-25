<?php
require_once('./cfg.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

function sendMail()
{
    global $email;

    // $header = 'From: Formularz kontaktowy <' . $email . '>';
    // $header .= "MIME-Version: 1.0\n Content-Type: text/plain; charset=utf-8\n";
    // $header .= "X-Sender: <" . $mail['email'] . ">\n";
    // $header .= "X-Mailer: PRapWWW mail 1.2\n";
    // $header .= "X-Priority: 3\n";
    // $header .= "Return-Path: <" . $mail['email'] . ">\n";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ontariogirl94@gmail.com';
        $mail->Password   = 'oawd ifov aalx xuyv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setLanguage('pl', './PHPMailer/language');
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($_POST['email'], $_POST['name'] . ' ' . $_POST['lastname']. ' ' . '[Hodowla żółwia wodnego]');
        $mail->addAddress($email);
        $mail->addReplyTo($_POST['email'], $_POST['name'] . ' ' . $_POST['lastname']);

        $mail->isHTML(false);
        $mail->Subject = $_POST['subject'];
        $mail->Body    = $_POST['message'];
        $mail->AltBody = $_POST['message'];

        $mail->send();
        return '<span class="alert">Wiadomość wysłana.</span>';
    }
    
    catch (Exception $e) {
        return '<span class="alert">Wystąpił błąd podczas wysyłania wiadomości. <br />' . $mail->ErrorInfo . '</span>';
    }
}
?>