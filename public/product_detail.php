<?php
// public/product_detail.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$conn = getConnection();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ? AND status = 'aktif'");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$produk = $res->fetch_assoc();

if (!$produk) {
    http_response_code(404);
    $pageTitle = 'Produk Tidak Ditemukan';
} else {
    $pageTitle = $produk['nama_produk'];
}

require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main class="py-5">
    <div class="container" style="max-width: 1100px;">

        <?php if (!$produk): ?>

            <!-- PRODUK TIDAK DITEMUKAN -->
            <div class="card card-glass p-4 text-center">
                <h1 class="h4 text-white mb-2">Produk Tidak Ditemukan</h1>
                <p class="text-soft mb-3">
                    Produk yang kamu cari tidak tersedia atau ID tidak valid.
                </p>
                <a href="<?= BASE_URL ?>products.php"
                   class="btn btn-outline-light pill-btn">
                    &laquo; Kembali ke Katalog Produk
                </a>
            </div>

        <?php else: ?>

            <!-- DETAIL PRODUK -->
            <div class="row g-4">
                
                <!-- GAMBAR PRODUK -->
                <div class="col-md-5">
                    <?php
                    $imgProduk = !empty($produk['gambar_utama'])
                        ? $produk['gambar_utama']
                        : 'no_image.png';
                    ?>
                    <div class="card card-glass h-100 p-2">
                        <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($imgProduk) ?>"
                             alt="<?= htmlspecialchars($produk['nama_produk']) ?>"
                             class="img-fluid"
                             style="
                                border-radius: 16px;
                                max-height: 420px;
                                width: 100%;
                                object-fit: cover;
                             ">
                    </div>
                </div>

                <!-- DETAIL KONTEN -->
                <div class="col-md-7">
                    <div class="card card-glass h-100">
                        <div class="card-body">

                            <!-- KATEGORI -->
                            <span class="badge-soft mb-2 d-inline-block">
                                <?= htmlspecialchars($produk['kategori'] ?: 'Knitwear') ?>
                            </span>

                            <!-- NAMA PRODUK -->
                            <h1 class="hero-title mb-2" style="font-size: 1.75rem;">
                                <?= htmlspecialchars($produk['nama_produk']) ?>
                            </h1>

                            <!-- KODE PRODUK -->
                            <?php if (!empty($produk['kode_produk'])): ?>
                                <p class="text-soft small mb-1">
                                    Kode Produk: <?= htmlspecialchars($produk['kode_produk']) ?>
                                </p>
                            <?php endif; ?>

                            <!-- HARGA -->
                            <p class="product-price fs-3 mb-1">
                                Rp <?= number_format($produk['harga'], 0, ',', '.') ?>
                            </p>

                            <!-- STOK -->
                            <p class="text-soft mb-3">
                                Stok Tersedia: <strong><?= (int)$produk['stok'] ?></strong> pcs
                            </p>

                            <hr class="border-secondary">

                            <!-- DESKRIPSI PRODUK -->
                            <div class="mb-4">
                                <h2 class="h6 text-white mb-2">Deskripsi Produk</h2>
                                <p class="text-soft" style="white-space: pre-line; line-height: 1.6;">
                                    <?= htmlspecialchars($produk['deskripsi'] ?: 'Belum ada deskripsi produk.') ?>
                                </p>
                            </div>

                            <hr class="border-secondary">

                            <!-- OPSI PEMBELIAN -->
                            <div class="mb-4">
                                <h2 class="h6 text-white mb-2">Opsi Pembelian</h2>
                                <p class="text-soft small mb-3">
                                    Pilih metode pembelian yang kamu inginkan.  
                                    Bisa langsung via website, WhatsApp admin, atau marketplace resmi Saputra Rajoet.
                                </p>

                                <div class="d-flex flex-wrap gap-2">

                                    <!-- VIA WEBSITE (FORM PEMESANAN) -->
                                    <a href="<?= BASE_URL ?>contact.php?product_id=<?= (int)$produk['id'] ?>"
                                       class="btn btn-primary pill-btn">
                                        Beli via Website
                                    </a>

                                    <!-- VIA WHATSAPP -->
                                    <?php
                                    $waMessage = rawurlencode(
                                        "Halo Saputra Rajoet, saya ingin pesan produk: " .
                                        $produk['nama_produk'] . 
                                        " (Kode: " . ($produk['kode_produk'] ?: '-') . "). Masih tersedia?"
                                    );
                                    $waLink = WHATSAPP_URL . '?text=' . $waMessage;
                                    ?>
                                    <a href="<?= $waLink ?>"
                                       target="_blank"
                                       class="btn btn-success pill-btn">
                                        WhatsApp Admin
                                    </a>

                                    <!-- VIA SHOPEE -->
                                    <a href="<?= SHOPEE_URL ?>" target="_blank"
                                       class="btn btn-warning pill-btn">
                                        Shopee Store
                                    </a>

                                    <!-- VIA TIKTOK SHOP -->
                                    <a href="<?= TIKTOK_URL ?>" target="_blank"
                                       class="btn btn-dark pill-btn">
                                        TikTok Shop
                                    </a>

                                </div>

                                <!-- INFO LOGIN UNTUK BELI VIA WEBSITE -->
                                <p class="text-soft small mt-2 mb-0">
                                    * Untuk pembelian <strong>via Website</strong>, kamu akan diminta login terlebih dahulu.
                                    Pembelian via WhatsApp, Shopee, dan TikTok Shop bisa diakses tanpa login.
                                </p>
                            </div>

                            <hr class="border-secondary">

                            <!-- BACK TO KATALOG -->
                            <a href="<?= BASE_URL ?>products.php"
                               class="btn btn-outline-light pill-btn btn-sm">
                                &laquo; Kembali ke Katalog
                            </a>

                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
