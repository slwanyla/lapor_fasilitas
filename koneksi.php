<?php
$host = "localhost:3306";
$user = "root";
$pass = "";
$db = "lapor_fasilitas";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    echo 'ERROR : '  . mysqli_connect_error($conn);
}
