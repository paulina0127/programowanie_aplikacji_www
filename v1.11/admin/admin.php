<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Paulina Hryciuk" />
    <title>Panel administracyjny | Hodowla żółwia wodnego</title>
    <link
      rel="shortcut icon"
      href="../images/favicon.ico"
      type="image/x-icon"
    />
    <link rel="stylesheet" href="../css/admin.css" />
  </head>

  <body>
    <header class="header">
      <a href="./admin.php">
        <img src="../images/logo.png" alt="Logo" class="logo" />
      </a>

      <ul class="menu">
        <li><a href="./admin.php?id=lista_stron" class="menu-item">Strony</a></li>
        <li><a href="./admin.php?id=lista_kategorii" class="menu-item">Kategorie</a></li>
        <li><a href="./admin.php?id=lista_produktow" class="menu-item">Produkty</a></li>
      </ul>
      
        <?php
          session_start();
          ob_start();

          if (isset($_SESSION['user'])) {
            echo 
            '<ul class="menu">
                    <li>Zalogowano jako: <span class="highlight">' . $_SESSION['user'] . '</span></li>
                    <li>
                      <form method="POST">
                        <input type="submit" name="logout" class="btn" value="Wyloguj" />
                      </form> </ul>';

            if (isset($_POST['logout'])) {
              session_unset();
              header("Location: ./admin.php");
              exit;
            }
          }
        ?>
      </ul>
    </header>

    <section class="section admin">
      <div class="section-center clearfix">
        <div class="section-text">  
          <?php
                require_once('./login.php');
                require_once('./pageAdmin.php');
                require_once('./categoryAdmin.php');
                require_once('./productAdmin.php');
                error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

                switch ($_GET['id']) {
                    case "logowanie":
                      loginForm();
                    break;

                    case "lista_stron":
                      if (!isset($_SESSION['user'])) {
                        header("Location: ./admin.php?id=logowanie");
                        exit;
                      }
                      else
                        pageList();
                    break;

                    case "edycja_strony":
                      if (!isset($_SESSION['user'])) {
                        header("Location: ./admin.php?id=logowanie");
                        exit;
                      }
                      else
                        editPageForm();
                    break;

                    case "nowa_strona":
                      if (!isset($_SESSION['user'])) {
                        header("Location: ./admin.php?id=logowanie");
                        exit;
                      }
                      else
                        addPageForm();
                    break;

                    case "lista_kategorii":
                      if (!isset($_SESSION['user'])) {
                        header("Location: ./admin.php?id=logowanie");
                        exit;
                      }
                      else
                        categoryList();
                    break;

                    case "edycja_kategorii":
                      if (!isset($_SESSION['user'])) {
                        header("Location: ./admin.php?id=logowanie");
                        exit;
                      }
                      else
                        editCategoryForm();
                    break;

                    case "nowa_kategoria":
                      if (!isset($_SESSION['user'])) {
                        header("Location: ./admin.php?id=logowanie");
                        exit;
                      }
                      else
                        addCategoryForm();
                    break;

                    case "lista_produktow":
                      if (!isset($_SESSION['user'])) {
                        header("Location: ./admin.php?id=logowanie");
                        exit;
                      }
                      else
                        productList();
                    break;

                    case "edycja_produktu":
                      if (!isset($_SESSION['user'])) {
                        header("Location: ./admin.php?id=logowanie");
                        exit;
                      }
                      else
                        editProductForm();
                    break;

                    case "nowy_produkt":
                      if (!isset($_SESSION['user'])) {
                        header("Location: ./admin.php?id=logowanie");
                        exit;
                      }
                      else
                        addProductForm();
                    break;

                    default:
                    if (!isset($_SESSION['user']))
                        loginForm();
                      else
                        pageList();
                }
            ?>
        </div>
      </div>
    </section>
  </body>
</html>
