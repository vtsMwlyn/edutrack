<?php
session_start(); // Mulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke login jika belum login
    exit();
}

// Ambil id_siswa dari session
$id_siswa = $_SESSION['username'];

// Include koneksi database
include 'connect.php';



// Mengambil daftar tugas yang belum di-submit untuk form submit
$stmt_submit = $conn->prepare("SELECT id_tugas, nama_tugas, deskripsi, tanggal_tenggat FROM tugas WHERE (id_siswa IS NULL OR id_siswa != ?) AND status != 'Submitted'");
$stmt_submit->bind_param("i", $id_siswa);
$stmt_submit->execute();
$result_submit = $stmt_submit->get_result();

// Menyimpan daftar tugas yang belum di-submit dalam array
$tugas_list_submit = [];
while ($row = $result_submit->fetch_assoc()) {
    $tugas_list_submit[] = $row;
}
$stmt_submit->close();

// Mengambil daftar semua tugas yang sudah di-submit oleh siswa untuk form view
$stmt_all_submitted_task = $conn->prepare("SELECT * FROM tugas WHERE status = 'Submitted'");
$stmt_all_submitted_task->execute();
$result_all_submitted_task = $stmt_all_submitted_task->get_result();

$all_submitted_task = [];
while ($row = $result_all_submitted_task->fetch_assoc()) {
    $all_submitted_task[] = $row;
}
$stmt_all_submitted_task->close();

$query = "SELECT * FROM tugas WHERE status = 'Submitted'";
if (isset($_GET['tugas'])) {
    $query = "SELECT * FROM tugas WHERE status = 'Submitted' AND nama_tugas LIKE ?";
}

$stmt_view = $conn->prepare($query);

if (isset($_GET['tugas'])) {
    $search = "%{$_GET['tugas']}%"; // Concatenating wildcards
    $stmt_view->bind_param('s', $search);
}

$stmt_view->execute();
$result_view = $stmt_view->get_result();

// Menyimpan daftar tugas yang sudah di-submit dalam array
$filtered_tugas = [];
while ($row = $result_view->fetch_assoc()) {
    $filtered_tugas[] = $row;
}
$stmt_view->close();

// Proses mengembalikan tugas
if (isset($_POST['retract_task'])) {
    $id_tugas_retract = $_POST['id_tugas_retract']; // Hanya mengambil id tugas

    // Mengambil nama file untuk menghapus file dari folder uploads
    $stmt_retract = $conn->prepare("SELECT file_name FROM tugas WHERE id_tugas = ? AND id_siswa = ?");
    $stmt_retract->bind_param("ii", $id_tugas_retract, $id_siswa);
    $stmt_retract->execute();
    $result = $stmt_retract->get_result();
    $file_name = null;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_name = $row['file_name'];
    }
    $stmt_retract->close();

    // Menghapus file dari server jika ada
    if ($file_name) {
        $file_path = "uploads/" . $file_name;
        if (file_exists($file_path)) {
            unlink($file_path); // Menghapus file dari folder uploads
        }
    }

    // Mengubah status tugas dan menghapus id_siswa sehingga tugas bisa disubmit lagi
    $retract_query = "UPDATE tugas SET id_siswa = NULL, file_name = NULL, status = 'Returned' WHERE id_tugas = ? AND id_siswa = ?";
    $stmt_retract = $conn->prepare($retract_query);
    $stmt_retract->bind_param("ii", $id_tugas_retract, $id_siswa);

    if ($stmt_retract->execute()) {
        // Redirect halaman setelah retract
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $stmt_retract->error;
    }
    
    $stmt_retract->close();

    
}

if (isset($_POST['mau_update_nilai'])) {
    // print_r($id_siswa);
    // die();

    $stmt_update_nilai = $conn->prepare("UPDATE tugas SET nilai = ? WHERE id_tugas = ?");
    $stmt_update_nilai->bind_param('ii', $_POST['nilai_baru'], $_POST['id_tugas']);
    
    if ($stmt_update_nilai->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $stmt_update_nilai->error;
    }

    $stmt_update_nilai->close();
}

    
// Tentukan waktu saat ini
$hari = date('l'); // Nama hari (Senin, Selasa, dll)
$tanggal = date('F jS, Y'); // Format tanggal "October 21st, 2024"

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <title>View Task</title>
    <style>
        .container {
            max-height: 90vh; /* Membatasi tinggi kontainer */
            overflow-y: auto; /* Aktifkan scroll jika konten terlalu panjang */
            width: 100%;
        }

        form {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 40px;
            /* margin-left: -500px; */
            display: flex; /* Aktifkan flexbox untuk membuatnya fleksibel */
            flex-direction: column; /* Konten di dalam form tetap vertikal */
            width: 100%; /* Gunakan persentase agar melebar sesuai ukuran layar */
            max-height: 80vh; /* Membatasi tinggi form */
            overflow-y: auto; /* Aktifkan scroll vertikal */
            font-family: 'Arial', sans-serif;
        }


        form h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
            color: #555;
        }

        form input[type="text"],
        form input[type="date"],
        form select,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background-color: #f9f9f9;
            box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease-in-out;
        }

        form button {
            background-color: #62c1b6;
            color: #fff;
            border: none;
            padding: 12px 16px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
            text-align: center;
        }

        form button:hover {
            background-color: #504e76;
            transform: scale(1.02);
        }

    </style>
    <!--
    <script>
        // Menyimpan data tugas dalam JavaScript agar dapat digunakan saat dropdown dipilih
        // const tugasDataSubmit = <?php echo json_encode($tugas_list_submit); ?>;
        // const tugasDataView = <?php echo json_encode($tugas_list_view); ?>;

        // Fungsi untuk menampilkan deskripsi tugas berdasarkan pilihan dropdown untuk view form
        // function showTaskDetailsView() {
        //     const selectElement = document.getElementById('id_tugas_view');
        //     const taskDetailsElement = document.getElementById('task-details-view');
        //     const selectedId = parseInt(selectElement.value);

        //     // Cari tugas yang sesuai dengan id_tugas yang dipilih
        //     const selectedTask = tugasDataView.find(task => task.id_tugas === selectedId);

        //     // Jika tugas ditemukan, tampilkan detailnya
        //     if (selectedTask) {
        //         taskDetailsElement.innerHTML = `
        //             <h3>Task Details</h3>
        //             <p><strong>Task ID:</strong> ${selectedTask.id_tugas}</p>
        //             <p><strong>Task Name:</strong> ${selectedTask.nama_tugas}</p>
        //             <p><strong>Description:</strong> ${selectedTask.deskripsi}</p>
        //             <p><strong>Status:</strong> ${selectedTask.status}</p>
        //             ${selectedTask.file_name ? "<p>Submitted File: <a href='uploads/" + selectedTask.file_name + "' download>Download</a></p>" : "<p>No file found for this task.</p>"}
        //         `;
        //     } else {
        //         taskDetailsElement.innerHTML = "<p>No task selected. Please select a task to view details.</p>";
        //     }
        // }
    </script>
    -->
</head>
<body>
<div class="dashboard-card">
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <nav class="menu">
                    <ul>
                        <li><a href="guru_dashboard.php" ><i class="fas fa-home"></i></a></li>
                        <li><a href="kelola_tugas.php"><i class="fas fa-chalkboard-teacher"></i></a></li>
                        <li><a href="calender_guru.php"><i class="fas fa-calendar-alt"></i></a></li>
                        <li><a href="mapel_guru.php" class="active"><i class="fas fa-calendar-alt"></i></a></li>

                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <header class="main-header">
                    <div class="header-text">
                        <h1>EduTrack - Teacher Dashboard</h1>
                        <h2>Hello, <?php echo htmlspecialchars($id_siswa); ?>!</h2>
                        <p><?php echo $hari . ', ' . $tanggal; ?></p>
                    </div>
                </header>

    <h2>View Your Submitted Task</h2>
    <form action="lihat_tugas.php" method="get" style="min-height: 150px">
        <label for="tugas">Select Submitted Task:</label>
        <select name="tugas" id="tugas" onchange="showTaskDetailsView()" required>
            <option value="">--Select Task--</option>
            <?php
            foreach ($all_submitted_task as $tugas) {
                echo "<option value='" . $tugas['nama_tugas'] . "'>" . $tugas['nama_tugas'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="filter_tugas" value="Lihat Pengumpulan">
    </form>
    <div style="width: 1200px;overflow-x: auto; min-height: 400px;">
        <table style="width: 100%">
            <thead>
                <th>No</th>
                <th>Tugas</th>
                <th>Deskripsi</th>
                <th>Tenggat</th>
                <th>Siswa</th>
                <th>Status Pengumpulan</th>
                <th>File</th>
                <th>Nilai</th>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($filtered_tugas as $tugas) { ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $tugas['nama_tugas'] ?></td>
                        <td><?= $tugas['deskripsi'] ?></td>
                        <td><?= $tugas['tanggal_tenggat'] . ', ' . $tugas['waktu'] ?></td>
                        <td>
                            <?php
                                $stmt_getsiswa = $conn->prepare("SELECT siswa.id_siswa, siswa.nama_siswa 
                                    FROM tugas 
                                    JOIN siswa ON tugas.id_siswa = siswa.id_siswa 
                                    WHERE tugas.id_tugas = ?");

                                $stmt_getsiswa->bind_param("i", $tugas['id_tugas']);
                                $stmt_getsiswa->execute();
                                $result_getsiswa = $stmt_getsiswa->get_result();

                                // Fetch and display results
                                while ($row = $result_getsiswa->fetch_assoc()) {
                                    echo $row['nama_siswa'];
                                }
                            ?>  
                        </td>
                        <td><?= $tugas['status'] ?></td>
                        <td><?= $tugas['nama_tugas'] ?></td>
                        <td>
                            <form method="post" style="padding: 0; border-radius: 0; margin: 0; width: 50px;">
                                <input type="number" class="nilai_baru" name="nilai_baru" value="<?= $tugas['nilai'] ?>">
                                <input type="hidden" name="id_tugas" value="<?= $tugas['id_tugas'] ?>">
                                <input type="hidden" name="mau_update_nilai">
                            </form>
                        </td>
                    </tr>
                    <?php $i++; ?>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div id="task-details-view">
        <p>No task selected. Please select a task to view details.</p>
    </div>

    <h2>Retract Submitted Task</h2>
    <form method="POST">
        <label for="id_tugas_retract">Select Submitted Task:</label>
        <select name="id_tugas_retract" id="id_tugas_retract" required>
            <option value="">--Select Task--</option>
            <?php
            foreach ($tugas_list_view as $tugas) {
                echo "<option value='" . $tugas['id_tugas'] . "'>" . $tugas['nama_tugas'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="retract_task" value="Retract Task">
    </form>

    <script>
        const allInputNilai = document.querySelectorAll('.nilai_baru').forEach(inpNilai => {
            inpNilai.addEventListener('change', function() {
                let form = this.closest('form');
                form.submit();
            });
        });
    </script>

</body>

<?php
$conn->close();
?>
</html>
