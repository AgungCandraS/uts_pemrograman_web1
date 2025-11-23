<?php
// public/detail.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$conn = getConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM informasi WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$info = $result->fetch_assoc();

if (!$info) {
    http_response_code(404);
    $pageTitle = 'Informasi Tidak Ditemukan';
} else {
    $pageTitle = $info['judul'];
}

require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main class="py-5">
    <div class="container" style="max-width: 900px;">
        <?php if (!$info): ?>
            <div class="card card-glass p-4">
                <h1 class="h5 text-white mb-2">Informasi Tidak Ditemukan</h1>
                <p class="text-soft mb-3">
                    Data informasi yang kamu pilih tidak tersedia atau ID tidak valid.
                </p>
                <a href="<?= BASE_URL ?>index.php" class="btn btn-outline-light pill-btn btn-sm">
                    &laquo; Kembali ke Beranda
                </a>
            </div>
        <?php else: ?>
            <article class="card card-glass">
                <div class="card-body">

                    <span class="hero-kicker d-inline-block mb-2">Informasi Brand</span>

                    <h1 class="hero-title mb-3" style="font-size: 1.9rem;">
                        <?= htmlspecialchars($info['judul']) ?>
                    </h1>

                    <div class="text-soft small mb-3">
                        Dipublikasikan pada: <?= htmlspecialchars($info['created_at']) ?>
                    </div>

                    <!-- KONTEN / ISI ARTIKEL TANPA GAMBAR -->
                    <div class="text-soft content-text mb-4"
                         style="white-space: pre-line; font-size: 0.95rem; line-height: 1.65;">
                        <?= nl2br(htmlspecialchars($info['isi'])) ?>
                    </div>

                    <hr class="border-secondary mb-4">

                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL ?>index.php" class="btn btn-outline-light pill-btn btn-sm">
                            &laquo; Kembali ke Beranda
                        </a>
                        <a href="<?= BASE_URL ?>about.php" class="btn btn-outline-light pill-btn btn-sm">
                            Tentang Brand
                        </a>
                    </div>

                </div>
            </article>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
