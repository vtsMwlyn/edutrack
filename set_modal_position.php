<?php
// set_modal_position.php

// Mengambil posisi klik dari URL (dari JavaScript sebelumnya)
if (isset($_GET['x']) && isset($_GET['y'])) {
    $x = intval($_GET['x']);
    $y = intval($_GET['y']);

    // Menghitung posisi form berdasarkan klik
    $modal_position = [
        'x' => $x,
        'y' => $y
    ];

    // Menyusun respons dalam format JSON untuk dikirim ke frontend
    echo json_encode($modal_position);
} else {
    // Jika parameter tidak ada, berikan nilai default
    echo json_encode([
        'error' => 'Posisi tidak ditemukan'
    ]);
}
?>
