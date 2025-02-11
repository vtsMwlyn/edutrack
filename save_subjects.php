<?php
// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'connect.php';

// Cek apakah session username tersedia
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Session username tidak ditemukan."]);
    exit();
}

$username = $_SESSION['username'];

// Proses jika metode permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['selectedSubjects']) || !isset($data['username'])) {
        echo json_encode(["success" => false, "message" => "Data input tidak lengkap."]);
        exit();
    }

    $selectedSubjects = $data['selectedSubjects'];
    $username = $data['username'];

    // Cek apakah username valid
    $sql = "SELECT id_siswa FROM siswa WHERE id_pengguna = (SELECT id_pengguna FROM pengguna WHERE username = ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Kesalahan pada query: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $siswa_id = $row['id_siswa'];

        // Hapus data lama
        $sql_delete = "DELETE FROM mata_pelajaran_siswa WHERE id_siswa = ?";
        $stmt_delete = $conn->prepare($sql_delete);

        if (!$stmt_delete) {
            echo json_encode(["success" => false, "message" => "Kesalahan saat menghapus data: " . $conn->error]);
            exit();
        }

        $stmt_delete->bind_param("i", $siswa_id);
        $stmt_delete->execute();

        // Insert data baru
        $sql_insert = "INSERT INTO mata_pelajaran_siswa (id_siswa, id_mapel) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);

        if (!$stmt_insert) {
            echo json_encode(["success" => false, "message" => "Kesalahan saat menambahkan data: " . $conn->error]);
            exit();
        }

        foreach ($selectedSubjects as $subject_name) {
            // Ambil id_mapel berdasarkan nama
            $sql_mapel = "SELECT id_mapel FROM mata_pelajaran WHERE nama_mapel = ?";
            $stmt_mapel = $conn->prepare($sql_mapel);

            if (!$stmt_mapel) {
                echo json_encode(["success" => false, "message" => "Kesalahan pada query mapel: " . $conn->error]);
                exit();
            }

            $stmt_mapel->bind_param("s", $subject_name);
            $stmt_mapel->execute();
            $mapel_result = $stmt_mapel->get_result();

            if ($mapel_result->num_rows > 0) {
                $mapel_row = $mapel_result->fetch_assoc();
                $mapel_id = $mapel_row['id_mapel'];

                // Masukkan id_siswa dan id_mapel
                $stmt_insert->bind_param("ii", $siswa_id, $mapel_id);
                $stmt_insert->execute();
            } else {
                echo json_encode(["success" => false, "message" => "Mata pelajaran tidak ditemukan: $subject_name"]);
                exit();
            }
        }

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "User tidak ditemukan."]);
    }
}
