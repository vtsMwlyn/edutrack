
<?php
// Mulai session jika belum dimulai
session_start();

// Pastikan user sudah login, jika belum arahkan ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'connect.php';

// Ambil nama pengguna dari session
$username = $_SESSION['username'];

// Ambil jumlah assignment dari database
$query = "SELECT COUNT(*) AS total_assignments FROM assignments"; // Sesuaikan nama tabel jika berbeda
$result = $conn->query($query);
$row = $result->fetch_assoc();
$total_assignments = $row['total_assignments'];

$stmt_reminder = $conn->prepare("SELECT nama_tugas, tanggal_tenggat FROM tugas WHERE (id_siswa IS NULL OR id_siswa != ?) AND status != 'Submitted'");
$stmt_reminder->bind_param("i", $id_siswa);
$stmt_reminder->execute();
$result_reminder = $stmt_reminder->get_result();

// Menyimpan daftar tugas yang belum di-submit dalam array
$reminders = [];
while ($row = $result_reminder->fetch_assoc()) {
    $reminders[] = $row;
}

// Mengambil data tugas yang statusnya "Returned" atau status lainnya yang menandakan tugas telah dikembalikan
$stmt = $conn->prepare("SELECT id_tugas, nama_tugas, status FROM tugas WHERE id_siswa = ? AND status IN ('Returned', 'Pending')");
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();

$tugas_kembali = [];
while ($row = $result->fetch_assoc()) {
    $tugas_kembali[] = $row;
}

$stmt = $conn->prepare("SELECT id_tugas, nama_tugas, tanggal_tenggat 
                        FROM tugas 
                        WHERE id_siswa = ? 
                        AND status != 'Submitted' 
                        AND tanggal_tenggat < NOW() 
                        ORDER BY tanggal_tenggat DESC");
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();

// Menyimpan tugas yang lewat tenggat dalam array
$tugas_lewat_tenggat = [];
while ($row = $result->fetch_assoc()) {
    $tugas_lewat_tenggat[] = $row;
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
    <title>EduTrack Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <style>
       

        
    </style>
</head>

<body>
    <div class="dashboard-card">
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <nav class="menu">
                    <ul>
                        <li><a href="#"><i class="fas fa-home"></i></a></li>
                        <li><a href="calender.php"><i class="fas fa-calendar-alt"></i></a></li>
                        <li><a href="mapel.php"><i class="fas fa-user"></i></a></li>
                        
                    </ul>
                </nav>
               
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <header class="main-header">
                    <div class="header-text">
                        <h1>EduTrack</h1>
                        <h2>Hello, <?php echo htmlspecialchars($username); ?>!</h2>
                        <p><?php echo $hari . ', ' . $tanggal; ?></p>
                    </div>
                </header>

              
                    <div class="card latest-results">
        <h2>Latest Results</h2>
        <ul>
            <?php if ($tugas_kembali): ?>
                <li>
                    <div class="notification">
                        <p><strong><?php echo $tugas_kembali['nama_tugas']; ?></strong> has been reviewed by your teacher and returned.</p>
                    </div>
                </li>
            <?php else: ?>
                <li>No tasks have been reviewed or returned yet.</li>
            <?php endif; ?>
        </ul>
    </div>


</body>
</html>
                </section>

                <section class="action-section">
                    <div class="action-card">
                    <h3>Assignments</h3>
                    <p><?php echo $total_assignments; ?></p> <!-- Menampilkan jumlah assignment -->
                    </div>
                  
                   <!-- Card Past Due -->
    <div class="action-card">
        <h3>Past Due</h3>
        <?php if (count($tugas_lewat_tenggat) > 0): ?>
            <ul>
                <?php foreach ($tugas_lewat_tenggat as $tugas): ?>
                    <li><strong><?php echo $tugas['nama_tugas']; ?></strong> (Due: <?php echo $tugas['tanggal_tenggat']; ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No tasks are past due.</p>
        <?php endif; ?>
    </div>
                </section>
                
                
                <div class="recent-results">
        <h3>Reminder</h3>
    </div>
      <!-- Reminder Section -->
      <div class="reminders">
            <?php if (!empty($reminders)): ?>
                <?php foreach ($reminders as $reminder): ?>
                    <div class="reminder-item">
                        <strong>Tugas:</strong> <?php echo htmlspecialchars($reminder['nama_tugas']); ?><br>
                        <strong>Due Date:</strong> <?php echo htmlspecialchars($reminder['tanggal_tenggat']); ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="reminder-item">Tidak ada tugas yang belum di-submit.</div>
            <?php endif; ?>
        </div>
                

</div>




  <!-- Profile Sidebar -->
  <div id="sidebar" class="profile-sidebar">
    <div class="profile-overview">
        <img src="https://cdn.discordapp.com/attachments/749553877333835846/1305882693116100608/3778bb3bb63024da3c52aa0f47fdd603.png?ex=6734a588&is=67335408&hm=5b951564b1a1246b3decb6dfdceafbde2e16ef24ae1484c09e81bb268096d39b&" alt="Profile Picture" class="profile-pic">
        <h2><?php echo htmlspecialchars($username);  ?>!</h2></h2>
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
                   
</button>

                    </ul>
                </nav>
            </aside>
            
		   
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

</body>

</html>