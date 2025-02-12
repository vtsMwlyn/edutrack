<?php
// Sertakan koneksi database
include 'connect.php'; // Pastikan file connect.php tersedia dan koneksi berfungsi

// Log data untuk debug
file_put_contents('debug_log.txt', print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '00:00:00';
    $end_time = isset($_POST['end_time']) ? trim($_POST['end_time']) : '23:59:59';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // **1️⃣ Validasi input (Cek jika ada yang kosong)**
    if (empty($title) || empty($date)) {
        echo json_encode([
            'success' => false,
            'error' => 'Judul dan tanggal tidak boleh kosong.'
        ]);
        exit;
    }

    // **2️⃣ Gabungkan tanggal dengan waktu**
    $start_datetime = $date . ' ' . $start_time;
    $end_datetime = $date . ' ' . $end_time;

    // **3️⃣ Simpan data ke database (Perbaiki Urutan Kode)**
    $stmt = $conn->prepare("INSERT INTO events (title, start, end, description) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'error' => 'Gagal menyiapkan query: ' . $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("ssss", $title, $start_datetime, $end_datetime, $description);
    if ($stmt->execute()) {
        // Dapatkan ID event yang baru saja disimpan
        $event_id = $conn->insert_id;

        echo json_encode([
            'success' => true,
            'message' => 'Event berhasil disimpan!',
            'event' => [
                'id' => $event_id, // ID event dari database
                'title' => $title,
                'start' => $start_datetime,
                'end' => $end_datetime,
                'description' => $description
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Gagal menyimpan event: ' . $stmt->error
        ]);
    }

    // **4️⃣ Tutup koneksi database**
    $stmt->close();
    $conn->close();
    exit;
}
?>
