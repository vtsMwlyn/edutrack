<?php
// Pengaturan koneksi database
define('DB_HOST', 'localhost'); // Ganti sesuai host Anda
define('DB_USER', 'Vincent');      // Ganti sesuai username Anda
define('DB_PASS', 'asa');          // Ganti sesuai password Anda
define('DB_NAME', 'edutrack');  // Ganti sesuai nama database Anda

// Membuat koneksi ke database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pengaturan timezone default (opsional)
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk debugging (opsional)
function debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
