<?php
session_start(); // Mulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke login jika belum login
    exit();
}

// Ambil id_siswa dari session
$id_siswa = $_SESSION['username'];
$user_type = $_SESSION['tipe_pengguna'];

// Include koneksi database
include 'connect.php';



// Mengambil daftar tugas yang belum di-submit untuk form submit
$stmt_submit = $conn->prepare("SELECT id_tugas, nama_tugas, deskripsi, tanggal_tenggat FROM tugas WHERE (id_siswa = ?) AND status != 'Submitted'");
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
$stmt_view = $conn->prepare("SELECT id_tugas, nama_tugas, deskripsi, status, file_name FROM tugas WHERE id_siswa = ? AND status = 'Submitted'");
$stmt_view->bind_param("i", $id_siswa);
$stmt_view->execute();
$result_view = $stmt_view->get_result();

// Menyimpan daftar tugas yang sudah di-submit dalam array
$tugas_list_view = [];
while ($row = $result_view->fetch_assoc()) {
    $tugas_list_view[] = $row;
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
    $retract_query = "UPDATE tugas SET id_siswa = ?, file_name = NULL, status = 'Returned' WHERE id_tugas = ? AND id_siswa = ?";
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
// Tentukan waktu saat ini
$hari = date('l'); // Nama hari (Senin, Selasa, dll)
$tanggal = date('F jS, Y'); // Format tanggal "October 21st, 2024"


$conn->close();
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
    <script>
        // Menyimpan data tugas dalam JavaScript agar dapat digunakan saat dropdown dipilih
        const tugasDataSubmit = <?php echo json_encode($tugas_list_submit); ?>;
        const tugasDataView = <?php echo json_encode($tugas_list_view); ?>;

        // Fungsi untuk menampilkan deskripsi tugas berdasarkan pilihan dropdown untuk view form
        function showTaskDetailsView() {
            const selectElement = document.getElementById('id_tugas_view');
            const taskDetailsElement = document.getElementById('task-details-view');
            const selectedId = parseInt(selectElement.value);

            // Cari tugas yang sesuai dengan id_tugas yang dipilih
            const selectedTask = tugasDataView.find(task => task.id_tugas === selectedId);

            // Jika tugas ditemukan, tampilkan detailnya
            if (selectedTask) {
                taskDetailsElement.innerHTML = `
                    <h3>Task Details</h3>
                    <p><strong>Task ID:</strong> ${selectedTask.id_tugas}</p>
                    <p><strong>Task Name:</strong> ${selectedTask.nama_tugas}</p>
                    <p><strong>Description:</strong> ${selectedTask.deskripsi}</p>
                    <p><strong>Status:</strong> ${selectedTask.status}</p>
                    ${selectedTask.file_name ? "<p>Submitted File: <a href='uploads/" + selectedTask.file_name + "' download>Download</a></p>" : "<p>No file found for this task.</p>"}
                `;
            } else {
                taskDetailsElement.innerHTML = "<p>No task selected. Please select a task to view details.</p>";
            }
        }
    </script>
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
    <form method="POST">
        <label for="id_tugas_view">Select Submitted Task:</label>
        <select name="id_tugas_view" id="id_tugas_view" onchange="showTaskDetailsView()" required>
            <option value="">--Select Task--</option>
            <?php
            foreach ($tugas_list_view as $tugas) {
                echo "<option value='" . $tugas['id_tugas'] . "'>" . $tugas['nama_tugas'] . "</option>";
            }
            ?>
        </select>
    </form>

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
       <!-- Profile Sidebar -->
  <div id="sidebar" class="profile-sidebar">
    <div class="profile-overview">
        <img src="https://cdn.discordapp.com/attachments/749553877333835846/1305882693116100608/3778bb3bb63024da3c52aa0f47fdd603.png?ex=6734a588&is=67335408&hm=5b951564b1a1246b3decb6dfdceafbde2e16ef24ae1484c09e81bb268096d39b&" alt="Profile Picture" class="profile-pic">
        <h2><?php echo htmlspecialchars($id_siswa);  ?>!</h2></h2>
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
