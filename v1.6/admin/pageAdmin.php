<?php
require_once('../cfg.php');

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
        }
        else {
            echo '<span id="alert">Podano niepoprawne dane logowania.</span>';
        }
    }
}

function getTitle($id) {
    global $conn;
        
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (empty($row['id'])) {
        $title = 'Nie znaleziono strony';
    }
    else {
        $title = $row['page_title'];
    }
    return $title;
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
}

function deletePage($id) {
    global $conn;

    $id_clear = htmlspecialchars($id);
    $query = "DELETE FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php");
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
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="post" enctype="multipart/form-data">
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
                <h2>' . getTitle($_GET['page']) . '</h2>
          </div>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="post" enctype="multipart/form-data">
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
