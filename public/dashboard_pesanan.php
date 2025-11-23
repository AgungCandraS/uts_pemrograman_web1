<?php
// public/dashboard_pesanan.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

session_start();

// hanya admin yang boleh akses
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'user') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$conn = getConnection();

$res = $conn->query("SELECT * FROM kontak ORDER BY created_at DESC");

$pageTitle = 'Pesanan / Pesan Masuk';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main class="py-5">
    <div class="container">
        <h1 class="h4 text-white mb-2">Pesanan / Pesan Masuk via Website</h1>
        <p class="text-soft small mb-4">
            Halaman ini menampilkan semua data yang dikirim dari form <strong>Kontak</strong> maupun
            <strong>Pemesanan Produk via Website</strong>.
        </p>

        <div class="card card-glass">
            <div class="card-body">
                <?php if ($res && $res->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Subjek</th>
                                    <th>Pesan</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1; ?>
                            <?php while ($row = $res->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['subjek'] ?? '-') ?></td>
                                    <td style="max-width: 300px;">
                                        <div class="text-soft small" style="white-space: pre-line; max-height: 120px; overflow:auto;">
                                            <?= htmlspecialchars($row['pesan']) ?>
                                        </div>
                                    </td>
                                    <td class="text-soft small">
                                        <?= htmlspecialchars($row['created_at']) ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-soft mb-0">Belum ada pesan atau pesanan yang masuk.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
