<?php
ob_start();
require_once('../cfg.php');

function getCategory($id) {
    global $conn;
        
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM category_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $category['name'] = $row['name'];
    $category['main_category'] = $row['main_category'];

    return $category;
}

function addCategory() {
    global $conn;

    $name = $_POST['name'];
    if (!empty($_POST['main_category'])) {
        $main_category = $_POST['main_category'];
    }
    else {
        $main_category = 0;
    }

    $query = "INSERT INTO category_list (id, name, main_category) VALUES (NULL, '$name', '$main_category');";
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php?id=lista_kategorii");
    exit;
}

function editCategory($id) {
    global $conn;
    
    $name = $_POST['name'];
    $main_category = $_POST['main_category'];
    
    $id_clear = htmlspecialchars($id);
    $query = "UPDATE category_list SET name='$name', main_category='$main_category' WHERE id='$id_clear' LIMIT 1;";
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php?id=lista_kategorii");
    exit;
}

function deleteCategory($id) {
    global $conn;

    $id_clear = htmlspecialchars($id);
    $query = "DELETE FROM category_list WHERE id='$id_clear' LIMIT 1";

    try { 
        $result = mysqli_query($conn, $query);
    }
    catch(Exception $e) {
        echo '<span class="alert">Wystąpił błąd</span>
        <p style="text-align:center">Są produkty, które znajdują się w tej kategorii.<br />Nie można jej usunąć.</p>';
    }
    if (!$e) {
        $query = "SELECT * FROM category_list WHERE main_category='$id_clear'";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $sql = "UPDATE category_list SET main_category='0' WHERE id=" . $row['id'] . " LIMIT 1";
            $upt = mysqli_query($conn, $sql);
        }
        header("Location: ./admin.php?id=lista_kategorii");
        exit;
    }
}

function categoryList()
{
    global $conn;
    $query = "SELECT * FROM category_list ORDER BY 'id' DESC LIMIT 100";
    $result = mysqli_query($conn, $query);
    $list = '<div class="main-header">
                <h1>Lista kategorii</h1>
            </div>';

    $cat = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($cat, $row);
    }

    $list .= '<table class="table">
                    <tr>
                        <th>Nr</th>
                        <th>Nazwa</th>
                        <th>Kategoria główna</th>
                        <th>Działania</th>
                    </tr>';

    for ($i = 0; $i < count($cat); $i++) {
        if (($cat[$i])['main_category'] == 0) {
            $main_category = " ";
            $list .= '<tr> <td>' . ($cat[$i])['id'] . '</td> <td>' . ($cat[$i])['name'] . '</td> <td>' . $main_category . '</td>';
            $list .= '<td>
                    <form method="POST" action="' . $_SERVER['REQUEST_URL'] . '" enctype="multipart/form-data">
                        <input type="hidden" id="category" name="category" value="' . ($cat[$i])['id'] . '">
                        <a href="./admin.php?id=edycja_kategorii&category=' . ($cat[$i])['id'] . '" class="admin-btn">Edytuj</a>
                        <a href="./delete_page.php?category=' . ($cat[$i])['id'] . '" class="admin-btn">Usuń</a>
                    </form>
                </td> </tr>';

            for ($j = 0; $j < count($cat); $j++) {
                if (($cat[$j])['main_category'] == ($cat[$i])['id']) {
                    $main_category = ($cat[$i])['name'];
                    $list .= '<tr> <td>' . ($cat[$j])['id'] . '</td> <td>' . ($cat[$j])['name'] . '</td> <td>' . $main_category . '</td>';
                    $list .= '<td>
                    <form method="POST" action="' . $_SERVER['REQUEST_URL'] . '" enctype="multipart/form-data">
                        <input type="hidden" id="category" name="category" value="' . ($cat[$j])['id'] . '">
                        <a href="./admin.php?id=edycja_kategorii&category=' . ($cat[$j])['id'] . '" class="admin-btn">Edytuj</a>
                        <a href="./delete_page.php?category=' . ($cat[$j])['id'] . '" class="admin-btn">Usuń</a>
                    </form>
                </td> </tr>';
                }
            }
        }
    }

    $list .= '</table>';
    $list .= '<a href="./admin.php?id=nowa_kategoria" class="btn">Dodaj nową kategorię</a>';

    echo $list;
}

function addCategoryForm()
{
    $form = '<div class="main-header">
                <h1>Nowa kategoria</h1>
            </div>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                <div class="form-field">
                    <label for="name">Nazwa:</label>
                    <input type="text" name="name" id="name" required />
                </div>

                <div class="form-field">
                    <label for="main_category">Kategoria główna:</label>
                    <input type="text" name="main_category" id="main_category"/>
                </div>

                <div class="form-btn">
                    <button type="submit" name="add" class="btn">Zatwierdź</button>
                    <button type="reset" name="reset" class="btn">Resetuj</button>
                </div>
                </form>';
    echo $form;

    if (isset($_POST['add'])) {
        addCategory();
    }
}

function editCategoryForm()
{
    echo '<div class="main-header">
                <h1>Edycja kategorii</h1>
                <h2>' . getCategory($_GET['category'])['name'] . '</h2>
          </div>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">';
    $form .= '<div class="form-field">
                    <label for="name">Nazwa:</label>
                    <input type="text" name="name" id="name" value="' . getCategory($_GET['category'])['name'] . '" required />
                </div>';

    $form .= '<div class="form-field">
                    <label for="main_category">Kategoria główna:</label>
                    <input type="number" name="main_category" id="main_category" value="' . getCategory($_GET['category'])['main_category'] . '"/>
                </div>';

    $form .= '<div class="form-btn">
                    <button type="submit" name="edit" class="btn">Zatwierdź</button>
                    <a href="javascript:history.go(-1);" class="btn">Anuluj</a>
                </div>
                </form>';
                 
    echo $form;

    if (isset($_POST['edit'])) {
        editCategory($_GET['category']);
    }
}
?>