<?php
require_once('cfg.php');

function showTitle($id) {
    global $conn;
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM page_list WHERE alias='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (empty($row['alias'])) {
        $title = 'Nie znaleziono strony';
    }
    else {
        $title = $row['page_title'];
    }
    return $title;
}
?>