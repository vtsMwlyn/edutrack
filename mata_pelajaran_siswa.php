<?php
// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'connect.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pengguna dari session
$username = $_SESSION['username'];

// Cek apakah siswa sudah memilih mata pelajaran
$sql = "SELECT id_siswa FROM siswa WHERE id_pengguna = (SELECT id_pengguna FROM pengguna WHERE username = ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query Error: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $siswa_id = $row['id_siswa'];

    $sql_check = "SELECT COUNT(*) as total FROM mata_pelajaran_siswa WHERE id_siswa = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $siswa_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $data = $result_check->fetch_assoc();

    if ($data['total'] < 14) {
        // Ada mata pelajaran yang belum lengkap, tetap tampilkan halaman pilih mata pelajaran
        echo '<script>
            alert("Anda harus memilih 14 mata pelajaran terlebih dahulu.");
        </script>';
    } else {
        // Jika sudah memilih 14 mata pelajaran, langsung arahkan ke siswa_dashboard.php
        header("Location: siswa_dashboard.php");
        exit();
    }
} else {
    // Jika tidak ditemukan data siswa
    echo '<script>
        alert("Data siswa tidak ditemukan.");
    </script>';
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilihan Mata Pelajaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Styling */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #C4C3E3;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
            margin: 0 auto;
        }
        h3, h4 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .subjects-wrapper {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .subject-container {
            flex: 1;
            max-height: 400px;
            overflow-y: hidden;
            padding-right: 10px;
        }
        .subjects-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            max-height: 340px;
            overflow-y: auto;
            padding-right: 5px;
            scrollbar-width: none;
        }
        .subjects-grid::-webkit-scrollbar {
            display: none;
        }
        .subject-card {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #F8F8F8;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.2s;
            cursor: pointer;
            gap: 15px;
            font-size: 16px;
            font-weight: 500;
        }
        .subject-card:hover {
            transform: translateY(-5px);
            background-color: #FCDD9D;
        }
        .subject-card.selected {
            background-color: #F1642E;
            color: white;
        }
        .divider {
            width: 3px;
            background: #ddd;
            height: 400px;
            align-self: center;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn-submit {
            padding: 12px 25px;
            font-size: 16px;
            background: #FCDD9D;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .btn-submit:disabled {
            background: #b8e994;
            cursor: not-allowed;
        }
        .btn-submit:hover:not(:disabled) {
            transform: translateY(-3px);
        }
        .subject-icon {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <?php
    // Mulai session jika belum dimulai
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Pastikan user sudah login, jika belum arahkan ke halaman login
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    include 'connect.php';

    // Ambil nama pengguna dari session
    $username = $_SESSION['username'];
    ?>
    <div class="container">
        <h3>Pilih Mata Pelajaran Anda</h3>
        <form id="subject-form" method="POST" action="save_subjects.php">
            <div class="subjects-wrapper">
                <div class="subject-container">
                    <h4>Mata Pelajaran Wajib</h4>
                    <div id="wajib" class="subjects-grid">
                        <!-- Mata pelajaran wajib -->
                        <div class="subject-card wajib" data-subject="Pendidikan Agama dan Budi Pekerti"><i class="fas fa-praying-hands subject-icon"></i>Pendidikan Agama dan Budi Pekerti</div>
                        <div class="subject-card wajib" data-subject="Pendidikan Pancasila"><i class="fas fa-flag subject-icon"></i>Pendidikan Pancasila</div>
                        <div class="subject-card wajib" data-subject="Bahasa Indonesia"><i class="fas fa-book-open subject-icon"></i>Bahasa Indonesia</div>
                        <div class="subject-card wajib" data-subject="Matematika"><i class="fas fa-square-root-alt subject-icon"></i>Matematika</div>
                        <div class="subject-card wajib" data-subject="Bahasa Inggris"><i class="fas fa-language subject-icon"></i>Bahasa Inggris</div>
                        <div class="subject-card wajib" data-subject="Sejarah"><i class="fas fa-landmark subject-icon"></i>Sejarah</div>
                        <div class="subject-card wajib" data-subject="Pendidikan Jasmani"><i class="fas fa-running subject-icon"></i>Pendidikan Jasmani</div>
                        <div class="subject-card wajib" data-subject="Bahasa Sunda"><i class="fas fa-book subject-icon"></i>Bahasa Sunda</div>
                        <div class="subject-card wajib" data-subject="Seni Budaya - Musik"><i class="fas fa-music subject-icon"></i>Seni Budaya - Musik</div>
                        <div class="subject-card wajib" data-subject="Seni Budaya - Rupa"><i class="fas fa-paint-brush subject-icon"></i>Seni Budaya - Rupa</div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="subject-container">
                    <h4>Mata Pelajaran Pilihan (Maksimal 4)</h4>
                    <div id="pilihan" class="subjects-grid">
                        <!-- Mata pelajaran pilihan -->
                        <div class="subject-card pilihan" data-subject="Biologi"><i class="fas fa-seedling subject-icon"></i>Biologi</div>
                        <div class="subject-card pilihan" data-subject="Fisika"><i class="fas fa-atom subject-icon"></i>Fisika</div>
                        <div class="subject-card pilihan" data-subject="Kimia"><i class="fas fa-flask subject-icon"></i>Kimia</div>
                        <div class="subject-card pilihan" data-subject="Sosiologi"><i class="fas fa-users subject-icon"></i>Sosiologi</div>
                        <div class="subject-card pilihan" data-subject="Informatika"><i class="fas fa-laptop-code subject-icon"></i>Informatika</div>
                        <div class="subject-card pilihan" data-subject="Bahasa Inggris Tingkat Lanjut"><i class="fas fa-comments subject-icon"></i>Bahasa Inggris Tingkat Lanjut</div>
                        <div class="subject-card pilihan" data-subject="Matematika Tingkat Lanjut"><i class="fas fa-calculator subject-icon"></i>Matematika Tingkat Lanjut</div>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="btn-submit" id="submit-button" disabled>Kirim</button>
            </div>
            <input type="hidden" id="selected-subjects" name="selected_subjects">
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const pilihanCards = document.querySelectorAll(".subject-card.pilihan");
            const wajibCards = document.querySelectorAll(".subject-card.wajib");
            const submitButton = document.getElementById("submit-button");
            const selectedSubjectsInput = document.getElementById("selected-subjects");
            let selectedCount = 0;

            pilihanCards.forEach(card => {
                card.addEventListener("click", () => {
                    if (card.classList.contains("selected")) {
                        card.classList.remove("selected");
                        selectedCount--;
                    } else {
                        if (selectedCount < 4) {
                            card.classList.add("selected");
                            selectedCount++;
                        }
                    }
                    submitButton.disabled = selectedCount !== 4;
                });
            });

            wajibCards.forEach(card => {
                card.classList.add("selected");
            });

            const form = document.getElementById("subject-form");
            form.addEventListener("submit", (e) => {
                e.preventDefault();
                const selectedSubjects = document.querySelectorAll(".subject-card.selected");
                const selectedSubjectsData = Array.from(selectedSubjects).map(subject => subject.getAttribute("data-subject"));
                selectedSubjectsInput.value = JSON.stringify(selectedSubjectsData);
                
                fetch('save_subjects.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        username: "<?php echo $username; ?>",
        selectedSubjects: selectedSubjectsData,
    }),
})
    .then(response => response.json())
    .then(data => {
        console.log('Response dari server:', data); // Debugging log server response
        if (data.success) {
            alert('Data berhasil disimpan!');
            window.location.href = 'siswa_dashboard.php';
        } else {
            alert('Terjadi kesalahan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data.');
    });


            });
        });
    </script>
</body>
</html>