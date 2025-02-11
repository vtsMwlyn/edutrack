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
$user_type = $_SESSION['tipe_pengguna'];

$query = "SELECT nama_tugas, tanggal_tenggat, deskripsi FROM tugas WHERE status = 'pending'";
$result = $conn->query($query);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'title' => $row['nama_tugas'],
            'start' => $row['tanggal_tenggat'],
            'description' => $row['deskripsi']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
  

    <style>
        
     
        
    </style>
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
                 <div class="calendar-container">
                     <h1 style="text-align: center; color: #504e76;">Kalender Tugas</h1>
                        <div id="calendar"></div>
                 </div>
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

    document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: <?php echo json_encode($events); ?>,
                eventClick: function (info) {
                    // Prevent default action to avoid page refresh
                    info.jsEvent.preventDefault();

                    // Menampilkan pop-up dengan SweetAlert
                    Swal.fire({
                        title: `<div style="font-size: 20px; color: #504e76;">${info.event.title}</div>`,
                        html: `
                            <div style="text-align: left; margin-top: 10px;">
                                <b>Deadline:</b> ${info.event.start.toISOString().split('T')[0]}<br>
                                <b>Deskripsi:</b> ${info.event.extendedProps.description || 'Tidak ada deskripsi.'}
                            </div>`,
                        icon: 'info',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#a3b565',
                        customClass: {
                            popup: 'swal2-rounded swal2-shadow'
                        }
                    });
                },
                eventMouseEnter: function(info) {
                    info.el.style.cursor = 'pointer';
                    info.el.style.backgroundColor = '#F8C471'; // Highlight on hover
                },
                eventMouseLeave: function(info) {
                    info.el.style.backgroundColor = '#f1642e'; // Revert on mouse leave
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>
