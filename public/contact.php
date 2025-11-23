<?php
// public/contact.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

session_start();
$conn = getConnection();

$message = null;
$messageType = 'success';

// cek apakah ada product_id di URL (order dari halaman produk)
$productId   = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$productData = null;


if ($productId > 0 && empty($_SESSION['user_id'])) {
    $redirectTarget = 'contact.php?product_id=' . $productId;
    header('Location: ' . BASE_URL . 'login.php?redirect=' . urlencode($redirectTarget));
    exit;
}

if ($productId > 0) {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE id = ? AND status = 'aktif'");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $res = $stmt->get_result();
    $productData = $res->fetch_assoc();
}

// Default form value (autofill fitur jika dari produk)
$defaultSubjek = $productData ? 'Pemesanan Produk: ' . $productData['nama_produk'] : '';
$defaultPesan  = $productData
    ? "Halo Saputra Rajoet,\n\nSaya ingin memesan produk berikut:\n" .
      "- Nama produk : {$productData['nama_produk']}\n" .
      "- Kode produk : " . ($productData['kode_produk'] ?: '-') . "\n" .
      "- Harga       : Rp " . number_format($productData['harga'], 0, ',', '.') . "\n\n" .
      "Mohon informasikan ketersediaan stok, varian warna (jika ada), dan cara pembayaran.\n\nTerima kasih."
    : '';


// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan  = trim($_POST['pesan'] ?? '');
    $hiddenProductId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    if ($nama === '' || $email === '' || $pesan === '') {
        $message = 'Nama, email, dan pesan wajib diisi.';
        $messageType = 'danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Format email tidak valid.';
        $messageType = 'danger';
    } else {
        $stmt = $conn->prepare("INSERT INTO kontak (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $nama, $email, $subjek, $pesan);

        if ($stmt->execute()) {
            $messageType = 'success';
            $message = 'Pesan berhasil dikirim. Kami akan menghubungi kamu kembali melalui email.';

            $_POST = []; // kosongkan input supaya form reset
        } else {
            $messageType = 'danger';
            $message = 'Terjadi kesalahan saat mengirim pesan. Coba beberapa saat lagi.';
        }
    }

    // Re-fetch product after submit
    if ($hiddenProductId > 0 && !$productData) {
        $stmt = $conn->prepare("SELECT * FROM produk WHERE id = ? AND status = 'aktif'");
        $stmt->bind_param('i', $hiddenProductId);
        $stmt->execute();
        $productData = $stmt->get_result()->fetch_assoc();
    }
}

$pageTitle = $productData ? 'Pemesanan Produk' : 'Kontak';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main class="py-5">
    <div class="container" style="max-width: 950px;">

        <!-- HEADER -->
        <div class="mb-4">
            <span class="hero-kicker d-inline-block mb-2">
                <?= $productData ? 'Form Pemesanan via Website' : 'Form Kontak' ?>
            </span>

            <h1 class="hero-title mb-2" style="font-size: 1.75rem;">
                <?= $productData ? 'Pemesanan Produk' : 'Hubungi Saputra Rajoet' ?>
            </h1>

            <p class="text-soft mb-0">
                <?= $productData
                    ? 'Isi data di bawah ini untuk melakukan pemesanan produk. Admin akan menghubungi kamu untuk konfirmasi lanjutan.'
                    : 'Jika kamu memiliki pertanyaan, kerja sama, atau ingin mengetahui detail produk, silakan isi form berikut.' ?>
            </p>
        </div>

        <div class="row g-4">
            <!-- RINGKASAN PRODUK (jika ada product_id) -->
            <?php if ($productData): ?>
                <div class="col-md-5">
                    <div class="card card-glass h-100">
                        <div class="card-body">
                            <h2 class="h6 text-white mb-3">Detail Produk</h2>

                            <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($productData['gambar_utama'] ?: 'no_image.png') ?>"
                                alt="<?= htmlspecialchars($productData['nama_produk']) ?>"
                                class="img-fluid mb-3"
                                style="border-radius: 16px; max-height: 260px; object-fit: cover;">

                            <h3 class="h6 text-white mb-1">
                                <?= htmlspecialchars($productData['nama_produk']) ?>
                            </h3>

                            <?php if ($productData['kode_produk']): ?>
                                <p class="text-soft small mb-1">
                                    Kode: <?= htmlspecialchars($productData['kode_produk']) ?>
                                </p>
                            <?php endif; ?>

                            <p class="product-price mb-1">
                                Rp <?= number_format($productData['harga'], 0, ',', '.') ?>
                            </p>

                            <p class="text-soft small mb-0">
                                Stok tersedia: <?= (int)$productData['stok'] ?> pcs
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
            <?php else: ?>
                <div class="col-12">
            <?php endif; ?>

                    <!-- FORM KONTAK -->
                    <div class="card card-glass">
                        <div class="card-body">
                            
                            <?php if ($message): ?>
                                <div class="alert alert-<?= $messageType ?> mb-3">
                                    <?= htmlspecialchars($message) ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" id="contactForm" novalidate>
                                <?php if ($productData): ?>
                                    <input type="hidden" name="product_id" value="<?= (int)$productData['id'] ?>">
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label class="form-label text-soft">Nama Lengkap</label>
                                    <input type="text"
                                        name="nama"
                                        class="form-control bg-dark text-soft"
                                        required
                                        value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-soft">Email Aktif</label>
                                    <input type="email"
                                        name="email"
                                        class="form-control bg-dark text-soft"
                                        required
                                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-soft">Subjek</label>
                                    <input type="text"
                                        name="subjek"
                                        class="form-control bg-dark text-soft"
                                        value="<?= htmlspecialchars($_POST['subjek'] ?? $defaultSubjek) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-soft">Pesan</label>
                                    <textarea name="pesan"
                                        class="form-control bg-dark text-soft"
                                        rows="6"
                                        required><?= htmlspecialchars($_POST['pesan'] ?? $defaultPesan) ?></textarea>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-soft">
                                        * Data yang kamu kirim aman dan hanya digunakan untuk menindaklanjuti pesan.
                                    </small>

                                    <button type="submit" class="btn btn-primary pill-btn">
                                        Kirim Pesan
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
