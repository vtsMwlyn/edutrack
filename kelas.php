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


// Query untuk mengambil mata pelajaran wajib dan pilihan siswa
$queryMapel = "
    SELECT mp.id_mapel, mp.nama_mapel, mp.kategori 
    FROM mata_pelajaran mp
    LEFT JOIN mata_pelajaran_siswa mps ON mp.id_mapel = mps.id_mapel AND mps.id_siswa = ?
    WHERE mp.kategori IN ('wajib', 'pilihan') OR mps.id_siswa = ?
";

$stmtMapel = $conn->prepare($queryMapel);
$stmtMapel->bind_param("ii", $idSiswa, $idSiswa);
$stmtMapel->execute();
$resultMapel = $stmtMapel->get_result();

if (!$resultMapel) {
    die("Query gagal dijalankan: " . $conn->error);
}

// Ambil tugas berdasarkan mapel
$tugasPerMapel = [];
$queryTugas = "
    SELECT t.*, t.id_mapel 
    FROM tugas t
    JOIN mata_pelajaran mp ON t.id_mapel = mp.id_mapel
    WHERE mp.kategori IN ('wajib', 'pilihan') 
        OR t.id_mapel IN (SELECT id_mapel FROM mata_pelajaran_siswa WHERE id_siswa = ?)
";

$stmtTugas = $conn->prepare($queryTugas);
$stmtTugas->bind_param("i", $idSiswa);
$stmtTugas->execute();
$resultTugas = $stmtTugas->get_result();

if ($resultTugas) {
    while ($tugas = $resultTugas->fetch_assoc()) {
        $tugasPerMapel[$tugas['id_mapel']][] = $tugas;
    }
}
?>
<?php
// Fungsi untuk menentukan ikon berdasarkan mata pelajaran
function getMapelIcon($namaMapel) {
    $mapelIcons = [
        'Matematika' => 'fas fa-square-root-alt',
        'Fisika' => 'fas fa-atom',
        'Kimia' => 'fas fa-flask',
        'Biologi' => 'fas fa-dna',
        'Bahasa Indonesia' => 'fas fa-book',
        'Bahasa Inggris' => 'fas fa-language',
        'Sejarah' => 'fas fa-landmark',
        'Geografi' => 'fas fa-globe',
        'Ekonomi' => 'fas fa-coins',
        'Seni Budaya' => 'fas fa-palette',
        'Pendidikan Jasmani' => 'fas fa-running',
        'Informatika' => 'fas fa-laptop-code',
    ];

    // Default ikon jika mata pelajaran tidak terdaftar
    return $mapelIcons[$namaMapel] ?? 'fas fa-book-open';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mata Pelajaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Keep existing styles for sidebar and other elements */
        
        /* New styles for main container */
        .main-content {
            flex: 1;
            padding: 2rem;
            background-color: #f4f6f8;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            padding: 2rem;
            border-radius: 1rem;
            color: white;
            margin-bottom: 2rem;
        }

        .dashboard-header h3 {
            font-size: 2rem;
            margin: 0 0 0.5rem 0;
            color: white;
        }

        .controls-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: white;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        .mapel-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 0;
        }

        .mapel-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 0;
            margin: 0;
        }

        .mapel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: #f8fafc;
        }

        .mapel-icon {
            font-size: 2rem;
            color: #4f46e5;
            margin: 0;
        }

        .card-content {
            padding: 1.5rem;
        }

        .mapel-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
            color: #1f2937;
        }

        .mapel-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #f3f4f6;
            border-radius: 1rem;
            font-size: 0.875rem;
            color: #4b5563;
            margin: 0;
        }

        .reminder {
            background: #f8fafc;
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 1rem 0;
            border: 1px solid #e5e7eb;
        }

        .reminder-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .reminder-title {
            color: #374151;
            font-weight: 500;
        }

        .deadline {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0.5rem 0;
        }

        .progress-container {
            height: 0.5rem;
            background: #e5e7eb;
            border-radius: 0.25rem;
            overflow: hidden;
            margin-top: 0.75rem;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            width: 60%;
            transition: width 0.3s ease;
        }

        .card-actions {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .mapel-card-link {
            text-decoration: none;
            color: inherit;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .controls-section {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: none;
            }

            .filter-buttons {
                overflow-x: auto;
                padding-bottom: 0.5rem;
            }

            .mapel-cards {
                grid-template-columns: 1fr;
            }
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
                    <li><a href="siswa_dashboard.php"><i class="fas fa-home"></i></a></li>
                    <li><a href="calender.php"><i class="fas fa-calendar-alt"></i></a></li>
                    <li><a href="mapel.php"><i class="fas fa-user"></i></a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            

                <!-- New Controls Section -->
                <div class="controls-section">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari mata pelajaran...">
                    </div>
                    <div class="filter-buttons">
                        <button class="filter-btn active">Semua</button>
                        <button class="filter-btn">Mata Pelajaran Wajib</button>
                        <button class="filter-btn">Peminatan</button>
                    </div>
                </div>

                <div class="mapel-cards">
    <div class="mapel-cards">
        <?php while ($mapel = $resultMapel->fetch_assoc()) : ?>
            <?php
                // Set the appropriate URL for each subject based on its ID
                $link = "kelas.php?id_mapel=" . $mapel['id_mapel'];
                switch ($mapel['id_mapel']) {
                    case 1:
                        $link = "kelas-agama.php";
                        break;
                    case 2:
                        $link = "kelas-pkn.php";
                        break;
                    case 3:
                        $link = "kelas-indo.php";
                        break;
                    case 4:
                        $link = "kelas-mat.php";
                        break;
                    case 5:
                        $link = "kelas-inggris.php";
                        break;
                    case 6:
                        $link = "kelas-sejarah.php";
                        break;
                    case 7:
                        $link = "kelas-or.php";
                        break;
                    case 8:
                        $link = "kelas-sunda.php";
                        break;
                    case 9:
                        $link = "kelas-musik.php";
                        break;
                    case 10:
                        $link = "kelas-rupa.php";
                        break;
                    case 11:
                        $link = "kelas-bio.php";
                        break;
                    case 12:
                        $link = "kelas-fisika.php";
                        break;
                    case 13:
                        $link = "kelas-kimia.php";
                        break;
                    case 14:
                        $link = "kelas-sosio.php";
                        break;
                    case 15:
                        $link = "kelas-tik.php";
                        break;
                    case 16:
                        $link = "kelas-bting.php";
                        break;
                    case 17:
                        $link = "kelas-mating.php";
                        break;
                    case 18:
                        $link = "kelas-eko.php";
                        break;
                    case 19:
                        $link = "kelas-geo.php";
                        break;
                    default:
                        $link = "kelas.php?id_mapel=" . $mapel['id_mapel'];
                        break;
                }
            ?>
         <a href="<?= $link ?>" class="mapel-card-link">
                            <div class="mapel-card" data-mapel-id="<?= $mapel['id_mapel'] ?>">
                                <div class="card-header">
                                    <div class="mapel-icon">
                                        <i class="<?= getMapelIcon($mapel['nama_mapel']) ?>"></i>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <h4 class="mapel-title"><?= htmlspecialchars($mapel['nama_mapel']) ?></h4>
                                    <span class="mapel-category"><?= htmlspecialchars($mapel['kategori']) ?></span>

                                    <?php if (isset($tugasPerMapel[$mapel['id_mapel']])) : ?>
                                        <?php
                                        $reminders = array_filter($tugasPerMapel[$mapel['id_mapel']], function ($tugas) {
                                            return strtolower($tugas['status']) !== 'submitted';
                                        });
                                        ?>
                                        <?php if (!empty($reminders)) : ?>
                                            <?php foreach ($reminders as $tugas) : ?>
                                                <div class="reminder">
                                                    <div class="reminder-header">
                                                        <div class="reminder-title"><?= htmlspecialchars($tugas['nama_tugas']) ?></div>
                                                    </div>
                                                    <div class="deadline">
                                                        <i class="fas fa-clock"></i>
                                                        <span>Deadline: <?= htmlspecialchars($tugas['tanggal_tenggat']) ?></span>
                                                    </div>
                                                    <div class="progress-container">
                                                        <div class="progress-bar"></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <div class="reminder">Tidak ada tugas yang belum di-submit.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
</div>
</div>
        </main>

        <!-- Profile Sidebar -->
        <div id="sidebar" class="profile-sidebar">
                            <div class="profile-overview">
                                <img src="https://cdn.discordapp.com/attachments/749553877333835846/1305882693116100608/3778bb3bb63024da3c52aa0f47fdd603.png?ex=6734a588&is=67335408&hm=5b951564b1a1246b3decb6dfdceafbde2e16ef24ae1484c09e81bb268096d39b&" alt="Profile Picture" class="profile-pic">
                                <h2><?php echo htmlspecialchars($id_siswa);  ?>!</h2>
                            </div>
                            <div class="profile-stats">
                                <div>Student</div>
                                <div>Unit: Highschool</div>
                            </div>
                            <button class="logout-button" onclick="window.location.href='logout.php'">
                                <i class="fas fa-sign-out-alt"></i> 
                            </button>
                        </div>

                        <!-- Toggle Button -->
                        <div class="right-container">
                            <button id="toggle-btn" class="sidebar-toggle">
                                <i class="fas fa-user-circle"></i>
                            </button>
                        </div>
    </div>
</div>

<!-- Keep existing scripts -->
<script>
    function openClass(idMapel) {
        window.location.href = kelas.php?id_mapel=${idMapel};
    }

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
            popup.style.top = ${rect.top + window.scrollY + rect.height}px;
            popup.style.left = ${rect.left + window.scrollX + rect.width / 2}px;
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
        const searchInput = document.querySelector('.search-box input');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const mapelCards = document.querySelectorAll('.mapel-card');

        // Fungsi untuk memfilter mata pelajaran berdasarkan teks pencarian
        function filterMapel() {
            const searchText = searchInput.value.toLowerCase();
            const activeFilter = document.querySelector('.filter-btn.active').innerText.toLowerCase();

            mapelCards.forEach(card => {
                const title = card.querySelector('.mapel-title').innerText.toLowerCase();
                const category = card.querySelector('.mapel-category').innerText.toLowerCase();

                let matchesSearch = title.includes(searchText);
                let matchesFilter = activeFilter === 'semua' || category.includes(activeFilter);

                if (matchesSearch && matchesFilter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Event listener untuk input pencarian
        searchInput.addEventListener('input', filterMapel);

        // Event listener untuk tombol filter
        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Hapus class 'active' dari semua tombol
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Tambahkan class 'active' ke tombol yang diklik
                this.classList.add('active');
                // Jalankan fungsi filter
                filterMapel();
            });
        });
    });
</script>

</body>
</html>