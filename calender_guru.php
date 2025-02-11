<?php

// Sertakan koneksi database
include 'connect.php'; // Pastikan path benar

// Hanya jalankan jika metode HTTP adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangkap data dari POST
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';

    // Validasi input
    if (empty($title) || empty($date)) {
        echo json_encode([
            'success' => false,
            'error' => 'Judul dan tanggal tidak boleh kosong.'
        ]);
        exit;
    }

    // Simpan event ke database
    $stmt = $conn->prepare("INSERT INTO events (title, date, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $date, $description);

    if ($stmt->execute()) {
        // Kembalikan respons berhasil
        echo json_encode([
            'success' => true,
            'message' => 'Event berhasil disimpan!',
            'event' => [
                'title' => $title,
                'start' => $date,
                'description' => $description
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Gagal menyimpan event: ' . $stmt->error
        ]);
    }

    $stmt->close();
}



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
?>




<!DOCTYPE html>
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

.event-container {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Meratakan isi */
    width: 100%; /* Memenuhi tabel */
    padding: 6px 10px;
    border-radius: 6px;
    background-color: rgb(66, 133, 244);
    margin-bottom: 4px;
    transition: all 0.3s ease;
    box-sizing: border-box; /* Menghindari overflow */
}

.event-container:hover {
    background-color: rgb(52, 120, 230);
    transform: scale(1.02);
}

.event-time {
    font-size: 0.8em;
    font-weight: bold;
    color: white;
    margin-right: 8px;
    flex-shrink: 0; /* Supaya tidak terpotong */
}

.event-title {
    font-size: 0.9em;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex-grow: 1; /* Mengisi ruang yang tersedia */
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
        <aside class="sidebar" style="margin-top:-798px">
            <nav class="menu">
                <ul>
                    <li><a href="guru_dashboard.php"><i class="fas fa-home"></i></a></li>
                    <li><a href="kelola_tugas.php"><i class="fas fa-chalkboard-teacher"></i></a></li>
                    <li><a href="calender_guru.php" class="active"><i class="fas fa-calendar-alt"></i></a></li>
                    <li><a href="lihat_tugas.php"><i class="fas fa-calendar-alt"></i></a></li>

                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <div class="header-text">
                </div>
            </header>
            <h1 style="margin-top:-75px">Agenda</h1>
                <div id="calendar">
                   
        </main>


<!-- FullCalendar Script -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>

<!-- Modal Tambah Event -->
<div id="event-modal" class="modal" style="display: none;">
    <div class="modal-content">
    <span class="close-btn">&times;</span>
        <h3>Tambah Event</h3>
        <form id="event-form">
    <label for="event-title">Judul Event:</label>
    <input type="text" id="event-title" name="title" required>
    
    <label for="event-date">Tanggal:</label>
    <input type="text" id="event-date" name="date" readonly>
    
    <label for="end-time">Deadline</label>
    <input type="time" id="end-time" name="end_time" required>
    
    <label for="event-desc">Deskripsi:</label>
    <textarea id="event-desc" name="description"></textarea>
    
    <button type="submit">Simpan</button>
</form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var modal = document.getElementById('event-modal');
    var form = document.getElementById('event-form'); // Pastikan ID ini ada di HTML
    var eventDateInput = document.getElementById('event-date');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        events: 'load_events.php', // Ambil event dari server
        eventContent: function (info) {
            var endTime = info.event.end ? new Date(info.event.end).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }) : 'N/A';

            // Gabungkan informasi waktu dengan gaya HTML rapi
            return {
                html: `
                    <div class="event-container">
                        <div class="event-time">${endTime}</div>
                        <div class="event-title">${info.event.title}</div>
                    </div>
                `
            };
        },
        dateClick: function (info) {
            modal.style.display = 'block';
            eventDateInput.value = info.dateStr;
        },
        eventClick: function (info) {
            alert(
                'Event: ' + info.event.title +
                '\nStart: ' + (info.event.start ? info.event.start.toLocaleString() : 'N/A') +
                '\nEnd: ' + (info.event.end ? info.event.end.toLocaleString() : 'N/A')
            );
        },
        windowResize: function () {
            calendar.updateSize();
        }
    });

    // Form submission untuk menyimpan event
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(form);

        fetch('save_events.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendar.addEvent({
                    id: data.event.id,
                    title: data.event.title,
                    start: data.event.start,
                    end: data.event.end
                });

                alert('Berhasil menyimpan event: ' + data.message);
                modal.style.display = 'none';
                form.reset();
            } else {
                alert('Gagal menyimpan event: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    calendar.render();
});


</script>
</body>
</html>