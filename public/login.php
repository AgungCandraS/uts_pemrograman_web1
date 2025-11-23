<?php
// public/login.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

session_start();
$conn = getConnection();
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $message = 'Email dan password wajib diisi.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['nama'];
            $_SESSION['user_role']  = $user['role'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: ' . BASE_URL . 'dashboard.php');
            exit;
        } else {
            $message = 'Email atau password salah.';
        }
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main class="py-5">
    <div class="container" style="max-width: 480px;">

        <!-- HEADER LOGIN -->
        <div class="text-center mb-4">
            <span class="hero-kicker d-inline-block mb-2">Masuk ke Akun</span>
            <h1 class="hero-title mb-2" style="font-size: 1.8rem;">
                Login Saputra Rajoet
            </h1>
            <p class="text-soft small mb-0">
                Gunakan email dan password yang sudah terdaftar untuk mengakses dashboard
                dan fitur di website Saputra Rajoet.
            </p>
        </div>

        <!-- CARD LOGIN -->
        <div class="card card-glass">
            <div class="card-body">

                <?php if ($message): ?>
                    <div class="alert alert-danger mb-3">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form method="post" id="loginForm" novalidate>
                    <div class="mb-3">
                        <label class="form-label text-soft">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control bg-dark text-soft"
                            required
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-soft">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control bg-dark text-soft"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 pill-btn mt-2">
                        Masuk
                    </button>
                </form>

                <p class="text-soft small mt-3 mb-1 text-center">
                    Belum punya akun?
                    <a href="<?= BASE_URL ?>register.php">Registrasi di sini</a>.
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
