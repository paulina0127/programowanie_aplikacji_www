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
        <li><a href="./admin.php?id=lista" class="menu-item">Lista stron</a></li>
        <li><a href="./admin.php?id=nowa" class="menu-item">Nowa strona</a></li>
      </ul>
      
        <?php
          @session_start();

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
            }
          }
        ?>
      </ul>
    </header>

    <section class="section admin">
      <div class="section-center clearfix">
        <div class="section-text">  
          <?php
                require_once('pageAdmin.php');
                error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

                switch ($_GET['id']) {
                    case "lista":
                      if (!isset($_SESSION['user']))
                        header("Location: ./admin.php?id=logowanie");
                      else
                        pageList();
                    break;

                    case "edycja":
                      if (!isset($_SESSION['user']))
                        header("Location: ./admin.php?id=logowanie");
                      else
                        editForm();
                    break;

                    case "nowa":
                      if (!isset($_SESSION['user']))
                        header("Location: ./admin.php?id=logowanie");
                      else
                        addForm();
                    break;

                    case "logowanie":
                      loginForm();
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
