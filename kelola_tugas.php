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
    $id_siswa = $_POST['id_siswa'];
    
    // Pastikan id_mapel ada dalam POST
    $id_mapel = isset($_POST['id_mapel']) ? $_POST['id_mapel'] : null;

    // Pastikan id_mapel tidak kosong
    if ($id_mapel !== null) {
        // Tambahkan tugas
        $mapel_ids = implode(",", $id_mapel); // Convert array to comma-separated values
        $sql = "INSERT INTO tugas (nama_tugas, deskripsi, tanggal_tenggat, status, id_mapel, id_siswa) 
                VALUES ('$nama_tugas', '$deskripsi', '$tanggal_tenggat', 'pending', '$mapel_ids', '$id_siswa')";
        if (mysqli_query($conn, $sql)) {
            $success_message = "Tugas berhasil ditambahkan untuk siswa!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
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
$user_type = $_SESSION['tipe_pengguna'];

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
    <title>EduTrack Teacher Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
</head>
<body>
    <div class="dashboard-card">
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <nav class="menu">
                    <ul>
                        <li><a href="guru_dashboard.php"><i class="fas fa-home"></i></a></li>
                        <li><a href="kelola_tugas.php"><i class="fas fa-chalkboard-teacher"></i></a></li>
                        <li><a href="mapel_guru.php"><i class="fas fa-user"></i></a></li>
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

                <!-- Pesan Sukses atau Error -->
                <?php if ($success_message): ?>
                    <p style="color: green;"><?php echo $success_message; ?></p>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>

            <div class="container">
                    <!-- Form Pilih Siswa -->
                    <form method="POST">
                        <h2>Pilih Siswa</h2>
                        <label for="id_siswa_selected">Pilih Siswa:</label>
                        <select name="id_siswa_selected" id="id_siswa_selected" required>
                            <option value="">-- Pilih Siswa --</option>
                            <!-- PHP loop untuk daftar siswa -->
                            <?php
                            $result = mysqli_query($conn, "SELECT id_siswa, nama_siswa FROM siswa");
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selected = ($row['id_siswa'] == $id_siswa_selected) ? "selected" : "";
                                echo "<option value='" . $row['id_siswa'] . "' $selected>" . $row['nama_siswa'] . "</option>";
                            }
                            ?>
                        </select>
                        <button type="submit">Lihat Mata Pelajaran</button>
                    </form>

                    <?php if ($id_siswa_selected): ?>
                        <!-- Form Tambah Tugas -->
                        <form method="POST" class="form.2">
                            <h2>Tambah Tugas untuk Siswa</h2>
                            <input type="hidden" name="id_siswa" value="<?php echo $id_siswa_selected; ?>">
                            <label for="nama_tugas">Nama Tugas:</label>
                            <input type="text" name="nama_tugas" id="nama_tugas" placeholder="Masukkan nama tugas" required>

                            <label for="deskripsi">Deskripsi:</label>
                            <textarea name="deskripsi" id="deskripsi" placeholder="Masukkan deskripsi tugas" required></textarea>

                            <label for="tanggal_tenggat">Tanggal Tenggat:</label>
                            <input type="date" name="tanggal_tenggat" id="tanggal_tenggat" required>

                            <label for="id_mapel">Mata Pelajaran Wajib & Pilihan:</label>
                            <select name="id_mapel[]" id="id_mapel" multiple required>
                                <?php
                                $mapel_query = mysqli_query($conn, "SELECT id_mapel, nama_mapel FROM mata_pelajaran");
                                while ($mapel = mysqli_fetch_assoc($mapel_query)) {
                                    echo "<option value='" . $mapel['id_mapel'] . "'>" . $mapel['nama_mapel'] . "</option>";
                                }
                                ?>
                            </select>

                            <button type="submit" name="add_task">Tambah Tugas</button>
                        </form>

                        <!-- Tabel Daftar Tugas -->
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Tugas</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Tenggat</th>
                                    <th>Status</th>
                                    <th>Mata Pelajaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tugas_query = mysqli_query($conn, "SELECT t.*, mp.nama_mapel 
                                                                    FROM tugas t 
                                                                    JOIN mata_pelajaran mp ON FIND_IN_SET(t.id_mapel, mp.id_mapel) 
                                                                    WHERE t.id_siswa = '$id_siswa_selected'");
                                if ($tugas_query && mysqli_num_rows($tugas_query) > 0) {
                                    while ($tugas = mysqli_fetch_assoc($tugas_query)) {
                                        echo "<tr>
                                                <td>" . $tugas['nama_tugas'] . "</td>
                                                <td>" . $tugas['deskripsi'] . "</td>
                                                <td>" . $tugas['tanggal_tenggat'] . "</td>
                                                <td>" . $tugas['status'] . "</td>
                                                <td>" . $tugas['nama_mapel'] . "</td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>Tidak ada tugas untuk siswa ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </main>
            <!-- Profile Sidebar -->
  <div id="sidebar" class="profile-sidebar">
    <div class="profile-overview">
        <img src="https://cdn.discordapp.com/attachments/749553877333835846/1305882693116100608/3778bb3bb63024da3c52aa0f47fdd603.png?ex=6734a588&is=67335408&hm=5b951564b1a1246b3decb6dfdceafbde2e16ef24ae1484c09e81bb268096d39b&" alt="Profile Picture" class="profile-pic">
        <h2><?php echo htmlspecialchars($username);  ?>!</h2></h2>
    </div>
    <div class="profile-stats">
    <div><?php echo htmlspecialchars($user_type); ?></div>
        <div>Unit: Highschool</div>
    </div>
    <button class="logout-button" onclick="window.location.href='logout.php'">
    <i class="fas fa-sign-out-alt"></i> 
</button>

</button>

           
    <!-- Toggle Button -->
<div class="right-container">
			<button id="toggle-btn" class="sidebar-toggle">
			<i class="fas fa-user-circle"></i>
			</button>
			</div>
                <nav class="menus">
                    <ul>
                   
</button>

                    </ul>
                </nav>
            </aside>
            
		   
</div>
        </div>
    </div>
    <script>
    // Toggle Sidebar on Button Click
document.getElementById("toggle-btn").addEventListener("click", function() {
    var sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("open");
});

// Fungsi untuk mengarahkan ke halaman kelas saat kartu diklik
function openClass(subject) {
        alert('Masuk ke kelas: ' + subject);
        // Anda bisa mengubah alert menjadi redirect ke halaman kelas, misalnya:
        // window.location.href = '/kelas/' + subject.toLowerCase();
    }

</script>
</body>
</html>
