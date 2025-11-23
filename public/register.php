<?php
// public/register.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

session_start();
$conn = getConnection();
$message = null;
$messageType = 'danger';

// HANDLE REGISTER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');

    if ($nama === '' || $email === '' || $password === '' || $confirm === '') {
        $message = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Format email tidak valid.';
    } elseif ($password !== $confirm) {
        $message = 'Password dan konfirmasi password tidak sama.';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param('sss', $nama, $email, $hash);

        if ($stmt->execute()) {
            $messageType = 'success';
            $message = 'Registrasi berhasil! Silakan login.';
            $_POST = []; // Kosongkan input
        } else {
            if ($conn->errno === 1062) {
                $message = 'Email sudah digunakan.';
            } else {
                $message = 'Terjadi kesalahan saat registrasi.';
            }
        }
    }
}

$pageTitle = 'Registrasi Akun';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main class="py-5">
    <div class="container" style="max-width: 500px;">

        <!-- HEADER REGISTER -->
        <div class="text-center mb-4">
            <span class="hero-kicker d-inline-block mb-2">Daftar Akun Baru</span>

            <h1 class="hero-title mb-2" style="font-size: 1.8rem;">
                Registrasi Saputra Rajoet
            </h1>

            <p class="text-soft small mb-0">
                Buat akun untuk mengakses dashboard dan menikmati pengalaman penuh di website Saputra Rajoet.
                Role awal: <strong>user</strong>. Role admin bisa diatur lewat phpMyAdmin.
            </p>
        </div>

        <!-- CARD FORM REGISTER -->
        <div class="card card-glass">
            <div class="card-body">

                <!-- Pesan error / sukses -->
                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?> mb-3">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form method="post" id="registerForm" novalidate>

                    <!-- NAMA -->
                    <div class="mb-3">
                        <label class="form-label text-soft">Nama Lengkap</label>
                        <input
                            type="text"
                            name="nama"
                            class="form-control bg-dark text-soft"
                            value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                            required>
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label class="form-label text-soft">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control bg-dark text-soft"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label text-soft">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control bg-dark text-soft"
                            required>
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label text-soft">Konfirmasi Password</label>
                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control bg-dark text-soft"
                            required>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn btn-primary w-100 pill-btn mt-2">
                        Daftar Sekarang
                    </button>
                </form>

                <p class="text-soft small mt-3 mb-1 text-center">
                    Sudah punya akun?
                    <a href="<?= BASE_URL ?>login.php">Login di sini</a>.
                </p>

                <p class="text-soft small text-center mb-0">
                    Kembali ke
                    <a href="<?= BASE_URL ?>index.php">Beranda Saputra Rajoet</a>.
                </p>

            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
