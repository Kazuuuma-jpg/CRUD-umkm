-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Nov 2025 pada 18.50
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_miesetan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `email`, `last_login`) VALUES
(1, 'admin@gmail.com', 'admin', 'Dewangga Nugroho Anwar', 'admin@gmail.com', '2025-11-11 14:20:08'),
(6, 'dewa@gmail.com', 'admin', 'Dewangga Nugroho Anwar', 'dewa@gmail.com', '2025-11-06 08:45:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_export`
--

CREATE TABLE `log_export` (
  `id_log` int(11) NOT NULL,
  `nama_admin` varchar(50) DEFAULT NULL,
  `bulan_laporan` varchar(7) DEFAULT NULL,
  `waktu_export` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_export`
--

INSERT INTO `log_export` (`id_log`, `nama_admin`, `bulan_laporan`, `waktu_export`) VALUES
(1, 'Admin', '2025-11', '2025-11-05 12:56:13'),
(2, 'Admin', '2025-11', '2025-11-05 12:57:13'),
(3, 'Admin', '2025-11', '2025-11-05 12:57:20'),
(4, 'Admin', '2025-11', '2025-11-05 13:00:13'),
(5, 'Admin', '2025-10', '2025-11-05 14:27:59'),
(6, 'Admin', '2025-07', '2025-11-05 14:36:50'),
(7, 'Admin', '2025-12', '2025-11-05 18:48:20'),
(8, 'Admin', '2025-11', '2025-11-06 15:35:27'),
(9, 'Admin', '2025-12', '2025-11-07 16:36:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(50) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `deskripsi`, `harga`, `kategori`, `stok`) VALUES
(1, 'ayam geprek sambal setan', '', 10000.00, 'Makanan', 5),
(2, 'mi iblis', '', 13000.00, 'Makanan', 3),
(4, 'air neraka', '', 3000.00, 'Umum', 0),
(6, 'Mie Api', '', 20000.00, 'Makanan', 4),
(7, 'Ikan Suir Neraka', '', 11000.00, 'Umum', 0),
(11, 'ayam', '', 2.00, 'Umum', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `nama_pembeli` varchar(100) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `status` enum('Lunas','Belum Lunas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `log_export`
--
ALTER TABLE `log_export`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `log_export`
--
ALTER TABLE `log_export`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
