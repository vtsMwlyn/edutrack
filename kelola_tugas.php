<?php
// Mulai session
session_start();
include 'connect.php'; // Pastikan koneksi database Anda di sini

// Inisialisasi variabel untuk error dan pesan sukses
$error_message = "";
$success_message = "";


// Fungsi untuk menambah tugas
if (isset($_POST['add_task'])) {
    $nama_tugas = $_POST['nama_tugas'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_tenggat = $_POST['tanggal_tenggat'];
    $waktu_tenggat = $_POST['waktu_tenggat']; // ✅ Include waktu_tenggat
    $id_siswas = $_POST['id_siswa'];

    // Pastikan id_mapel ada dalam POST
    $id_mapel = isset($_POST['id_mapel']) ? $_POST['id_mapel'] : null;

    // Pastikan id_mapel tidak kosong
    if ($id_mapel !== null) {
        $all_queries_success = true; // Flag to check if all queries succeed

        foreach ($id_siswas as $id_siswa) {
            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO tugas (nama_tugas, deskripsi, tanggal_tenggat, waktu, status, id_mapel, id_siswa) 
                                    VALUES (?, ?, ?, ?, 'pending', ?, ?)");
            $stmt->bind_param("ssssii", $nama_tugas, $deskripsi, $tanggal_tenggat, $waktu_tenggat, $id_mapel, $id_siswa);

            if (!$stmt->execute()) {
                $all_queries_success = false;
                $error_message = "Error: " . $stmt->error;
                break; // Stop execution if one query fails
            }
        }

        if ($all_queries_success) {
            $success_message = "Tugas berhasil ditambahkan untuk semua siswa!";
        }

    } else {
        $error_message = "Mata pelajaran harus dipilih!";
    }
}


// Pastikan user sudah login, jika belum arahkan ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'connect.php';

// Pastikan koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ambil nama pengguna dari session
$username = $_SESSION['username'];

// Ambil id_guru dari tabel guru berdasarkan pengguna yang login
$query_guru = "SELECT id_guru FROM guru WHERE id_pengguna = ?";
// Debugging: Show current session data
// Debugging: Check if session ID pengguna is set
if (!isset($_SESSION['id_pengguna'])) {
    echo "<p>Error: User ID not found in session. Please log in again.</p>";
    exit();
}

$stmt_guru = $conn->prepare($query_guru);
if ($stmt_guru) {
    $stmt_guru->bind_param("i", $_SESSION['id_pengguna']);
    $stmt_guru->execute();
    $result_guru = $stmt_guru->get_result();
    $row_guru = $result_guru->fetch_assoc();
    $id_guru = isset($row_guru['id_guru']) ? $row_guru['id_guru'] : null;
    if (is_null($id_guru)) {
    echo "<p>Error: Guru ID not found. Please check if the user is properly linked to a teacher account.</p>";
        exit();
}
    $stmt_guru->close();
} else {
    die("Error preparing statement: " . $conn->error);
}
// Tangkap ID siswa yang dipilih untuk menampilkan mata pelajaran dan tugas
$id_siswa_selected = isset($_POST['id_siswa_selected']) ? $_POST['id_siswa_selected'] : null;

// Tentukan waktu saat ini
$hari = date('l'); // Nama hari (Senin, Selasa, dll)
$tanggal = date('F jS, Y'); // Format tanggal "October 21st, 2024"
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />
     <!-- FullCalendar -->
     <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
   <!-- Header -->
   <header>
  <nav class="navbar">
    <div class="logo" >
        
      <img src="https://cdn.discordapp.com/attachments/1282538476079677533/1335218996852555808/Untitled_design_20250201_190204_0000.png?ex=67a0b098&is=679f5f18&hm=df23008f6322f361d3fac53002f2442809d42acfc0fc096b3641e26bb78f978e&" alt="EduTrack Logo" class="logo-img">
      <span style="color:white;">EduTrack</span>
    </div>
    <ul class="nav-links">
      <li><a href="aboutus.php">About Us</a></li>
      <li><a href="rateus.php">Rate Us</a></li>
    </ul>
  </nav>
</header>
            <!-- Sidebar -->
            <aside class="sidebar" style="margin-top:-876px">
                <nav class="menu">
                    <ul>
                    <li><a href="guru_dashboard.php" ><i class="fas fa-home"></i></a></li>
                    <li><a href="kelola_tugas.php" class="active"><i class="fas fa-chalkboard-teacher"></i></a></li>
                    <li><a href="calender_guru.php"><i class="fas fa-calendar-alt"></i></a></li>
                    <li><a href="lihat_tugas.php"><i class="fas fa-calendar-alt"></i></a></li>

                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <header class="main-header">
                    <div class="header-text">
                        <h1>EduTrack - Teacher Dashboard</h1>
                        <h2>Hello, <?php echo htmlspecialchars($username); ?>!</h2>
                        <p><?php echo $hari . ', ' . $tanggal; ?></p>
                    </div>
                </header>

                <?php
include 'connect.php'; // Pastikan koneksi database sudah disertakan

// Proses penyimpanan tugas jika form dikirim
if (isset($_POST['add_task'])) {
    $id_mapel = $_POST['id_mapel'];
    $nama_tugas = $_POST['nama_tugas'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_tenggat = $_POST['tanggal_tenggat'];

    // Pastikan siswa dipilih sebelum memproses
    if (isset($_POST['id_siswa']) && is_array($_POST['id_siswa']) && count($_POST['id_siswa']) > 0) {
        $id_siswa_list = $_POST['id_siswa'];

        foreach ($id_siswa_list as $id_siswa) {
            $insert_query = "INSERT INTO assignments (id_mapel, id_siswa, nama_tugas, deskripsi, tanggal_tenggat) 
                             VALUES ('$id_mapel', '$id_siswa', '$nama_tugas', '$deskripsi', '$tanggal_tenggat')";
            mysqli_query($conn, $insert_query);
        }

        $_SESSION['message'] = "<p class='success'>Tugas berhasil ditambahkan!</p>";
    } else {
        $_SESSION['message'] = "<p class='error'>Harap pilih setidaknya satu siswa.</p>";
    }

    header("Location: " . $_SERVER['PHP_SELF']); // Refresh halaman setelah submit
    exit();
}
?>

<div class="container">
    <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='notification'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
        }
    ?>

    <form method="POST" class="form-tugas">
        <h2>Tambah Tugas</h2>

        <!-- Pilih Mata Pelajaran -->
        <div class="form-group">
            <label for="id_mapel">Pilih Mata Pelajaran:</label>
            <select name="id_mapel" id="id_mapel" required>
                <option value="">-- Pilih Mata Pelajaran --</option>
                <?php
                $mapel_query = mysqli_query($conn, "SELECT id_mapel, nama_mapel FROM mata_pelajaran");
                while ($mapel = mysqli_fetch_assoc($mapel_query)) {
                    echo "<option value='" . $mapel['id_mapel'] . "'>" . $mapel['nama_mapel'] . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Detail Tugas -->
        <div class="form-group">
            <label for="nama_tugas">Nama Tugas:</label>
            <input type="text" name="nama_tugas" id="nama_tugas" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi" required></textarea>
        </div>

        <!-- Pilih Siswa -->
        <div class="form-group">
            <label>Pilih Siswa:</label>
            <button type="button" id="openModal" class="btn-secondary">Pilih Siswa</button>
            <div id="siswaModal" class="modal">
                <div class="modal-content">
                    <span id="closeModal" class="close">&times;</span>
                    <h3 class="modal-title">Daftar Siswa</h3>

                    <!-- Checkbox Pilih Semua -->
                    <div class="checkbox-container">
                        <div>
                            <input type="checkbox" id="selectAll"> <strong>Pilih Semua</strong>
                        </div>
                    </div>

                    <!-- Daftar Siswa -->
                    <div class="checkbox-container">
                        <?php
                            $siswa_query = mysqli_query($conn, "SELECT id_siswa, nama_siswa FROM siswa");

                            while ($siswa = mysqli_fetch_assoc($siswa_query)) {
                                echo "<div><input type='checkbox' class='siswa-checkbox' name='id_siswa[]' value='" . $siswa['id_siswa'] . "'> " . $siswa['nama_siswa'] . "</div>";
                            }
                        ?>
                    </div>

                    <!-- Tombol Oke -->
                    <button type="button" id="confirmSelection" class="btn-primary" disabled>Oke</button>
                </div>
            </div>
        </div>

        <!-- Menampilkan daftar siswa yang dipilih -->
        <div id="selectedSiswa" class="selected-siswa"></div>

        <script>
            document.getElementById("openModal").addEventListener("click", function() {
                document.getElementById("siswaModal").style.display = "block";
            });

            document.getElementById("closeModal").addEventListener("click", function() {
                document.getElementById("siswaModal").style.display = "none";
            });

            document.getElementById("confirmSelection").addEventListener("click", function() {
                document.getElementById("siswaModal").style.display = "none";
            });

            // Pilih Semua Checkbox
            document.getElementById("selectAll").addEventListener("change", function() {
                let checkboxes = document.querySelectorAll(".siswa-checkbox");
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            // Jika ada yang tidak dicentang, "Pilih Semua" akan nonaktif
            let siswaCheckboxes = document.querySelectorAll(".siswa-checkbox");
            siswaCheckboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    let allChecked = document.querySelectorAll(".siswa-checkbox:checked").length === siswaCheckboxes.length;
                    document.getElementById("selectAll").checked = allChecked;
                });
            });

            document.addEventListener("DOMContentLoaded", function () {
                const modal = document.getElementById("siswaModal");
                const openModalBtn = document.getElementById("openModal");
                const closeModalBtn = document.getElementById("closeModal");
                const pilihSemua = document.getElementById("selectAll");
                const checkboxes = document.querySelectorAll(".siswa-checkbox");
                const btnOke = document.getElementById("confirmSelection");
                const selectedSiswaDiv = document.getElementById("selectedSiswa");

                // Buka Modal
                openModalBtn.addEventListener("click", function () {
                    modal.style.display = "block";
                });

                // Tutup Modal
                closeModalBtn.addEventListener("click", function () {
                    modal.style.display = "none";
                });

                // Pilih Semua Checkbox
                pilihSemua.addEventListener("change", function () {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = pilihSemua.checked;
                    });
                    updateButtonState();
                });

                // Jika ada yang tidak dicentang, "Pilih Semua" akan nonaktif
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener("change", function () {
                        let allChecked = document.querySelectorAll(".siswa-checkbox:checked").length === checkboxes.length;
                        pilihSemua.checked = allChecked;
                        updateButtonState();
                    });
                });

                // Update Status Tombol "Oke"
                function updateButtonState() {
                    let checkedCount = document.querySelectorAll(".siswa-checkbox:checked").length;
                    btnOke.disabled = checkedCount === 0;
                }

                // Tombol Oke - Tampilkan Siswa yang Dipilih
                btnOke.addEventListener("click", function () {
                    modal.style.display = "none";
                    let selectedSiswa = [];
                    document.querySelectorAll(".siswa-checkbox:checked").forEach(checkbox => {
                        selectedSiswa.push(checkbox.parentNode.textContent.trim());
                    });

                    // Tampilkan daftar siswa terpilih
                    selectedSiswaDiv.innerHTML = selectedSiswa.length > 0
                        ? "<strong>Siswa Terpilih:</strong> <br>" + selectedSiswa.join("<br>")
                        : "";
                });

                // Klik di luar modal untuk menutup
                window.addEventListener("click", function (event) {
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                });
            });
        </script>

        <style>
            .notification {
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 5px;
                text-align: center;
                font-weight: bold;
            }

            .success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }

                .alert {
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #28a745; /* Hijau */
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                font-size: 16px;
            }

            .alert.success {
                background-color: #28a745; /* Hijau */
            }

                .selected-siswa {
                margin-top: 15px;
                padding: 10px;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 8px;
                font-size: 16px;
                color: black;
            }

            .btn-primary {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 8px 12px;
                cursor: pointer;
                border-radius: 5px;
                margin-top: 10px;
            }

            .btn-primary:hover {
                background-color: #0056b3;
            }

            .btn-secondary {
                background-color: #6c757d;
                color: white;
                border: none;
                padding: 8px 12px;
                cursor: pointer;
                border-radius: 5px;
            }

            .btn-secondary:hover {
                background-color: #5a6268;
            }
        </style>

        <!-- Tenggat Tugas -->
        <div class="form-group">
            <label for="tanggal_tenggat">Tanggal Tenggat:</label>
            <input type="date" name="tanggal_tenggat" id="tanggal_tenggat" required>
        </div>

        <div class="form-group">
            <label for="waktu_tenggat">Waktu Tenggat:</label>
            <input type="time" name="waktu_tenggat" id="waktu_tenggat" required>
        </div>

        <button type="submit" name="add_task" class="btn-primary">Tambah Tugas</button>
    </form>
</div>

<style>
    .container {
        width: 100%;
        background: #fff;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        max-width:10000px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.8rem;
    }

    .form-tugas {
        width: 100%;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        text-align: left;
        color: black;

    }

    input, select, textarea, button {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        margin-top: 5px;
    }

    button {
        background-color: #007BFF;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        width: 50%;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 24px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .checkbox-container div {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        font-weight: 500;
        Color: black;
    }

    .checkbox-container input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #007bff; /* Warna checkbox */
        cursor: pointer;
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        padding: 20px;
    }

    .modal-title {
        color: #333;
        font-size: 18px;
        font-weight: bold;
    }

</style>

<script>
    const modal = document.getElementById('siswaModal');
    const btn = document.getElementById('openModal');
    const span = document.getElementById('closeModal');

    btn.onclick = function () {
        modal.style.display = "block";
    };

    span.onclick = function () {
        modal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>

</body>
</html>
