<?php
require_once('../cfg.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

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

        $mail->setFrom('email@email.com', 'Panel administracyjny [Hodowla żółwia wodnego]');
        $mail->addAddress($email);

        $mail->isHTML(false);
        $mail->Subject = 'Przypomnienie hasła do panelu administracyjnego [Hodowla żółwia wodnego]';
        $mail->Body    = 'Haslo: ' . $pass;
        $mail->AltBody = 'Haslo: ' . $pass;

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
            
            if (isset($_POST['remind'])) {
                remindPassword();
                echo 'dfgdf';
            }
            
        }
    }
}

function getPage($id) {
    global $conn;
        
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (empty($row['id'])) {
        $title = 'Nie znaleziono strony';
    }
    else {
        $page['title'] = $row['page_title'];
        $page['content'] = $row['page_content'];
        $page['alias'] = $row['alias'];
        $page['status'] = $row['status'];
    }
    return $page;
}

function addPage() {
        $title = $_POST['title'];
        $alias = $_POST['alias'];
        $content = $_POST['content'];

        if ($_POST['status'] == 'on')
            $status = 1;
        else
            $status = 0;
    
        global $conn;

        $query = "INSERT INTO page_list (id, page_title, page_content, alias, status) VALUES (NULL, '$title', '$content', '$alias', '$status');";
        $result = mysqli_query($conn, $query);
        header("Location: ./admin.php");
        exit;
}

function editPage($id) {
        $title = $_POST['title'];
        $alias = $_POST['alias'];
        $content = $_POST['content'];

        if ($_POST['status'] == 'on')
            $status = 1;
        else
            $status = 0;
    
        global $conn;
        $id_clear = htmlspecialchars($id);

        $query = "UPDATE page_list SET page_title='$title', page_content='$content', alias='$alias', status='$status' WHERE id='$id_clear' LIMIT 1;";
        $result = mysqli_query($conn, $query);
        header("Location: ./admin.php");
        exit;
}

function deletePage($id) {
    global $conn;

    $id_clear = htmlspecialchars($id);
    $query = "DELETE FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php");
    exit;
}

function pageList()
{
    global $conn;
    $query = "SELECT * FROM page_list ORDER BY 'id' DESC LIMIT 100";
    $result = mysqli_query($conn, $query);
    $list = '<div class="main-header">
                <h1>Lista stron</h1>
            </div>';
    $list .= '<table class="table">
                    <tr>
                        <th>Nr</th>
                        <th>Tytuł</th>
                        <th>Status</th>
                        <th>Działania</th>
                    </tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['status'] == 1)
            $status = "Aktywna";
        else
            $status = "Nieaktywna";
            
        $list .= '<tr> <td>' . $row['id'] . '</td> <td>' . $row['page_title'] . '</td> <td>' . $status . '</td>';
        $list .= '<td>
                    <form method="POST" action="' . $_SERVER['REQUEST_URL'] . '" enctype="multipart/form-data">
                        <input type="hidden" id="page" name="page" value="' . $row['id'] . '">
                        <a href="./admin.php?id=edycja&page=' . $row['id'] . '" class="admin-btn"> Edytuj</a>
                        <input type="submit" name="delete" class="admin-btn" value="Usuń" />
                    </form>
                </td> </tr>';
    }

    $list .= '</table>';
    $list .= '<a href="./admin.php?id=nowa" class="btn">Dodaj nową stronę</a>';

    echo $list;
    if (isset($_POST['delete'])) {
        deletePage($_POST['page']);
    }
}

function addForm()
{
    $form = '<div class="main-header">
                <h1>Nowa strona</h1>
            </div>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                <div class="form-field">
                    <label for="title">Tytuł:</label>
                    <input type="text" name="title" id="title" required />
                </div>

                <div class="form-field">
                    <label for="alias">Alias:</label>
                    <input type="text" name="alias" id="alias" required />
                </div>

                <div class="form-field">
                    <label for="content">Treść:</label>
                    <textarea
                    name="content"
                    id="content"
                    cols="50"
                    rows="5"
                    required
                    ></textarea>
                </div>

                <div class="form-field status">
                    <label for="status">Aktywna:</label>
                    <input
                    type="checkbox"
                    name="status"
                    id="status"
                    checked
                    />
                </div>

                <div class="form-btn">
                    <button type="submit" name="add" class="btn">Zatwierdź</button>
                    <button type="reset" name="reset" class="btn">Resetuj</button>
                </div>
                </form>';
    echo $form;

    if (isset($_POST['add'])) {
        addPage();
    }
}

function editForm()
{
    echo '<div class="main-header">
                <h1>Edycja strony</h1>
                <h2>' . getPage($_GET['page'])['title'] . '</h2>
          </div>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">';
    $form .= '<div class="form-field">
                    <label for="title">Tytuł:</label>
                    <input type="text" name="title" id="title" value="' . getPage($_GET['page'])['title'] . '" required />
                </div>';

    $form .= '<div class="form-field">
                    <label for="alias">Alias:</label>
                    <input type="text" name="alias" id="alias" value="' . getPage($_GET['page'])['alias'] . '" required />
                </div>';

    $form .= '<div class="form-field">
                    <label for="content">Treść:</label>
                    <textarea
                    name="content"
                    id="content"
                    cols="50"
                    rows="5"
                    required
                    >' . getPage($_GET['page'])['content'] . '</textarea>
                </div>';

    $form .= '<div class="form-field status">
                    <label for="status">Aktywna:</label>
                    <input
                    type="checkbox"
                    name="status"
                    id="status"';
    if  (getPage($_GET['page'])['status'] == 1) {
        $form .= ' checked
                    />
                </div>';
    }

    else {
        $form .= '/>
                </div>';
    }

    $form .= '<div class="form-btn">
                    <button type="submit" name="edit" class="btn">Zatwierdź</button>
                    <button type="reset" name="reset" class="btn">Resetuj</button>
                </div>
                </form>';
                 
    echo $form;

    if (isset($_POST['edit'])) {
        editPage($_GET['page']);
    }
}
?>
