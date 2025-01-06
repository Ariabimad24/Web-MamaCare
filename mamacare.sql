-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2025 at 11:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mamacare`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `noHP` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `email`, `password`, `noHP`) VALUES
(1, 'admin', 'admin@gmail.com', 'gecko123', '087656453746');

-- --------------------------------------------------------

--
-- Table structure for table `anak`
--

CREATE TABLE `anak` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) NOT NULL,
  `umur` int(11) DEFAULT NULL,
  `ibu_muda_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artikel_kesehatan`
--

CREATE TABLE `artikel_kesehatan` (
  `id` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `isi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `highlight` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `catatan_kesehatan`
--

CREATE TABLE `catatan_kesehatan` (
  `id` int(11) NOT NULL,
  `namaAnak` varchar(45) DEFAULT NULL,
  `height` float DEFAULT NULL,
  `bb` float DEFAULT NULL,
  `waktuCatatan` datetime DEFAULT NULL,
  `usia` int(11) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `z_score` float DEFAULT NULL,
  `headCircumference` float DEFAULT NULL,
  `ibu_muda_id` int(11) DEFAULT NULL,
  `z_score_weight` double DEFAULT NULL,
  `z_score_head` double DEFAULT NULL,
  `status_height` varchar(50) DEFAULT NULL,
  `status_weight` varchar(50) DEFAULT NULL,
  `status_head` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ibu_muda`
--

CREATE TABLE `ibu_muda` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `alamat` text DEFAULT NULL,
  `statusKecemasan` varchar(45) DEFAULT NULL,
  `noHP` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `photo_profile` varchar(255) DEFAULT NULL,
  `nama_anak_id` int(11) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modul_kesehatan`
--

CREATE TABLE `modul_kesehatan` (
  `id` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `isi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `highlight` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nakes`
--

CREATE TABLE `nakes` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `noHP` varchar(45) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `spesialisasi` varchar(45) DEFAULT NULL,
  `sertifikat_kedokteran` varchar(255) DEFAULT NULL,
  `kualifikasi_tenaga_kesehatan` varchar(255) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `photo_profile` varchar(255) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengingat_jadwal_kesehatan`
--

CREATE TABLE `pengingat_jadwal_kesehatan` (
  `id` int(11) NOT NULL,
  `namaAnak` varchar(100) NOT NULL,
  `waktuPengingat` datetime DEFAULT NULL,
  `instruksi_arahan_dokter` varchar(255) DEFAULT NULL,
  `ibu_muda_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `notifikasi_ditampilkan` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `anak`
--
ALTER TABLE `anak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ibu_muda_id` (`ibu_muda_id`);

--
-- Indexes for table `artikel_kesehatan`
--
ALTER TABLE `artikel_kesehatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `catatan_kesehatan`
--
ALTER TABLE `catatan_kesehatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ibu_muda` (`ibu_muda_id`);

--
-- Indexes for table `ibu_muda`
--
ALTER TABLE `ibu_muda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nama_anak` (`nama_anak_id`);

--
-- Indexes for table `modul_kesehatan`
--
ALTER TABLE `modul_kesehatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nakes`
--
ALTER TABLE `nakes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengingat_jadwal_kesehatan`
--
ALTER TABLE `pengingat_jadwal_kesehatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ibu_muda_id` (`ibu_muda_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `anak`
--
ALTER TABLE `anak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `artikel_kesehatan`
--
ALTER TABLE `artikel_kesehatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `catatan_kesehatan`
--
ALTER TABLE `catatan_kesehatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ibu_muda`
--
ALTER TABLE `ibu_muda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `modul_kesehatan`
--
ALTER TABLE `modul_kesehatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `nakes`
--
ALTER TABLE `nakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pengingat_jadwal_kesehatan`
--
ALTER TABLE `pengingat_jadwal_kesehatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anak`
--
ALTER TABLE `anak`
  ADD CONSTRAINT `anak_ibfk_1` FOREIGN KEY (`ibu_muda_id`) REFERENCES `ibu_muda` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `catatan_kesehatan`
--
ALTER TABLE `catatan_kesehatan`
  ADD CONSTRAINT `fk_ibu_muda` FOREIGN KEY (`ibu_muda_id`) REFERENCES `ibu_muda` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ibu_muda`
--
ALTER TABLE `ibu_muda`
  ADD CONSTRAINT `fk_nama_anak` FOREIGN KEY (`nama_anak_id`) REFERENCES `anak` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pengingat_jadwal_kesehatan`
--
ALTER TABLE `pengingat_jadwal_kesehatan`
  ADD CONSTRAINT `pengingat_jadwal_kesehatan_ibfk_1` FOREIGN KEY (`ibu_muda_id`) REFERENCES `ibu_muda` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
