<?php

// Mulai session jika belum dimulai
include 'connect.php';

// Pastikan koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();


// Debugging: Ensure user ID is set in session during login
if (!isset($_SESSION['id_pengguna']) && isset($_SESSION['username'])) {
    // Retrieve user ID based on username
    $query_user = "SELECT id_pengguna FROM pengguna WHERE username = ?";
    $stmt_user = $conn->prepare($query_user);
    if ($stmt_user) {
        $stmt_user->bind_param("s", $_SESSION['username']);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        if ($result_user->num_rows > 0) {
            $row_user = $result_user->fetch_assoc();
            $_SESSION['id_pengguna'] = $row_user['id_pengguna'];
        }
        $stmt_user->close();
    } else {
        die("Error preparing statement to fetch user ID: " . $conn->error);
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

// Ambil jumlah assignment yang diberikan oleh guru dari database
$query = "SELECT COUNT(*) AS total_assignments FROM assignments a JOIN pengajaran p ON a.id_mapel = p.id_mapel WHERE p.id_guru = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $id_guru);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_assignments = $row['total_assignments'];
    $stmt->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

// Mengambil daftar kelas yang diajar oleh guru
$query_kelas = "SELECT p.* FROM pengajaran p WHERE p.id_guru = ?";
$stmt_kelas = $conn->prepare($query_kelas);
if ($stmt_kelas) {
    $stmt_kelas->bind_param("i", $id_guru);
    $stmt_kelas->execute();
    $result_kelas = $stmt_kelas->get_result();
    $kelas_list = [];
    while ($row = $result_kelas->fetch_assoc()) {
        $kelas_list[] = $row;
    }
    $stmt_kelas->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

// Ambil tugas yang belum dikumpulkan oleh siswa dari kelas yang diajar
$query_reminder = "SELECT t.nama_tugas, t.tanggal_tenggat, s.nama_siswa FROM tugas t JOIN siswa s ON t.id_siswa = s.id_siswa JOIN pengajaran p ON t.id_mapel = p.id_mapel WHERE p.id_guru = ? AND t.status != 'Submitted'";
$stmt_reminder = $conn->prepare($query_reminder);
if ($stmt_reminder) {
    $stmt_reminder->bind_param("i", $id_guru);
    $stmt_reminder->execute();
    $result_reminder = $stmt_reminder->get_result();

    // Menyimpan daftar tugas yang belum di-submit dalam array
    $reminders = [];
    while ($row = $result_reminder->fetch_assoc()) {
        $reminders[] = $row;
    }
    $stmt_reminder->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

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
    <style>
.modal {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1001;
    background-color: white;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    width: 400px;
    border-radius: 8px;
}
.modal-content {
    position: relative;
}
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
}
</style>
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
            <aside class="sidebar">
                <nav class="menu">
                    <ul>
                        <li><a href="guru_dashboard.php" class="active"><i class="fas fa-home"></i></a></li>
                        <li><a href="kelola_tugas.php"><i class="fas fa-chalkboard-teacher"></i></a></li>
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
                <div class="header-text" style="margin-top: 0px;">
                </div>
            </header>
                
</body>

</html>
