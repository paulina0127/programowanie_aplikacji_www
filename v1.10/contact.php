<?php
require_once('./cfg.php');

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail()
{
    global $email;

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