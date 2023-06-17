<?php
$host = "localhost"; // Nama hostnya
$username = "root"; // Username
$password = "phpmyadmin"; // Password (Isi jika menggunakan password)
$database = "LaundryDar"; // Nama databasenya
// Koneksi ke MySQL dengan PDO
$pdo = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
?>