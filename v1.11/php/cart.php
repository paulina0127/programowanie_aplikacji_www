<?php
require_once('./cfg.php');

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

function showCategories() {
    global $conn;
    $query = "SELECT * FROM category_list";
    $result = mysqli_query($conn, $query);
    $cat = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($cat, $row);
    }

    $list = '<ul>
            <li><a href="./index.php?id=sklep">Wszystkie produkty</a></li>';
    for ($i = 0; $i < count($cat); $i++) {
        if (($cat[$i])['main_category'] == 0) {
            $list .= '<li><a href="./index.php?id=sklep&category=' . ($cat[$i])['id'] . '">' . ($cat[$i])['name'] . '</a>';
            $list .= '<ul>';
            for ($j = 0; $j < count($cat); $j++) {
                if (($cat[$j])['main_category'] == ($cat[$i])['id']) {
                    $list .= '<li><a href="./index.php?id=sklep&subcategory=' . ($cat[$j])['id'] . '">' . ($cat[$j])['name'] . '</a></li>';
                }
            }
            $list .= '</li></ul>';
        }
    }
    $list .= '</ul>';
    return $list;
}

function showProducts() {
    global $conn;

    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $filter = ' WHERE category="' . $_GET['category'] .'"';
    }
    if (isset($_GET['subcategory']) && !empty($_GET['subcategory'])) {
        $filter = ' WHERE subcategory="' . $_GET['subcategory'] .'"';
    }
    if (isset($_POST['sort']) && !empty($_POST['sort'])) {
        $sort = ' ORDER BY ' . $_POST['sort'];
    }

    $query = "SELECT * FROM product_list" . $filter . $sort . " LIMIT 100";

    $result = mysqli_query($conn, $query);
    $list = '<section class="section">
                <div class="section-center section-center-first">
                    <div class="shop-header">
                        <div class="main-header">
                            <h1>';
    
    if (isset($_GET['category'])) {
        $cat_query = "SELECT * FROM category_list WHERE id=" . $_GET['category'] . " LIMIT 1";
        $cat_result = mysqli_query($conn, $cat_query);
        $cat = mysqli_fetch_assoc($cat_result);
        $list .= $cat['name'] . '</h1></div>';
    }
    else if (isset($_GET['subcategory'])) {
        $cat_query = "SELECT * FROM category_list WHERE id=" . $_GET['subcategory'] . " LIMIT 1";
        $cat_result = mysqli_query($conn, $cat_query);
        $cat = mysqli_fetch_assoc($cat_result);
        $list .= $cat['name'] . '</h1></div>';
    }
    else
        $list .= 'Wszystkie produkty' . '</h1></div>';

    $list .=
                        '<div class="shop">
                        <form method="POST">
                            <select name="sort" onchange="this.form.submit()">
                                <option value="" hidden selected>Sortuj produkty</option>
                                <option value="price ASC">Cena rosnąco</option>
                                <option value="price DESC">Cena malejąco</option>
                                <option value="name ASC">Nazwa rosnąco</option>
                                <option value="name DESC">Nazwa malejąco</option>
                            </select>
                        </form>
                        <a href="./index.php?id=koszyk"><h2>Koszyk <i class="fa-solid fa-basket-shopping"></i></h2></a>
                        </div>
                    </div>
                    <div class="shop">
                    <div class="categories">'
                    . showCategories() .
                    '</div>
                    <div class="products">';
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['available'] == 1) {
            $list .= '<article class="product-card">
                        <div class="product-img">
                            <img src="data:image; base64,' . $row['photo'] . '">
                        </div>
                        <div class="product-text">
                                <h4>' . $row['name'] . '</h4>
                                <h3>' . number_format($row['price'] + $row['price'] * ($row['tax'] / 100), 2) . 'zł</h3>
                        </div>
                        <form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="product_id" value="' . $row['id'] . '">
                            <input type="hidden" name="name" value="' . $row['name'] . '">
                            <input type="number" name="quantity" value="1" max="' . $row['quantity'] . '">
                            <input type="hidden" name="price" value="' . number_format($row['price'] + $row['price'] * ($row['tax'] / 100), 2) .'">
                            <input type="submit" name="addToCart" value="Dodaj do koszyka" class="btn">
                        </form>
                        
                    </article>';
        }
    }

    $list .= '</div></div></div></section>';
    echo $list;

    if (isset($_POST['addToCart'])) {
        addToCart($_POST['product_id'], $_POST['name'], $_POST['quantity'], $_POST['price']);
    }
}

function addToCart($id, $name, $quantity, $price) {
    if (isset($_SESSION['cart']['count'])) {
        $_SESSION['cart']['count'] += $quantity;
        $_SESSION['cart']['total'] += $price * $quantity;
    }
    else {
        $_SESSION['cart']['count'] = $quantity;
        $_SESSION['cart']['total'] = $price * $quantity;
    }

    $product_info = array("id" => $id, "name" => $name, "quantity" => $quantity, "price" => $price);

    if (isset($_SESSION['cart']['products'])) {
        foreach ($_SESSION['cart']['products'] as $product) {
            if (in_array($name, $product)) {
                $product['quantity'] += $quantity;
                return;
            }
        }
            array_push($_SESSION['cart']['products'], $product_info);
    }
    else {
        $_SESSION['cart']['products'] = array();
        array_push($_SESSION['cart']['products'], $product_info);
    }
}

function updateCart($product_nr, $quantity) {
    $_SESSION['cart']['count'] -= $_SESSION['cart']['products'][$product_nr]['quantity'];
    $_SESSION['cart']['total'] -= $_SESSION['cart']['products'][$product_nr]['price'] * $_SESSION['cart']['products'][$product_nr]['quantity'];
    $_SESSION['cart']['products'][$product_nr]['quantity'] = $quantity;

    $_SESSION['cart']['count'] += $_SESSION['cart']['products'][$product_nr]['quantity'];
    $_SESSION['cart']['total'] += $_SESSION['cart']['products'][$product_nr]['price'] * $quantity;
    header("Location: ./index.php?id=koszyk");
    exit;
}

function removeFromCart($product_nr) {
    $_SESSION['cart']['count'] -= $_SESSION['cart']['products'][$product_nr]['quantity'];
    $_SESSION['cart']['total'] -= $_SESSION['cart']['products'][$product_nr]['price'] * $_SESSION['cart']['products'][$product_nr]['quantity'];
    unset($_SESSION['cart']['products'][$product_nr]);
    header("Location: ./index.php?id=koszyk");
    exit;
}

function showCart() {
    if (!isset($_SESSION['cart']['count'])) {
        $_SESSION['cart']['count'] = 0;
        $_SESSION['cart']['total'] = 0;
    }

    $list =  '<section class="section">
            <div class="section-center section-center-first">';
    $list .= '<div class="main-header">
                <h1>Koszyk</h1>
            </div>';

    $list .= '<div class="cart">
                    <h2>Liczba produktów: ' . $_SESSION['cart']['count'] . '</h2>';
    if ($_SESSION['cart']['count'] > 0) {
        $list .= '<table>';
        foreach ($_SESSION['cart']['products'] as $nr => $product) {
            $list .= '<tr>
                    <td><div class="product-img"><img style="height: 100px; width: auto; display: block;" src="data:image; base64,' . getProduct($product['id'])['photo'] . '"></div></td>
                    <td>' . $product['name'] . '</td>
                    <td>
                    <form method="POST">
                    <input type="number" onchange="this.form.submit()" name="quantity" value="' . $product['quantity'] . '">
                    <input type="hidden" name="product_nr" value="' . $nr . '">
                    <input type="hidden" name="updateCart"></form></td>
                    <td>' . number_format($product['price'] * $product['quantity'], 2) . 'zł</td>';

            $list .= '<td><form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product_nr" value="' . $nr . '">
                    <input type="submit" name="removeFromCart" value="Usuń z koszyka" class="btn">
                </form></td></tr>';
        }
        $list .= '</table>
                <h2 class="float-right">Razem: ' . number_format($_SESSION['cart']['total'], 2) . 'zł</h2>';
    }
    
    $list .= '</div></section>';
    echo $list;

    if (isset($_POST['updateCart'])) {
        updateCart($_POST['product_nr'], $_POST['quantity']);
    }

    if (isset($_POST['removeFromCart'])) {
        removeFromCart($_POST['product_nr']);
    }
}
?>