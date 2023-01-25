<?php
ob_start();
require_once('../cfg.php');
require_once('./categoryAdmin.php');

function getProduct($id) {
    global $conn;
        
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM product_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $product['name'] = $row['name'];
    $product['description'] = $row['description'];
    $product['size'] = $row['size'];
    $product['category'] = $row['category'];
    $product['subcategory'] = $row['subcategory'];
    $product['price'] = $row['price'];
    $product['tax'] = $row['tax'];
    $product['quantity'] = $row['quantity'];
    $product['available'] = $row['available'];
    $product['photo'] = $row['photo'];
    $product['expires'] = $row['expires'];
    $product['created'] = $row['created'];
    $product['modified'] = $row['modified'];

    return $product;
}

function addProduct() {
    global $conn;

    // photo handling
    $dir = "uploads/";
    $fileName = basename($_FILES["photo"]["name"]);
    $path = $dir . $fileName;
    $fileType = pathinfo($path, PATHINFO_EXTENSION);
    $allowTypes = array('jpg','JPG','png', 'PNG', 'jpeg', 'JPEG');
    $photo = base64_encode(file_get_contents(addslashes($_FILES["photo"]["tmp_name"])));
    
    if(!in_array($fileType, $allowTypes)) {
        echo '<span class="alert">Wybrano plik z nieprawidłowym typem. Wybierz plik typu JPG, JPEG lub PNG</span>';
        exit;
    }

    $name = $_POST['name'];
    $description = $_POST['description'];
    $size = $_POST['size'];
    $subcategory = $_POST['subcategory'];
    $category = getCategory($subcategory)['main_category'];
    $price = $_POST['price'];
    $tax = $_POST['tax'];
    $quantity = $_POST['quantity'];

    $expires = $_POST['expires'];
    $created = date('Y-m-d');
    $modified = date('Y-m-d');

    if ($category == 0) {
        $category = $subcategory;
    }

    if ($expires >= $modified && $quantity > 0) {
        $available = 1;
    }
    else {
        $available = 0;
    }

    if ($price < 0) {
        echo '<span class="alert">Cena netto musi być liczbą dodatnią</span>';
        exit;
    }
    else if ($tax < 0) {
        echo '<span class="alert">Podatek VAT musi być liczbą dodatnią</span>';
        exit;
    }

    $query = "INSERT INTO product_list (id, name, description, size, category, subcategory, price, tax, quantity, available, photo, expires, created, modified) VALUES (NULL, '$name', '$description', '$size', '$category', '$subcategory', '$price', '$tax', '$quantity', '$available', '$photo', '$expires', '$created', '$modified');";
    echo $query;
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php?id=lista_produktow");
    exit;
}

function editProduct($id) {
    global $conn;

    // photo handling
    if (!empty($_FILES['photo']['name'])) {
        $dir = "uploads/";
        $fileName = basename($_FILES["photo"]["name"]);
        $path = $dir . $fileName;
        $fileType = pathinfo($path, PATHINFO_EXTENSION);
        $allowTypes = array('jpg','JPG','png', 'PNG', 'jpeg', 'JPEG');
        $photo = base64_encode(file_get_contents(addslashes($_FILES["photo"]["tmp_name"])));
    
        if(!in_array($fileType, $allowTypes)) {
            echo '<span class="alert">Wybrano plik z nieprawidłowym typem. Wybierz plik typu JPG, JPEG lub PNG</span>';
            exit;
        }
    }

    $name = $_POST['name'];
    $description = $_POST['description'];
    $size = $_POST['size'];
    $subcategory = $_POST['subcategory'];
    $category = getCategory($subcategory)['main_category'];
    $price = $_POST['price'];
    $tax = $_POST['tax'];
    $quantity = $_POST['quantity'];

    $expires = $_POST['expires'];
    $modified = date('Y-m-d');

    if ($category == 0) {
        $category = $subcategory;
    }

    if ($expires >= $modified && $quantity > 0) {
        $available = 1;
    }
    else {
        $available = 0;
    }

    if ($price < 0) {
        echo '<span class="alert">Cena netto musi być liczbą dodatnią</span>';
        exit;
    }
    else if ($tax < 0) {
        echo '<span class="alert">Podatek VAT musi być liczbą dodatnią</span>';
        exit;
    }

    $id_clear = htmlspecialchars($id);

    $query = "UPDATE product_list SET name='$name', description='$description', size='$size', category='$category', subcategory='$subcategory', price='$price', tax='$tax', quantity='$quantity', available='$available', photo='$photo', expires='$expires', modified='$modified' WHERE id='$id_clear' LIMIT 1;";

    if (empty($_FILES['photo']['name'])) {
        $query = "UPDATE product_list SET name='$name', description='$description', size='$size', category='$category', subcategory='$subcategory', price='$price', tax='$tax', quantity='$quantity', available='$available', expires='$expires', modified='$modified' WHERE id='$id_clear' LIMIT 1;";
    }
    
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php?id=lista_produktow");
    exit;
}

function deleteProduct($id) {
    global $conn;

    $id_clear = htmlspecialchars($id);
    $query = "DELETE FROM product_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php?id=lista_produktow");
    exit;
}

function productList()
{
    global $conn;
    $query = "SELECT * FROM product_list ORDER BY 'id' DESC LIMIT 100";
    $result = mysqli_query($conn, $query);
    $list = '<div class="main-header">
                <h1>Lista produktów</h1>
            </div>';

    $list .= '<table class="table">
                    <tr>
                        <th>Nr</th>  
                        <th>Zdjęcie</th>  
                        <th>Nazwa</th>
                        <th>Opis</th>
                        <th>Waga</th>
                        <th>Kategoria</th>
                        <th>Cena netto</th>
                        <th>Podatek VAT</th>
                        <th>Dostępny</th>
                        <th>Dostępna ilość</th>
                        <th>Data wygaśnięcia</th>
                        <th>Data utworzenia</th>
                        <th>Data modyfikacji</th>
                        <th>Działania</th>
                    </tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        $subcategory = getCategory($row['subcategory'])['name'];
        $category = '';
        if ($row['category'] != $row['subcategory']) {
            $category = '(' . getCategory($row['category'])['name'] . ')';
        }

        if ($row['available'] == 1) {
            $available = "Tak";
        }
        else {
            $available = "Nie";
        }
            
        $list .= '<tr> <td>' . $row['id'] . '</td>';
        $list .= '<td><img style="height: 100px; width: auto; display: block;" src="data:image; base64,' . $row['photo'] . '"></td>';

        $list .= '<td>' . $row['name'] . '</td>' . 
                 '<td><div class="table-row">' . $row['description'] . '</div></td>' .
                 '<td>' . $row['size'] . '</td>' .
                 '<td>' . $subcategory . ' ' . $category . '</td>' . 
                 '<td>' . $row['price'] . 'zł</td>' .
                 '<td>' . $row['tax'] . '%</td>' .
                 '<td>' . $available . '</td>' .
                 '<td>' . $row['quantity']  . '</td>' .
                 '<td>' . $row['expires'] . '</td>' . 
                 '<td>' . $row['created'] . '</td>' .
                 '<td>' . $row['modified'] . '</td>';

        $list .= '<td>
                        <form method="POST" action="' . $_SERVER['REQUEST_URL'] . '" enctype="multipart/form-data">
                            <input type="hidden" id="product" name="product" value="' . $row['id'] . '">
                            <a href="./admin.php?id=edycja_produktu&product=' . $row['id'] . '" class="admin-btn">Edytuj</a>
                            <a href="./delete_page.php?product=' . $row['id'] . '" class="admin-btn">Usuń</a>
                        </form>
                </td> </tr>';
    }
    
    $list .= '</table>';
    $list .= '<a href="./admin.php?id=nowy_produkt" class="btn">Dodaj nowy produkt</a>';

    echo $list;
}

function addProductForm()
{
    $form = '<div class="main-header">
                <h1>Nowy produkt</h1>
            </div>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                <div class="double-form-field">
                    <div class="form-field">
                        <label for="name">Nazwa:</label>
                        <input type="text" name="name" id="name" required />
                    </div>

                    <div class="form-field">
                        <label for="photo">Zdjęcie:</label>
                        <input type="file" name="photo" id="photo" required />
                    </div>
                </div>

                <div class="form-field">
                    <label for="description">Opis:</label>
                    <textarea
                    name="description"
                    id="description"
                    cols="50"
                    rows="5"
                    required
                    ></textarea>
                </div>
                
                <div class="double-form-field">
                    <div class="form-field">
                        <label for="size">Waga:</label>
                        <input type="text" name="size" id="size"/>
                    </div>

                    <div class="form-field">
                        <label for="category">Kategoria:</label>
                        <input type="number" name="subcategory" id="subcategory" required />
                    </div>
                </div>

                <div class="double-form-field">
                    <div class="form-field">
                        <label for="price">Cena netto:</label>
                        <input type="number" name="price" id="price" step="0.01" required />
                    </div>

                    <div class="form-field">
                        <label for="tax">Podatek VAT:</label>
                        <input type="number" name="tax" id="tax" step="0.01" required />
                    </div>
                </div>

                <div class="double-form-field">
                    <div class="form-field">
                        <label for="quantity">Dostępna ilość:</label>
                        <input type="number" name="quantity" id="quantity" required />
                    </div>

                    <div class="form-field">
                        <label for="expires">Data wygaśnięcia:</label>
                        <input type="date" name="expires" id="expires" required />
                    </div>
                </div>

                <div class="form-btn">
                    <button type="submit" name="add" class="btn">Zatwierdź</button>
                    <button type="reset" name="reset" class="btn">Resetuj</button>
                </div>
                </form>';
    echo $form;

    if (isset($_POST['add'])) {
        addProduct();
    }
}

function editProductForm()
{
    echo '<div class="main-header">
                <h1>Edycja produktu</h1>
                <h2>' . getProduct($_GET['product'])['name'] . '</h2>
          </div>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                <div class="double-form-field">
                    <div class="form-field">
                        <label for="name">Nazwa:</label>
                        <input type="text" name="name" id="name" value="' . getProduct($_GET['product'])['name'] . '" required />
                    </div>

                    <div class="form-field">
                        <label for="photo">Zdjęcie:</label>
                        <input type="file" name="photo" id="photo"/>
                    </div>
                </div>

                <div class="double-form-field">
                    <div class="form-field">
                        <label for="description">Opis:</label>
                        <textarea
                        name="description"
                        id="description"
                        cols="50"
                        rows="5"
                        required
                        >' . getProduct($_GET['product'])['description'] . '</textarea>
                    </div>
                    <div class="form-field">
                        <label>Aktualne zdjęcie:</label>
                        <div class="product-img"><img style="height: 100px; width: auto; display: block;" src="data:image; base64,' . getProduct($_GET['product'])['photo'] . '"></div>
                    </div>
                </div>
                
                <div class="double-form-field">
                    <div class="form-field">
                        <label for="size">Waga:</label>
                        <input type="text" name="size" id="size" value="' . getProduct($_GET['product'])['size'] . '"/>
                    </div>

                    <div class="form-field">
                        <label for="category">Kategoria:</label>
                        <input type="number" name="subcategory" id="subcategory" value="' . getProduct($_GET['product'])['subcategory'] . '" required />
                    </div>
                </div>

                <div class="double-form-field">
                    <div class="form-field">
                        <label for="price">Cena netto:</label>
                        <input type="number" name="price" id="price" step="0.01" value="' . getProduct($_GET['product'])['price'] . '" required />
                    </div>

                    <div class="form-field">
                        <label for="tax">Podatek VAT:</label>
                        <input type="number" name="tax" id="tax" step="0.01" value="' . getProduct($_GET['product'])['tax'] . '" required />
                    </div>
                </div>

                <div class="double-form-field">
                    <div class="form-field">
                        <label for="quantity">Dostępna ilość:</label>
                        <input type="number" name="quantity" id="quantity" value="' . getProduct($_GET['product'])['quantity'] . '" required />
                    </div>

                    <div class="form-field">
                        <label for="expires">Data wygaśnięcia:</label>
                        <input type="date" name="expires" id="expires" value="' . getProduct($_GET['product'])['expires'] . '" required />
                    </div>
                </div>

                <div class="form-btn">
                    <button type="submit" name="edit" class="btn">Zatwierdź</button>
                    <a href="javascript:history.go(-1);" class="btn">Anuluj</a>
                </div>
                </form>';
    echo $form;

    if (isset($_POST['edit'])) {
        editProduct($_GET['product']);
    }
}
?>