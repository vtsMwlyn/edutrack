-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Feb 2025 pada 15.48
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edutrack`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `assignments`
--

CREATE TABLE `assignments` (
  `id_tugas` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `id_mapel` int(11) DEFAULT NULL,
  `nama_tugas` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_tenggat` date DEFAULT NULL,
  `status` varchar(12) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `assignments`
--

INSERT INTO `assignments` (`id_tugas`, `id_siswa`, `id_mapel`, `nama_tugas`, `deskripsi`, `tanggal_tenggat`, `status`, `file_name`) VALUES
(1, 1, 1, 'Tugas Aljabar', 'Mengerjakan soal-soal aljabar bab 1', '2024-12-01', 'Pending', NULL),
(2, 1, 1, 'Analisis Puisi', 'Menganalisis puisi karya Chairil Anwar', '2024-12-03', 'Pending', NULL),
(3, 1, 2, 'Laporan Percobaan Hukum Newton', 'Membuat laporan percobaan hukum Newton', '2024-12-05', 'Pending', NULL),
(4, 2, 1, 'Reaksi Kimia', 'Menuliskan reaksi kimia asam-basa', '2024-12-07', 'Pending', NULL),
(5, 2, 2, 'Penelitian Tumbuhan', 'Melakukan penelitian sederhana tentang tumbuhan', '2024-12-10', 'Pending', NULL),
(6, 2, 1, 'Menulis Cerita Pendek', 'Menulis cerita pendek bertema persahabatan', '2024-12-15', 'Pending', NULL),
(7, 2, 1, 'Geometri', 'Mengerjakan soal geometri bab 2', '2024-12-20', 'Pending', NULL),
(8, 1, 3, 'hehe', 'apa', '2200-02-21', NULL, NULL),
(9, 2, 3, 'hehe', 'apa', '2200-02-21', NULL, NULL),
(10, 1, 3, 'hehe', 'apa', '2200-02-21', NULL, NULL),
(11, 2, 3, 'hehe', 'apa', '2200-02-21', NULL, NULL),
(12, 1, 1, 'bikin agama baru', 'bikin minimal 2 agama', '2004-10-10', NULL, NULL),
(13, 2, 1, 'bikin agama baru', 'bikin minimal 2 agama', '2004-10-10', NULL, NULL),
(14, 1, 1, 'bikin agama baru', 'bikin minimal 2 agama', '2004-10-10', NULL, NULL),
(15, 2, 1, 'bikin agama baru', 'bikin minimal 2 agama', '2004-10-10', NULL, NULL),
(16, 1, 6, 'bikin sejarah baru cuy', 'coba bikin sejarah 100 tahun yang akan datang', '2020-11-11', NULL, NULL),
(17, 2, 6, 'bikin sejarah baru cuy', 'coba bikin sejarah 100 tahun yang akan datang', '2020-11-11', NULL, NULL),
(18, 1, 6, 'bikin sejarah baru cuy', 'coba bikin sejarah 100 tahun yang akan datang', '2020-11-11', NULL, NULL),
(19, 2, 6, 'bikin sejarah baru cuy', 'coba bikin sejarah 100 tahun yang akan datang', '2020-11-11', NULL, NULL),
(20, 1, 4, 'bikin rumus baru', '4 rumus', '2223-11-11', NULL, NULL),
(21, 1, 3, 'aaaa', 'aaaa', '5555-05-05', NULL, NULL),
(22, 2, 3, 'aaaa', 'aaaa', '5555-05-05', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`id`, `title`, `start`, `end`, `date`, `description`, `created_at`) VALUES
(43, 'hehe', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00', 'apapap', '2025-02-10 04:18:02'),
(44, 'hehe', '2025-02-01 00:00:00', '2025-02-01 11:11:00', '0000-00-00', 'apa', '2025-02-10 04:30:22'),
(45, 'apa', '2025-02-07 00:00:00', '2025-02-07 11:59:00', '0000-00-00', 'rawr', '2025-02-10 04:31:31'),
(46, 'apa', '2025-02-06 00:00:00', '2025-02-06 23:55:00', '0000-00-00', 'apa', '2025-02-10 04:36:44'),
(47, 'apa', '2025-02-05 00:00:00', '2025-02-05 11:02:00', '0000-00-00', 'apa', '2025-02-10 04:36:58'),
(48, 'rawr', '2025-02-04 00:00:00', '2025-02-04 03:32:00', '0000-00-00', '1111', '2025-02-10 04:40:12'),
(49, 'apa', '2025-02-05 00:00:00', '2025-02-05 11:11:00', '0000-00-00', 'apa', '2025-02-10 04:44:35'),
(50, 'aaaa', '2025-02-08 00:00:00', '2025-02-08 12:12:00', '0000-00-00', '12122', '2025-02-10 15:18:02'),
(51, 'aaaa', '2025-02-01 00:00:00', '2025-02-01 12:12:00', '0000-00-00', 'adasa', '2025-02-11 14:36:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `spesialisasi` varchar(100) DEFAULT NULL,
  `nomor_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_pengguna` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `nama_guru`, `spesialisasi`, `nomor_telepon`, `email`, `id_pengguna`) VALUES
(1, 'Vincent', 'Matematika', '08118608830', 'vincenttheo100@gmail.com', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_tugas`
--

CREATE TABLE `jadwal_tugas` (
  `id_jadwal` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `id_tugas` int(11) DEFAULT NULL,
  `tanggal_pengingat` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id_mapel` int(11) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kategori` enum('Wajib','Pilihan') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id_mapel`, `nama_mapel`, `kategori`) VALUES
(1, 'Pendidikan Agama dan Budi Pekerti', 'Wajib'),
(2, 'Pendidikan Pancasila', 'Wajib'),
(3, 'Bahasa Indonesia', 'Wajib'),
(4, 'Matematika', 'Wajib'),
(5, 'Bahasa Inggris', 'Wajib'),
(6, 'Sejarah', 'Wajib'),
(7, 'Pendidikan Jasmani', 'Wajib'),
(8, 'Bahasa Sunda', 'Wajib'),
(9, 'Seni Budaya - Musik', 'Wajib'),
(10, 'Seni Budaya - Rupa', 'Wajib'),
(11, 'Biologi', 'Pilihan'),
(12, 'Fisika', 'Pilihan'),
(13, 'Kimia', 'Pilihan'),
(14, 'Sosiologi', 'Pilihan'),
(15, 'Informatika', 'Pilihan'),
(16, 'Bahasa Inggris Tingkat Lanjut', 'Pilihan'),
(17, 'Matematika Tingkat Lanjut', 'Pilihan'),
(18, 'Ekonomi', 'Pilihan'),
(19, 'Geografi', 'Pilihan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_pelajaran_siswa`
--

CREATE TABLE `mata_pelajaran_siswa` (
  `id_siswa` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mata_pelajaran_siswa`
--

INSERT INTO `mata_pelajaran_siswa` (`id_siswa`, `id_mapel`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai_siswa`
--

CREATE TABLE `nilai_siswa` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `id_mapel` int(11) DEFAULT NULL,
  `nilai` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajaran`
--

CREATE TABLE `pengajaran` (
  `id_pengajaran` int(11) NOT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `id_mapel` int(11) DEFAULT NULL,
  `nama_mapel` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `tipe_pengguna` enum('siswa','guru','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `phone`, `tipe_pengguna`) VALUES
(1, 'Alexandria', 'aaa', '089501019440', 'siswa'),
(2, 'Vallen', 'awa', '0895361337375', 'siswa'),
(3, 'Vincent', 'a', '08118608830', 'guru');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `nomor_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `id_pengguna` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama_siswa`, `tanggal_lahir`, `alamat`, `nomor_telepon`, `email`, `kelas`, `id_pengguna`) VALUES
(1, 'Alexandria', '2007-05-02', 'Jl. Merdeka No. 1', '089501019440', 'vroteir@gmail.com', '12-1', 1),
(2, 'Vallen', '2006-02-12', 'Jl. Karya Bakti No. 2', '0895361337375', 'siapaaja@gmail.com', '12-1', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa_mapel`
--

CREATE TABLE `siswa_mapel` (
  `id_siswa` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` int(11) NOT NULL,
  `nama_tugas` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_tenggat` date DEFAULT NULL,
  `status` varchar(12) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `id_siswa` int(255) DEFAULT NULL,
  `id_mapel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `nama_tugas`, `deskripsi`, `tanggal_tenggat`, `status`, `file_name`, `id_siswa`, `id_mapel`) VALUES
(42, 'Physics Lab Report', 'Write a detailed report on the refraction of light.', '2024-12-10', 'Submitted', 'Screenshot 2023-05-24 201754.png', 0, NULL),
(43, 'Chemistry Experiment', 'Conduct an experiment on chemical reactions.', '2024-12-15', 'Submitted', 'Screenshot 2023-05-24 201754.png', 0, NULL),
(44, 'English Poem Analysis', 'Analyze the poem \"Ozymandias\" by Percy Shelley.', '2024-12-18', 'Submitted', 'Screenshot 2023-05-24 201754.png', 0, NULL),
(45, 'Geography Map Assignment', 'Create a map of tectonic plates.', '2024-12-22', 'Submitted', 'Screenshot 2023-06-07 223608.png', 0, NULL),
(46, 'Computer Programming Task', 'Write a Python program to solve quadratic equations.', '2024-12-25', 'Submitted', 'Screenshot 2023-06-07 223608.png', 0, NULL),
(47, 'Biology Research Paper', 'Write a detailed paper on photosynthesis.', '2024-12-28', 'pending', NULL, NULL, NULL),
(48, 'Mathematics Geometry Task', 'Solve the geometry worksheet on circles and tangents.', '2024-12-30', 'Submitted', 'Screenshot 2024-11-17 223546.png', 0, NULL),
(49, 'History Essay', 'Write an essay on the causes of World War II.', '2025-01-05', 'Submitted', 'Screenshot 2023-06-07 223315.png', 0, NULL),
(50, 'Computer Programming Project', 'Create a Python program to automate a simple task.', '2025-01-10', 'pending', NULL, NULL, NULL),
(51, 'Economics Case Study', 'Analyze a case study on market failure.', '2025-01-15', 'pending', NULL, NULL, NULL),
(52, 'miau', 'TUGAS MEMBUAT PLANET BARU', '2024-11-30', 'pending', NULL, 1, 19),
(53, 'membuat bumi', 'coba bikin sekalian sama bulannya', '2024-11-25', 'pending', NULL, 2, 19),
(54, 'rawr', 'miau miau', '2024-11-25', 'pending', NULL, NULL, NULL),
(55, 'a', 'a', '2024-11-24', 'pending', NULL, NULL, NULL),
(56, 'aaaa', 'aaaaaaa', '2024-11-29', 'Submitted', 'aa.jpg', 0, NULL),
(57, 'miau', 'miau', '2025-01-22', 'pending', NULL, 0, NULL),
(58, 'miauaaaaaaaaaaa', 'aaaaaaaaaaaa', '2025-01-23', 'Submitted', NULL, 1, 2),
(59, 'aaa', '1qaa', '1111-11-11', 'pending', NULL, 2, 19),
(60, 'aaa', '1qaa', '1111-11-11', 'pending', NULL, 2, 19),
(61, 'aaa', '1qaa', '1111-11-11', 'pending', NULL, 2, 19),
(62, 'aaa', '1qaa', '1111-11-11', 'pending', NULL, 2, 19),
(63, 'aaa', '1qaa', '1111-11-11', 'pending', NULL, 2, 19),
(64, 'aaa', '1qaa', '1111-11-11', 'pending', NULL, 2, 19),
(65, 'aaa', '1qaa', '1111-11-11', 'pending', NULL, 2, 19),
(66, 'aaa', '1qaa', '1111-11-11', 'pending', NULL, 2, 19),
(67, 'hehe', 'apa', '2200-02-21', 'pending', NULL, 0, 0),
(68, 'hehe', 'apa', '2200-02-21', 'pending', NULL, 0, 0),
(69, 'bikin agama baru', 'bikin minimal 2 agama', '2004-10-10', 'pending', NULL, 0, 0),
(70, 'bikin agama baru', 'bikin minimal 2 agama', '2004-10-10', 'pending', NULL, 0, 0),
(71, 'bikin sejarah baru cuy', 'coba bikin sejarah 100 tahun yang akan datang', '2020-11-11', 'pending', NULL, 0, 0),
(72, 'bikin sejarah baru cuy', 'coba bikin sejarah 100 tahun yang akan datang', '2020-11-11', 'pending', NULL, 0, 0),
(73, 'bikin rumus baru', '4 rumus', '2223-11-11', 'pending', NULL, 0, 0),
(74, 'aaaa', 'aaaa', '5555-05-05', 'pending', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `phone`, `password`) VALUES
(1, '08118608830', '$2y$10$.pxGptEBVF8pgqXUEwspSu3a8HmcUKCPw7a09dr/nK.rAbhKOjjcq');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id_tugas`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indeks untuk tabel `jadwal_tugas`
--
ALTER TABLE `jadwal_tugas`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_tugas` (`id_tugas`);

--
-- Indeks untuk tabel `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indeks untuk tabel `mata_pelajaran_siswa`
--
ALTER TABLE `mata_pelajaran_siswa`
  ADD PRIMARY KEY (`id_siswa`,`id_mapel`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indeks untuk tabel `nilai_siswa`
--
ALTER TABLE `nilai_siswa`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indeks untuk tabel `pengajaran`
--
ALTER TABLE `pengajaran`
  ADD PRIMARY KEY (`id_pengajaran`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indeks untuk tabel `siswa_mapel`
--
ALTER TABLE `siswa_mapel`
  ADD PRIMARY KEY (`id_siswa`,`id_mapel`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indeks untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id_tugas`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jadwal_tugas`
--
ALTER TABLE `jadwal_tugas`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `nilai_siswa`
--
ALTER TABLE `nilai_siswa`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengajaran`
--
ALTER TABLE `pengajaran`
  MODIFY `id_pengajaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`);

--
-- Ketidakleluasaan untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`);

--
-- Ketidakleluasaan untuk tabel `jadwal_tugas`
--
ALTER TABLE `jadwal_tugas`
  ADD CONSTRAINT `jadwal_tugas_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `jadwal_tugas_ibfk_2` FOREIGN KEY (`id_tugas`) REFERENCES `tugas` (`id_tugas`);

--
-- Ketidakleluasaan untuk tabel `mata_pelajaran_siswa`
--
ALTER TABLE `mata_pelajaran_siswa`
  ADD CONSTRAINT `mata_pelajaran_siswa_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `mata_pelajaran_siswa_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilai_siswa`
--
ALTER TABLE `nilai_siswa`
  ADD CONSTRAINT `nilai_siswa_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `nilai_siswa_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`);

--
-- Ketidakleluasaan untuk tabel `pengajaran`
--
ALTER TABLE `pengajaran`
  ADD CONSTRAINT `pengajaran_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `pengajaran_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`);

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`);

--
-- Ketidakleluasaan untuk tabel `siswa_mapel`
--
ALTER TABLE `siswa_mapel`
  ADD CONSTRAINT `siswa_mapel_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_mapel_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
