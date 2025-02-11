<?php

 
$host = 'localhost'; // Database host
$dbname = 'edutrack'; // Database name
$dbusername = 'root'; // Database username
$dbpassword = ''; // Database password

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>