<?php
session_start();
include 'connect.php';

// Pastikan parameter `id_mapel` ada di URL
if (isset($_GET['id_mapel'])) {
    $idMapel = intval($_GET['id_mapel']); // Sanitasi input
} else {
    die("ID mapel tidak ditemukan. Pastikan Anda mengakses halaman dengan parameter yang benar.");
}

$queryMapel = "SELECT * FROM mata_pelajaran";
$resultMapel = $conn->query($queryMapel);

if (!$resultMapel) {
    die("Query gagal dijalankan: " . $conn->error);
}

// Ambil tugas berdasarkan mapel untuk tampilan reminder
$tugasPerMapel = [];
$queryTugas = "SELECT * FROM tugas";
$resultTugas = $conn->query($queryTugas);

if ($resultTugas) {
    while ($tugas = $resultTugas->fetch_assoc()) {
        $tugasPerMapel[$tugas['id_mapel']][] = $tugas;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mapel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f7f7f7;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #504e76;
        }
        .mapel-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .mapel-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #f4f4f4;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .mapel-card:hover {
            transform: scale(1.05);
            background: #e0e0e0;
        }
        .mapel-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .mapel-icon {
            font-size: 24px;
            color: #fff;
            background: #504e76;
            padding: 10px;
            border-radius: 50%;
            text-align: center;
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }
        .reminder {
            color: #f1642e;
            font-size: 14px;
            margin-top: 5px;
        }
        .complete-btn {
            background: #a3b565;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .complete-btn:hover {
            background: #89a14b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Mata Pelajaran</h1>
        <div class="mapel-cards">
            <?php while ($mapel = $resultMapel->fetch_assoc()) : ?>
                <div class="mapel-card" data-mapel-id="<?= $mapel['id_mapel'] ?>" onclick="openClass(<?= $mapel['id_mapel'] ?>)">
                    <div>
                        <div class="mapel-icon">📚</div>
                        <div class="mapel-title"><?= $mapel['nama_mapel'] ?> (<?= $mapel['kategori'] ?>)</div>
                        <?php if (isset($tugasPerMapel[$mapel['id_mapel']])) : ?>
                            <?php foreach ($tugasPerMapel[$mapel['id_mapel']] as $tugas) : ?>
                                <div class="reminder">
                                    <?= $tugas['nama_tugas'] ?> (Deadline: <?= $tugas['deadline'] ?>)
                                    <button class="complete-btn" onclick="markAsDone(event, <?= $tugas['id_tugas'] ?>)">Selesai</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <i class="fas fa-arrow-right"></i>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        function openClass(idMapel) {
            // Arahkan ke halaman kelas sesuai ID mapel
            window.location.href = `kelas.php?id_mapel=${idMapel}`;
        }

        function markAsDone(event, idTugas) {
            event.stopPropagation(); // Hindari membuka kelas saat klik tombol
            fetch('update_tugas.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_tugas: idTugas })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sukses!', 'Tugas telah diselesaikan.', 'success').then(() => {
                        location.reload(); // Refresh halaman untuk memperbarui tampilan
                    });
                } else {
                    Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                }
            });
        }
    </script>
</body>
</html>
