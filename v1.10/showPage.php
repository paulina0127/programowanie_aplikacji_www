<?php
require_once('./cfg.php');

function showPage($id) {
    global $conn;

    if (!$id)
        $id = "glowna";

    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM page_list WHERE alias='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (empty($row['alias']) || $row['status'] == 0) {
        $web = '<div class="section-center">
                    <div class="main-header">
                        <h1 style="margin-bottom: 5px;">Podstrona nie istnieje</h1>
                    </div>
                </div>';
    }
    else {
        $web = $row['page_content'];
    }
    return $web;
}

function showTitle($id) {
    global $conn;

    if (!$id) 
        $id = "glowna";
        
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
    echo $title;
}
?>