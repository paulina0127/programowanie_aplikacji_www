<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="Content-Language" content="pl" />
  <meta name="Author" content="Paulina Hryciuk" />

  <title>
    <?php
    require_once('showPage.php');
    @showTitle($_GET['id']);
    ?> | Hodowla żółwia wodnego</title>

  <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="./css/style.css" />
  <link rel="stylesheet" href="./css/icons/css/all.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body onload="startClock()">
<?php
  session_start();

  $theme = '<input type="checkbox" name="theme" id="theme"';
  
  if(isset($_POST['theme']) && $_POST['theme'] == 'true') {
    $_SESSION['theme'] = 'on';
  }
  else if ((isset($_POST['theme']) && $_POST['theme'] == 'false')) {
    $_SESSION['theme'] = 'off';
  }
 
  if (isset($_SESSION['theme']) && $_SESSION['theme'] == 'on') {
    $theme .= ' checked />
      <label for="theme">
        <i class="fa-solid fa-moon"></i>
        <i class="fa-solid fa-sun"></i>
      </label>';
  }
  else {
    $theme .= '/>
      <label for="theme">
        <i class="fa-solid fa-moon"></i>
        <i class="fa-solid fa-sun"></i>
      </label>';
  }

  echo $theme;
  ?>

  <header class="header">
    <a href="./index.php">
      <img src="./images/logo.png" alt="Logo" class="logo" />
    </a>
    <nav>
      <ul class="menu">
        <?php
        require_once('./cfg.php');
        global $conn;
        $query = "SELECT * FROM page_list ORDER BY 'id' DESC LIMIT 100";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          if ($row['status'] == 1) {
          echo '<li><a href="./index.php?id=' . $row['alias'] . '" class="menu-item">' . $row['page_title'] . '</a></li>';
          }
        }
        ?>
      </ul>
    </nav>
  </header>

  <div class="banner">
    <h1>Hodowla żółwia wodnego</h1>
    <p>
      Na naszej stronie dowiesz się wszystkich potrzebnych informacji!
    </p>
    <a href="#start" class="banner-btn">Przeglądaj</a>
  </div>

  <div id="start"></div>

  <?php
  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
  require_once('./contact.php');

  if ($_GET['id'] == 'kontakt') {
    $page = '<section class="section">
                <div class="form section-center">' . showPage($_GET['id']);

    if (isset($_POST['send'])) {
        $page .= sendMail();
    }

    $page .= '</div>
            </section>';
  }
  else {
    $page = showPage($_GET['id']);
  }

  echo $page;
  ?>

  <footer class="footer">
    <div id="datetime">
      <div>
        <p>Dzisiejsza data:</p>
        <div id="date"></div>
      </div>

      <div>
        <p>Aktualny czas:</p>
        <div id="time"></div>
      </div>
    </div>

    <p class="highlight"> Copyright &copy; 2022 
        <?php
        $author = 'Paulina Hryciuk';
        $index = '162405';
        $group = '1';
        echo $author . ' (' . $index . '), grupa ' . $group;
        ?>
    </p>
  </footer>

  <script src="./javascript/ajax.js"></script>
  <script src="./javascript/animations.js"></script>
  <script src="./javascript/timeDate.js"></script>
</body>
</html>