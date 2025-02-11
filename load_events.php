<?php
// Sertakan koneksi database
include 'connect.php'; // Pastikan file connect.php tersedia dan koneksi berfungsi

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query untuk mengambil semua event dengan waktu mulai dan selesai
    $sql = "SELECT id, title, start, end, description FROM events";
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'], // Waktu mulai
        'end' => $row['end'],     // Waktu akhir
        'description' => $row['description']
    ];
}
echo json_encode($events);
$conn->close();
exit;

}
?>
