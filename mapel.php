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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $id_tugas = $_POST["id_tugas"];
    
    // Ambil data dari database berdasarkan ID tugas
    $stmt = $conn->prepare("SELECT * FROM tugas WHERE id_tugas = ? AND (id_siswa IS NULL OR id_siswa = ?)");
    $stmt->bind_param("is", $id_tugas, $id_siswa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Validasi file yang diupload
        $target_dir = "uploads/";

        // Loop untuk memproses setiap file yang diupload
        foreach ($_FILES['file']['name'] as $index => $file_name) {
            if (!empty($file_name)) {
                $tmp_name = $_FILES['file']['tmp_name'][$index];
                $target_file = $target_dir . basename($file_name);

                // Debugging file upload
                echo "Processing file: $file_name<br>";
                echo "Temporary location: $tmp_name<br>";

                // Pastikan file berhasil dipindahkan
                if (move_uploaded_file($tmp_name, $target_file)) {
                    echo "File moved successfully to: $target_file<br>";

                    // Update data tugas dengan detail siswa dan nama file
                    $update_query = "UPDATE tugas SET id_siswa = ?, file_name = ?, status = 'Submitted' WHERE id_tugas = ?";
                    $stmt_update = $conn->prepare($update_query);

                    if ($stmt_update === false) {
                        die("Prepare failed: " . $conn->error);
                    }

                    $stmt_update->bind_param("ssi", $id_siswa, $file_name, $id_tugas);

                    if ($stmt_update->execute()) {
                        echo "Database updated successfully. File name: $file_name<br>";
                    } else {
                        die("Error updating database: " . $stmt_update->error);
                    }

                    $stmt_update->close();
                } else {
                    echo "Failed to move file: $file_name<br>";
                }
            } else {
                echo "No file uploaded.";
            }
        }
    } else {
        echo "No such assignment found or already submitted.";
    }


    $stmt->close();
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Proses form submission di sini

    // Setelah form disubmit, lakukan redirect untuk menyegarkan halaman
    header("Location: " . $_SERVER['PHP_SELF']);
    exit; // Pastikan tidak ada kode yang berjalan setelah header
}
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit and View Task</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    

    </style>
    <script>
        // JavaScript functions to toggle sections and display task details
        function showSection(section) {
            document.querySelectorAll('.task-section').forEach(el => el.classList.remove('active'));
            document.getElementById(section).classList.add('active');

            document.querySelectorAll('.button-container button').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`[data-section="${section}"]`).classList.add('active');
        }

        const tugasDataSubmit = <?php echo json_encode($tugas_list_submit); ?>;
        const tugasDataView = <?php echo json_encode($tugas_list_view); ?>;

        function showTaskDetailsSubmit() {
            const selectElement = document.getElementById('id_tugas');
            const taskDetailsElement = document.getElementById('task-details-submit');
            const selectedId = parseInt(selectElement.value);
            const selectedTask = tugasDataSubmit.find(task => task.id_tugas === selectedId);

            if (selectedTask) {
                taskDetailsElement.innerHTML = `
                    <h3>Task Details</h3>
                    <p><strong>Task Name:</strong> ${selectedTask.nama_tugas}</p>
                    <p><strong>Description:</strong> ${selectedTask.deskripsi}</p>
                    <p><strong>Due Date:</strong> ${selectedTask.tanggal_tenggat}</p>
                `;
            } else {
                taskDetailsElement.innerHTML = "<p>No task selected. Please select a task to view details.</p>";
            }
        }

        function showTaskDetailsView() {
            const selectElement = document.getElementById('id_tugas_view');
            const taskDetailsElement = document.getElementById('task-details-view');
            const selectedId = parseInt(selectElement.value);
            const selectedTask = tugasDataView.find(task => task.id_tugas === selectedId);

            if (selectedTask) {
    const submittedAt = new Date(selectedTask.submitted_at);  // Assuming 'submitted_at' is the timestamp column in your database
    
    // Format the date and time
    const submissionDate = submittedAt.toLocaleDateString(); // E.g. "10/8/2024"
    const submissionTime = submittedAt.toLocaleTimeString(); // E.g. "12:30:00 PM"
    
    taskDetailsElement.innerHTML = `

        <p><strong>Task Name:</strong> ${selectedTask.nama_tugas}</p>
        <p><strong>Description:</strong> ${selectedTask.deskripsi}</p>
        <p><strong>Status:</strong> ${selectedTask.status}</p>
        <p><strong>Submitted On:</strong> ${submissionDate} at ${submissionTime}</p> <!-- Display submission date and time -->
        ${selectedTask.file_name ? `<p>Submitted File: <a href='uploads/${selectedTask.file_name}' download>Download</a></p>` : "<p>No file found for this task.</p>"}
    `;
} else {
    taskDetailsElement.innerHTML = "<p>No task selected. Please select a task to view details.</p>";
}
        }
    </script>
</head>
<body>
<body>
    <div class="dashboard-card">
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <nav class="menu">
                    <ul>
                        <li><a href="siswa_dashboard.php"><i class="fas fa-home"></i></a></li>
                        <li><a href="calender.php"><i class="fas fa-calendar-alt"></i></a></li>
                        <li><a href="mapel.php"><i class="fas fa-user"></i></a></li>
                    </ul>
                </nav>
                
            </aside>
            <main class="main-content">
                <header class="main-header">
                    
    <div class="container">
        <h2>Task Management</h2>
        <div class="button-container">
            <button data-section="submit-section" onclick="showSection('submit-section')" class="active">Submit Task</button>
            <button data-section="view-section" onclick="showSection('view-section')">View Task</button>
            
        </div>
        
       
                    

        <!-- Submit Task Section -->
        <div id="submit-section" class="task-section active">
            <form method="POST" enctype="multipart/form-data">
                <label for="id_tugas">Select Task:</label>
                <select name="id_tugas" id="id_tugas" onchange="showTaskDetailsSubmit()" required>
                    <option value="">--Select Task--</option>
                    <?php
                    foreach ($tugas_list_submit as $tugas) {
                        echo "<option value='" . $tugas['id_tugas'] . "'>" . $tugas['nama_tugas'] . "</option>";
                    }
                    ?>
                </select>

                <div id="task-details-submit" class="task-details">
                    <p>No task selected. Please select a task to view details.</p>
                </div>

                <label for="file">Upload File(s):</label>
                <input type="file" name="file[]" id="file" accept="image/*,application/pdf" multiple required>

                <input type="submit" class="btn btn-primary" value="Submit Task">

                  <!-- PHP to handle file upload -->
                  <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
                            $targetDir = 'uploads/';
                            $uploadSuccess = true;
                            
                            // Loop through each uploaded file
                            foreach ($_FILES['file']['name'] as $key => $name) {
                                $fileTmpName = $_FILES['file']['tmp_name'][$key];
                                $targetFilePath = $targetDir . basename($name);

                                // Move uploaded file to the uploads directory
                                if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                                    echo "<p>File $name uploaded successfully.</p>";
                                } else {
                                    echo "<p>Error uploading file $name.</p>";
                                    $uploadSuccess = false;
                                }
                            }

                            if ($uploadSuccess) {
                                echo "<p>All files have been uploaded successfully.</p>";
                            }
                        }
                        ?>
            </form>
        </div>

        <!-- View Task Section -->
        <div id="view-section" class="task-section">
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
            <div id="task-details-view" class="task-details">
                <p>No task selected. Please select a task to view details.</p>
            </div>
        </div>

       

        
        
        <!-- Profile Sidebar -->
  <div id="sidebar" class="profile-sidebar">
    <div class="profile-overview">
        <img src="https://cdn.discordapp.com/attachments/749553877333835846/1305882693116100608/3778bb3bb63024da3c52aa0f47fdd603.png?ex=6734a588&is=67335408&hm=5b951564b1a1246b3decb6dfdceafbde2e16ef24ae1484c09e81bb268096d39b&" alt="Profile Picture" class="profile-pic">
        <h2><?php echo htmlspecialchars($id_siswa);  ?>!</h2></h2>
    </div>
    <div class="profile-stats">
        <div>Student</div>
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
            
		   
</div>




<script>
    document.addEventListener('DOMContentLoaded', function() {
    const dates = document.querySelectorAll('.calendar .date');
    const popup = document.getElementById('event-popup');
    const eventDetails = document.getElementById('event-details');
    const closePopupButton = document.getElementById('close-popup');

    dates.forEach(function(date) {
        date.addEventListener('click', function(e) {
            // Mendapatkan event terkait dari tanggal yang diklik
            const event = date.getAttribute('data-event');

            // Menampilkan pop-up dengan detail event
            eventDetails.textContent = event;
            popup.style.display = 'block';

            // Mengatur posisi pop-up di sebelah tanggal yang dipilih
            const rect = date.getBoundingClientRect();
            popup.style.top = `${rect.top + window.scrollY + rect.height}px`;
            popup.style.left = `${rect.left + window.scrollX + rect.width / 2}px`;
        });
    });

    // Menutup pop-up ketika tombol 'Close' diklik
    closePopupButton.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    // Menutup pop-up jika user mengklik di luar pop-up
    window.addEventListener('click', function(event) {
        if (event.target !== popup && !popup.contains(event.target) && !event.target.classList.contains('date')) {
            popup.style.display = 'none';
        }
    });
});

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
        </header>
    </div>
</body>
</html>

