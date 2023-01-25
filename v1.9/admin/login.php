<?php
require_once('../cfg.php');

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function remindPassword()
{
    global $pass, $email;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ontariogirl94@gmail.com';
        $mail->Password   = 'oawd ifov aalx xuyv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setLanguage('pl', '../PHPMailer/language');
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('admin@email.com', 'Panel administracyjny [Hodowla żółwia wodnego]');
        $mail->addAddress($email);

        $mail->isHTML(false);
        $mail->Subject = 'Przypomnienie hasła do panelu administracyjnego [Hodowla żółwia wodnego]';
        $mail->Body    = 'Hasło: ' . $pass;
        $mail->AltBody = 'Hasło: ' . $pass;

        $mail->send();
        echo '<span class="alert">Przypomnienie hasła zostało wysłane.</span>';
    }
    
    catch (Exception $e) {
        echo '<span class="alert">Wystąpił błąd podczas wysyłania przypomnienia. <br />' . $mail->ErrorInfo . '</span>';
    }
}

function loginForm()
{
    global $user, $pass;
    $form = '<div class="main-header">
                <h1>Logowanie</h1>
            </div>
                <form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                    <div class="form-field">
                    <label for="user">Nazwa użytkownika:</label>
                    <input type="text" name="user" id="user" required />
                </div>

                <div class="form-field">
                    <label for="pass">Hasło:</label>
                    <input type="password" name="pass" id="pass" required />
                </div>

                    <div class="form-btn">
                        <button type="submit" name="login" class="btn">Zaloguj</button>
                    </div>
                </form>';
    echo $form;

    if (isset($_POST['login'])) {
        if ($_POST['user'] == $user && $_POST['pass'] == $pass) {
            $_SESSION['user'] = $user;
            header("Location: ./admin.php?id=lista");
            exit;
        }
        else {
            echo '<span class="alert">Podano niepoprawne dane logowania.</span>';
            echo '<form method="POST" action="' . $_SERVER['REQUEST_URL'] . '" enctype="multipart/form-data">
                        <input type="submit" name="remind" class="admin-btn" value="Przypomnij hasło" />
                  </form>';
        }
    }

    if (isset($_POST['remind'])) {
        remindPassword();
    }
}
?>