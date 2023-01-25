<?php
// database connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db = 'page_db';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db);

if (mysqli_connect_error()) {
    echo mysqli_connect_error();
}

// admin panel
$user = 'root';
$pass = '1234';
$email = '162405@student.uwm.edu.pl';
?>