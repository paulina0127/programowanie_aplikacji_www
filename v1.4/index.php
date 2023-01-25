<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Content-Language" content="pl" />
  <meta name="Author" content="Paulina Hryciuk" />
  <title>Hodowla żółwia wodnego</title>
  <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="./css/style.css" />
  <script src="./javascript/kolorujTlo.js"></script>
  <script src="./javascript/timeDate.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body onload="startClock()">
  <header class="header">
    <a href="./index.php?id=glowna">
      <img src="./images/logo.png" alt="Logo" class="logo" />
    </a>
    <nav>
      <ul class="menu">
        <li><a href="./index.php?id=glowna" class="menu-item">O żółwiach</a></li>
        <li><a href="./index.php?id=gatunki" class="menu-item">Gatunki</a></li>
        <li><a href="./index.php?id=akwarium" class="menu-item">Akwarium</a></li>
        <li><a href="./index.php?id=zywienie" class="menu-item">Żywienie</a></li>
        <li><a href="./index.php?id=zdrowie" class="menu-item">Zdrowie</a></li>
        <li><a href="./index.php?id=filmy" class="menu-item">Filmy</a></li>
        <li><a href="./index.php?id=kontakt" class="menu-item">Kontakt</a></li>
      </ul>
    </nav>
  </header>

  <div class="banner">
    <h1>Hodowla żółwia wodnego</h1>
    <p class="banner-text">
      Na naszej stronie dowiesz się wszystkich potrzebnych informacji!
    </p>
    <a href="#start" class="banner-btn">Przeglądaj</a>
  </div>

  <div id="start"></div>
  <?php
  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

  switch ($_GET['id']) {
    case "glowna":
      $page = './html/glowna.html';
      break;
    case "gatunki":
      $page = './html/gatunki.html';
      break;
    case "akwarium":
      $page = './html/akwarium.html';
      break;
    case "zywienie":
      $page = './html/zywienie.html';
      break;
    case "zdrowie":
      $page = './html/zdrowie.html';
      break;
      case "filmy":
        $page = './html/filmy.html';
        break;
    case "kontakt":
      $page = './html/kontakt.html';
      break;
    case "javascript":
      $page = './html/javascript.html';
      break;
    default:
      $page = '';
  }

  if (file_exists($page)) {
    include $page;
  }
  else {
    echo '<div class="section-center">
    <div class="main-header">
    <h1 style="margin-bottom: 5px;">Podstrona nie istnieje</h1>
  </div> </div>';
  }
  ?>


  <footer class="footer">
    <a href="./index.php?id=javascript" class="highlight">Javascript</a>
    | &copy; 2022 <span class="highlight">Hodowla żółwia wodnego</span> <br />

    <span>
      <?php
      $autor = 'Paulina Hryciuk';
      $nr_indeksu = '162405';
      $nr_grupy = '1';
      echo 'Autor: ' . $autor . ' (' . $nr_indeksu . '), grupa ' . $nr_grupy;
      ?>
    </span>
  </footer>
  <script src="./javascript/animacje.js"></script>
</body>

</html>