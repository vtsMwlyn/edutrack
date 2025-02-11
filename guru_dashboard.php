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
    <title>EduTrack Teacher Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
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

                <section class="welcome-section">
                    <div class="card welcome-card">
                        <h2>Welcome back...</h2>
                    </div>
                    <div class="card latest-assignments">
                        <h2>Latest Assignments</h2>
                        <p>Total Assignments: <?php echo $total_assignments; ?></p>
                    </div>
                </section>

                <section class="classroom-section">
                    <h3>Your Classes</h3>
                    <div class="classroom-list">
                        <?php if (!empty($kelas_list)): ?>
                            <?php foreach ($kelas_list as $kelas): ?>
                                <div class="class-item" onclick="openClass('<?php echo htmlspecialchars($kelas['nama_kelas']); ?>')">
                                    <h4><?php echo htmlspecialchars($kelas['nama_kelas']); ?></h4>
                                    <p><?php echo htmlspecialchars($kelas['deskripsi']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>You have no classes assigned yet.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <section class="reminder-section">
                    <h3>Assignment Reminders</h3>
                    <div class="reminders">
                        <?php if (!empty($reminders)): ?>
                            <?php foreach ($reminders as $reminder): ?>
                                <div class="reminder-item">
                                    <strong>Assignment:</strong> <?php echo htmlspecialchars($reminder['nama_tugas']); ?><br>
                                    <strong>Due Date:</strong> <?php echo htmlspecialchars($reminder['tanggal_tenggat']); ?><br>
                                    <strong>Student:</strong> <?php echo htmlspecialchars($reminder['nama_siswa']); ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="reminder-item">No pending assignments to be submitted.</div>
                        <?php endif; ?>
                    </div>
                </section>
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
