<?php
$host = "localhost";
$user = "root";      // username MySQL
$pass = "";          // password MySQL
$db   = "toko_online"; // ganti dengan nama DB kamu

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
