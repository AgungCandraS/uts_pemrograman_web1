-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Nov 2025 pada 16.52
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uts_pemweb1`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `informasi`
--

CREATE TABLE `informasi` (
  `id` int(11) UNSIGNED NOT NULL,
  `judul` varchar(150) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `ringkasan` text DEFAULT NULL,
  `isi` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `informasi`
--

INSERT INTO `informasi` (`id`, `judul`, `slug`, `kategori`, `gambar`, `ringkasan`, `isi`, `created_at`, `updated_at`) VALUES
(1, 'Profil Saputra Rajoet', 'profil-saputra-rajoet', 'Profil', 'logo.png', 'Saputra Rajoet adalah brand fashion rajut lokal ...', 'Saputra Rajoet adalah brand fashion rajut lokal yang fokus pada kualitas bahan, kenyamanan, dan desain yang timeless. Kami memproduksi cardigan, vest, dan knitwear lainnya dengan sentuhan handmade dan produksi rumahan yang rapi.', '2025-11-21 20:45:45', '2025-11-22 00:35:14'),
(2, 'Keunggulan Produk Rajut Kami', 'keunggulan-produk-rajut', 'Info', 'belle01.jpg', 'Keunggulan rajut kami adalah bahan halus, tidak panas, dan cocok untuk daily wear.', 'Kami menggunakan benang pilihan yang lembut di kulit, tidak mudah berbulu, serta melalui proses quality control sebelum dikirim ke pelanggan.', '2025-11-21 20:45:45', '2025-11-22 00:35:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak`
--

CREATE TABLE `kontak` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subjek` varchar(150) DEFAULT NULL,
  `pesan` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kontak`
--

INSERT INTO `kontak` (`id`, `nama`, `email`, `subjek`, `pesan`, `created_at`) VALUES
(1, 'agung', 'agungcandra655@gmail.com', 'Pemesanan Produk: Cardigan Belle Knit', 'Halo Saputra Rajoet,\r\n\r\nSaya ingin memesan produk berikut:\r\n- Nama produk : Cardigan Belle Knit\r\n- Kode produk : SR-BELLE\r\n- Harga       : Rp 185.000\r\n\r\nMohon informasikan ketersediaan stok, varian warna (jika ada), dan cara pembayaran.\r\n\r\nTerima kasih.', '2025-11-23 22:03:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `kode_produk` varchar(50) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar_utama` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `slug`, `kode_produk`, `kategori`, `harga`, `stok`, `gambar_utama`, `deskripsi`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cardigan Belle Knit', 'cardigan-belle-knit', 'SR-BELLE', 'Cardigan', 185000.00, 10, 'belle01.jpeg', 'Cardigan rajut dengan tekstur halus, cocok untuk daily look.', 'aktif', '2025-11-21 20:46:10', '2025-11-22 00:33:23'),
(2, 'Rompi Vest Basic', 'rompi-vest-basic', 'SR-VEST01', 'Vest', 135000.00, 15, 'Andin.png', 'Rompi vest basic yang mudah dipadukan dengan kemeja atau kaos.', 'aktif', '2025-11-21 20:46:10', '2025-11-22 00:33:09'),
(3, 'Kulot Rajut', NULL, NULL, 'Celana', 120000.00, 20, 'Kulot.jpeg', 'Celana kulot rajut premium, adem, nyaman dipakai harian.', 'aktif', '2025-11-23 22:37:14', '2025-11-23 22:37:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'agung', 'candraagung627@gmail.com', '$2y$10$ax7.fIvhxmfOTmA6FjQa0OSpgOPwP1i3kdfSpkBLk7HFusM0rF.eW', 'admin', '2025-11-21 23:25:28'),
(2, 'candra', 'agungcandra655@gmail.com', '$2y$10$7ci29YYGdT5W5iRUOTA4DeJUIMIc2JP5pGpEEUpOJx3HQs.GFUcnW', 'user', '2025-11-23 19:40:01');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `informasi`
--
ALTER TABLE `informasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `informasi`
--
ALTER TABLE `informasi`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
