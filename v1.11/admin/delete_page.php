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
          require_once('./pageAdmin.php');
          require_once('./categoryAdmin.php');
          require_once('./productAdmin.php');
          error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
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

  <section class="section">
    <div class="section-center section-center-first">
      <div class="alert">
          <h2>Potwierdź usunięcie</h2>
          <form method="POST">
              <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
              <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>" />
              <input type="hidden" name="product" value="<?php echo $_GET['product']; ?>" />
              <span class="alert">Jesteś pewny, że chcesz usunąć ten rekord?</span>
            <div class="form-btn">
            <input type="submit" value="Tak" class="btn btn-danger">
            <a href="javascript:history.go(-1);" class="btn">Nie</a>
          </div>
          </form>
      </div>
      <?php
       if (isset($_POST['page']) && !empty($_POST['page'])) {
             deletePage($_POST['page']);
         }

       if (isset($_POST['category']) && !empty($_POST['category'])) {
             deleteCategory($_POST['category']);
         }

       if (isset($_POST['product']) && !empty($_POST['product'])) {
             deleteProduct($_POST['product']);
         }
      ?>
    </div>
    
  </section>
</body>
</html>

