<?php
// public/index.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';



$conn = getConnection();

// ambil 6 produk aktif terbaru
$produkRes = $conn->query("
    SELECT * 
    FROM produk 
    WHERE status = 'aktif' 
    ORDER BY created_at DESC 
    LIMIT 6
");

// ambil 3 informasi terbaru
$infoRes = $conn->query("
    SELECT * 
    FROM informasi 
    ORDER BY created_at DESC 
    LIMIT 3
");

$pageTitle = 'Beranda';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                <!-- Hero Text -->
                <div class="col-lg-6">
                    <div class="hero-kicker mb-3">
                        Saputra Rajoet • Official Brand
                    </div>

                    <h1 class="hero-title mb-3">
                        Knitwear nyaman,<br>
                        <span>gaya kamu, karaktermu.</span>
                    </h1>

                    <p class="hero-subtitle mb-4">
                        Cardigan, vest, dan knitwear premium dengan desain simple & timeless.
                        Cocok untuk kuliah, kerja, sampai hangout — dengan material yang lembut dan nyaman dipakai seharian.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="<?= BASE_URL ?>products.php" class="btn btn-light btn-lg pill-btn">
                            Lihat Katalog Produk
                        </a>
                        <a href="<?= BASE_URL ?>about.php" class="btn btn-outline-light btn-lg pill-btn">
                            Tentang Saputra Rajoet
                        </a>
                    </div>
                    <!-- TEKS BERJALAN -->
<div class="hero-marquee">
    <span>Saputra Rajoet • Knitwear Nyaman • Ready Stock • Bisa COD • Shopee & TikTok Shop</span>
</div>

                    <p class="text-soft small mb-0">
                        Website ini adalah pusat katalog resmi, informasi brand, dan penghubung ke kanal pembelian Saputra Rajoet.
                    </p>
                </div>

                <!-- Hero Image -->
                <div class="col-lg-6 text-center">
                    <img src="<?= BASE_URL ?>assets/img/belle01.jpeg"
                         alt="Saputra Rajoet"
                         class="img-fluid hero-image">
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION PRODUK TERBARU -->
    <section class="section">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-3 gap-2">
                <div>
                    <h2 class="section-title mb-1">Koleksi Produk Terbaru</h2>
                    <p class="section-subtitle mb-0">
                        Jelajahi beberapa produk terbaru kami. Klik salah satu kartu untuk melihat detail lengkap dan opsi pembelian.
                    </p>
                </div>
                <a href="<?= BASE_URL ?>products.php" class="text-soft small">
                    Lihat semua produk &raquo;
                </a>
            </div>

            <?php if ($produkRes && $produkRes->num_rows > 0): ?>
                <div class="row g-4">
                    <?php while ($p = $produkRes->fetch_assoc()): ?>
                        <?php
                        $produkUrl = BASE_URL . 'product_detail.php?id=' . (int)$p['id'];
                        $imgProduk = !empty($p['gambar_utama']) ? $p['gambar_utama'] : 'no_image.png';
                        ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card card-glass product-card h-100 position-relative">
                                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($imgProduk) ?>"
                                     class="product-img"
                                     alt="<?= htmlspecialchars($p['nama_produk']) ?>">

                                <div class="card-body d-flex flex-column">
                                    <span class="badge-soft mb-2">
                                        <?= htmlspecialchars($p['kategori'] ?: 'Knitwear') ?>
                                    </span>

                                    <h5 class="card-title mb-1">
                                        <?= htmlspecialchars($p['nama_produk']) ?>
                                    </h5>

                                    <p class="product-price mb-1">
                                        Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                    </p>

                                    <p class="card-text mb-3">
                                        Stok: <?= (int)$p['stok'] ?> pcs
                                    </p>

                                    <div class="mt-auto">
                                        <span class="text-soft small">
                                            Klik kartu untuk lihat detail & cara beli.
                                        </span>
                                    </div>

                                    <!-- SELURUH CARD BISA DIKLIK -->
                                    <a href="<?= $produkUrl ?>" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="card card-glass p-3">
                    <p class="mb-0 text-soft">Belum ada produk yang ditambahkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- SECTION INFORMASI & PROFIL BRAND (TANPA FOTO) -->
    <section class="section">
        <div class="container">
            <div class="mb-3">
                <h2 class="section-title mb-1">Profil & Informasi Brand</h2>
                <p class="section-subtitle mb-0">
                    Kenali lebih dekat Saputra Rajoet. Semua kartu informasi di bawah ini bisa diklik untuk membaca penjelasan lengkap.
                </p>
            </div>

            <?php if ($infoRes && $infoRes->num_rows > 0): ?>
                <div class="row g-4">
                    <?php while ($info = $infoRes->fetch_assoc()): ?>
                        <?php
                        $infoUrl = BASE_URL . 'detail.php?id=' . (int)$info['id'];
                        ?>
                        <div class="col-md-4">
                            <div class="card card-glass h-100 position-relative">

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-2">
                                        <?= htmlspecialchars($info['judul']) ?>
                                    </h5>
                                    <p class="card-text mb-3">
                                        <?= htmlspecialchars($info['ringkasan'] ?? '') ?>
                                    </p>

                                    <span class="text-soft small mt-auto">
                                        Klik kartu ini untuk baca detail informasi.
                                    </span>

                                    <!-- SELURUH CARD BISA DIKLIK -->
                                    <a href="<?= $infoUrl ?>" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="card card-glass p-3">
                    <p class="mb-0 text-soft">Belum ada informasi yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
