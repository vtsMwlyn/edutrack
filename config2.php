<?php
$servername = "localhost";
$username = "root";    // Ganti dengan username MySQL Anda
$password = "";        // Ganti dengan password MySQL Anda
$dbname = "edutrack";  // Nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} else {
    echo "";
}

// Cek apakah tabel 'users' ada, jika tidak, buat tabelnya
$tableCheck = $conn->query("SHOW TABLES LIKE 'users'");
if ($tableCheck->num_rows == 0) {
    // Buat tabel 'users' jika belum ada
    $createTable = "
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            phone VARCHAR(15) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        );
    ";

    if ($conn->query($createTable) === TRUE) {
        echo "Tabel 'users' berhasil dibuat.";
    } else {
        die("Gagal membuat tabel 'users': " . $conn->error);
    }
}

// Periksa apakah ada data di tabel 'users' untuk pengujian awal
$sql = "SELECT * FROM users LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Masukkan data awal untuk pengujian
    $hashed_password = password_hash("password123", PASSWORD_BCRYPT); // Ganti dengan hash kata sandi
    $insertData = $conn->prepare("INSERT INTO users (phone, password) VALUES (?, ?)");
    $insertData->bind_param("ss", $phone = '08118608830', $hashed_password);

    if ($insertData->execute()) {
        echo "Data pengguna awal berhasil dimasukkan ke tabel users.";
    } else {
        echo "Gagal memasukkan data pengguna: " . $insertData->error;
    }
    $insertData->close();
} else {
    echo "";
}
?>