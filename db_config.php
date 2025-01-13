<?php
$servername = "localhost"; // Server Database
$username = "root"; // Username Database
$password = ""; // Password Database
$dbname = "university"; // Nama Database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>

