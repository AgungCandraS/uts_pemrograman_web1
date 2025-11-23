<?php
// public/products.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$conn = getConnection();

// Ambil filter dari query string
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$search   = isset($_GET['q']) ? trim($_GET['q']) : '';

// Query dasar
$sql    = "SELECT * FROM produk WHERE status = 'aktif'";
$params = [];
$types  = "";

// Filter kategori (opsional)
if ($kategori !== '') {
    $sql       .= " AND kategori = ?";
    $params[]   = $kategori;
    $types     .= "s";
}

// Filter pencarian nama produk (opsional)
if ($search !== '') {
    $sql       .= " AND nama_produk LIKE ?";
    $params[]   = "%{$search}%";
    $types     .= "s";
}

// Urutkan dari produk terbaru
$sql .= " ORDER BY created_at DESC";

// Eksekusi query sesuai ada/tidaknya parameter
if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $produkRes = $stmt->get_result();
} else {
    $produkRes = $conn->query($sql);
}

// Ambil daftar kategori unik untuk dropdown
$kategoriRes = $conn->query("
    SELECT DISTINCT kategori 
    FROM produk 
    WHERE kategori IS NOT NULL 
      AND kategori <> '' 
    ORDER BY kategori ASC
");

$pageTitle = 'Katalog Produk';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main class="py-5">
    <div class="container">

        <!-- HEADER KATALOG -->
        <div class="mb-4">
            <span class="hero-kicker d-inline-block mb-2">Koleksi Terbaru</span>
            <h1 class="hero-title mb-2" style="font-size: 1.8rem;">
                Katalog Produk Saputra Rajoet
            </h1>
            <p class="text-soft mb-0" style="max-width: 620px;">
                Temukan berbagai pilihan knitwear premium—cardigan, vest, outer, dan lainnya.
                Semua produk dapat diklik untuk melihat detail dan opsi pembelian.
            </p>
        </div>

        <!-- FILTER BAR -->
        <form method="get" class="card card-glass p-3 mb-4">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="text-soft small mb-1">Kategori</label>
                    <select name="kategori"
                            class="form-select bg-dark text-soft border-secondary">
                        <option value="">Semua Kategori</option>
                        <?php if ($kategoriRes && $kategoriRes->num_rows > 0): ?>
                            <?php while ($kat = $kategoriRes->fetch_assoc()): ?>
                                <?php $val = $kat['kategori']; ?>
                                <option value="<?= htmlspecialchars($val) ?>"
                                    <?= $kategori === $val ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($val) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="text-soft small mb-1">Pencarian</label>
                    <input type="text"
                           name="q"
                           value="<?= htmlspecialchars($search) ?>"
                           placeholder="Cari nama produk..."
                           class="form-control bg-dark text-soft border-secondary">
                </div>

                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary pill-btn">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- LIST PRODUK -->
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

                                <?php if (!empty($p['kode_produk'])): ?>
                                    <p class="text-soft small mb-1">
                                        Kode: <?= htmlspecialchars($p['kode_produk']) ?>
                                    </p>
                                <?php endif; ?>

                                <p class="product-price mb-1">
                                    Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                </p>

                                <p class="text-soft mb-3 small">
                                    Stok: <?= (int)$p['stok'] ?> pcs
                                </p>

                                <span class="text-soft small mt-auto">
                                    Klik untuk lihat detail
                                </span>

                                <!-- SELURUH CARD BISA DIKLIK -->
                                <a href="<?= $produkUrl ?>" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="card card-glass p-4 text-center">
                <h2 class="h6 text-white mb-2">Produk Tidak Ditemukan</h2>
                <p class="text-soft small mb-3">
                    Tidak ada produk yang cocok dengan kategori atau kata kunci yang kamu pilih.
                </p>
                <a href="<?= BASE_URL ?>products.php" class="btn btn-outline-light pill-btn btn-sm">
                    Reset Filter
                </a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
