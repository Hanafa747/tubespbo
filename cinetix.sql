-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2026 at 09:58 AM
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
-- Database: `cinetix`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_dibuat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `username`, `password`, `tanggal_dibuat`) VALUES
(1, 'Administrator', 'admin', '$2y$10$yyRsSElnGLKcufVlIl4aU.iAYejRQ8H681Mve.9dDRs86B2gtB0bW', 0);

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id_film` int(11) NOT NULL,
  `sampul` varchar(255) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `sutradara` varchar(100) NOT NULL,
  `sinopsis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id_film`, `sampul`, `judul`, `genre`, `sutradara`, `sinopsis`) VALUES
(1, 'uploads/poster/dc1a8c01afceb8bc3c77057c.png', 'World War III - The Final Conflict', 'War', 'Hanafa', 'Perang Dunia ke 3');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_tayang`
--

CREATE TABLE `jadwal_tayang` (
  `id_jadwal` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  `studio` varchar(50) NOT NULL,
  `tanggal_tayang` date NOT NULL,
  `jam_tayang` time NOT NULL,
  `harga_tiket` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_tayang`
--

INSERT INTO `jadwal_tayang` (`id_jadwal`, `id_film`, `studio`, `tanggal_tayang`, `jam_tayang`, `harga_tiket`) VALUES
(1, 1, 'Marvel', '2026-11-14', '10:00:00', 35000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `tanggal_pemesanan` datetime NOT NULL,
  `jumlah_tiket` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'menunggu pembayaran'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_user`, `id_film`, `id_jadwal`, `tanggal_pemesanan`, `jumlah_tiket`, `total_harga`, `status`) VALUES
(1, 3, 1, 1, '2026-11-14 09:08:41', 2, 70000.00, 'selesai'),
(2, 3, 1, 1, '2026-11-14 09:08:57', 1, 35000.00, 'selesai'),
(3, 3, 1, 1, '2026-11-14 09:20:51', 1, 35000.00, 'selesai'),
(4, 3, 1, 1, '2026-11-14 09:39:52', 1, 35000.00, 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `email`, `no_hp`, `alamat`, `username`, `password`) VALUES
(1, 'Hafizh', 'hafizh123@gmail.com', '0812345678910', 'UNIB', 'hafizh', '$2y$10$X52vSIqkJ8rRxzyWwmM3Vuto3yxqg5w1llUinZrunGjbbzzWB0NCW'),
(2, 'hapis', 'hapis@gmail.com', '085369766769', 'unib', '', ''),
(3, 'hazim', 'hazim@gmail.com', '08347284723', 'mana', 'hazim', '$2y$10$XViOcjHktD2pHE36dj4/3.xxSyBreujxCBufBG58/XF.Bdo8LLkiy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id_film`);

--
-- Indexes for table `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `fk_jadwal_film` (`id_film`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `fk_pemesanan_user` (`id_user`),
  ADD KEY `fk_pemesanan_film` (`id_film`),
  ADD KEY `fk_pemesanan_jadwal` (`id_jadwal`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `uq_user_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id_film` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  ADD CONSTRAINT `fk_jadwal_film` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`) ON UPDATE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `fk_pemesanan_film` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pemesanan_jadwal` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_tayang` (`id_jadwal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pemesanan_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
