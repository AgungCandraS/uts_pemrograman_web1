<?php
// public/dashboard.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$conn  = getConnection();
$role  = $_SESSION['user_role'] ?? 'user';
$nama  = $_SESSION['user_name'] ?? 'Pengguna';

// hitung data dasar
$produkCount = $conn->query("SELECT COUNT(*) AS jml FROM produk")->fetch_assoc()['jml'] ?? 0;
$infoCount   = $conn->query("SELECT COUNT(*) AS jml FROM informasi")->fetch_assoc()['jml'] ?? 0;
$pesanCount  = $conn->query("SELECT COUNT(*) AS jml FROM kontak")->fetch_assoc()['jml'] ?? 0;

// produk terbaru
$produkRes = $conn->query("SELECT * FROM produk ORDER BY created_at DESC LIMIT 5");

$pageTitle = 'Dashboard';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main class="py-5">
    <div class="container">

        <!-- HEADER DASHBOARD -->
        <div class="mb-4">
            <span class="hero-kicker d-inline-block mb-2">
                Dashboard <?= $role === 'admin' ? 'Admin' : 'Pengguna' ?>
            </span>

            <h1 class="hero-title mb-2" style="font-size: 1.8rem;">
                Hai, <?= htmlspecialchars($nama) ?> 👋
            </h1>

            <p class="text-soft mb-0">
                <?= $role === 'admin'
                    ? 'Ini adalah area kontrol untuk memantau aktivitas website Saputra Rajoet, termasuk produk, informasi, dan pesan/pesanan yang masuk.'
                    : 'Selamat datang di dashboard kamu. Dari sini kamu bisa menjelajahi katalog produk, mengenal brand Saputra Rajoet, dan menghubungi kami dengan mudah.' ?>
            </p>
        </div>

        <!-- KARTU RINGKASAN STATISTIK -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card card-glass h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-4 text-white"><?= (int)$produkCount ?></div>
                        <div class="text-soft small">Total Produk Terdaftar</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-glass h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-4 text-white"><?= (int)$infoCount ?></div>
                        <div class="text-soft small">Total Informasi / Konten Brand</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-glass h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-4 text-white"><?= (int)$pesanCount ?></div>
                        <div class="text-soft small">Pesan & Pesanan via Website</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($role === 'admin'): ?>
            <!-- ====================== DASHBOARD ADMIN ====================== -->
            <div class="row g-3">
                <!-- Panel menu admin & branding -->
                <div class="col-md-6">
                    <div class="card card-glass h-100">
                        <div class="card-body">
                            <h2 class="h6 text-white mb-2">Panel Admin Saputra Rajoet</h2>
                            <p class="text-soft small mb-3">
                                Sebagai admin, kamu dapat menggunakan dashboard ini untuk memantau aktivitas website
                                dan memahami bagaimana brand Saputra Rajoet berinteraksi dengan pengunjung.
                            </p>

                            <ul class="text-soft small mb-3">
                                <li>Memantau <strong>pesan & pesanan</strong> yang masuk dari form kontak / pemesanan.</li>
                                <li>Melihat ringkasan jumlah <strong>produk</strong> dan <strong>informasi brand</strong>.</li>
                                <li>(Opsional ke depan) Menambah / mengedit produk dan konten informasi.</li>
                            </ul>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?= BASE_URL ?>dashboard_pesanan.php"
                                   class="btn btn-primary btn-sm pill-btn">
                                    Lihat Pesanan / Pesan Masuk
                                </a>
                                <a href="<?= BASE_URL ?>products.php"
                                   class="btn btn-outline-light btn-sm pill-btn">
                                    Lihat Katalog di Website
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel produk terbaru -->
                <div class="col-md-6">
                    <div class="card card-glass h-100">
                        <div class="card-body">
                            <h2 class="h6 text-white mb-2">Produk Terbaru</h2>
                            <p class="text-soft small mb-3">
                                Ringkasan beberapa produk terakhir yang ditambahkan ke sistem.
                            </p>

                            <?php if ($produkRes && $produkRes->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Kategori</th>
                                                <th>Harga</th>
                                                <th>Stok</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($p = $produkRes->fetch_assoc()): ?>
                                            <tr>
                                                <td class="small">
                                                    <?= htmlspecialchars($p['nama_produk']) ?>
                                                </td>
                                                <td class="small">
                                                    <?= htmlspecialchars($p['kategori'] ?? '-') ?>
                                                </td>
                                                <td class="small">
                                                    Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                                </td>
                                                <td class="small">
                                                    <?= (int)$p['stok'] ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-soft small mb-0">Belum ada produk yang terdaftar.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- ====================== DASHBOARD USER / PELANGGAN ====================== -->
            <div class="row g-3">
                <!-- Kartu utama experience user -->
                <div class="col-lg-7">
                    <div class="card card-glass h-100">
                        <div class="card-body">
                            <h2 class="h6 text-white mb-2">Selamat datang di Saputra Rajoet Dashboard</h2>
                            <p class="text-soft small mb-3">
                                Dari dashboard ini, kamu bisa menjelajahi produk knitwear, mengenal brand, dan
                                menghubungi kami dengan lebih mudah. Berikut beberapa langkah yang bisa kamu lakukan:
                            </p>

                            <ul class="text-soft small mb-3">
                                <li>Lihat semua koleksi cardigan, vest, dan knitwear di halaman <strong>Katalog Produk</strong>.</li>
                                <li>Baca cerita dan profil brand Saputra Rajoet di halaman <strong>Tentang Kami</strong>.</li>
                                <li>Gunakan halaman <strong>Kontak</strong> untuk bertanya, konsultasi ukuran, atau pemesanan khusus.</li>
                            </ul>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?= BASE_URL ?>products.php"
                                   class="btn btn-primary pill-btn btn-sm">
                                    Lihat Katalog Produk
                                </a>
                                <a href="<?= BASE_URL ?>about.php"
                                   class="btn btn-outline-light pill-btn btn-sm">
                                    Tentang Saputra Rajoet
                                </a>
                                <a href="<?= BASE_URL ?>contact.php"
                                   class="btn btn-outline-light pill-btn btn-sm">
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu shortcut & branding kecil -->
                <div class="col-lg-5">
                    <div class="card card-glass mb-3">
                        <div class="card-body">
                            <h2 class="h6 text-white mb-2">Akses Cepat</h2>
                            <p class="text-soft small mb-3">
                                Beberapa menu yang sering digunakan oleh pengunjung:
                            </p>
                            <ul class="text-soft small mb-0">
                                <li><a href="<?= BASE_URL ?>products.php">Katalog Produk</a> — lihat semua koleksi.</li>
                                <li><a href="<?= BASE_URL ?>contact.php">Form Kontak</a> — tanya stok / ukuran / warna.</li>
                                <li><a href="<?= BASE_URL ?>index.php#hero">Beranda</a> — kembali ke halaman utama brand.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card card-glass">
                        <div class="card-body">
                            <h2 class="h6 text-white mb-2">Brand Highlight</h2>
                            <p class="text-soft small mb-2">
                                Saputra Rajoet berfokus pada knitwear yang nyaman dan mudah dipadupadankan
                                untuk aktivitas sehari-hari, mulai dari kuliah hingga hangout santai.
                            </p>
                            <p class="text-soft small mb-0">
                                Terima kasih sudah menjadi bagian dari perjalanan Saputra Rajoet. Semoga kamu
                                menemukan knitwear favoritmu di sini. 💛
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
