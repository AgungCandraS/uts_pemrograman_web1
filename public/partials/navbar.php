<?php
// public/partials/navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user_name'] ?? null;

// bantu cek halaman aktif (sederhana)
$currentScript = basename($_SERVER['SCRIPT_NAME']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-nav shadow-sm sticky-top">
    <div class="container">
<a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= BASE_URL ?>">
    <!-- WRAPPER BULAT -->
    <div class="brand-logo-wrapper">
        <img src="<?= BASE_URL ?>assets/img/logo.png"
             alt="Saputra Rajoet Logo"
             class="brand-logo">
    </div>

    <!-- TEKS BRAND -->
    <div class="brand-text">
        <span class="brand-name">Saputra Rajoet</span>
        <span class="brand-tagline">Knitwear & Apparel</span>
    </div>
</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentScript === 'index.php' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentScript === 'products.php' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>products.php">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentScript === 'about.php' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>about.php">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentScript === 'contact.php' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>contact.php">Kontak</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if ($currentUser): ?>
                    <a href="<?= BASE_URL ?>dashboard.php" class="btn btn-sm btn-outline-light pill-btn">
                        Dashboard
                    </a>
                    <span class="text-white ms-2 small d-none d-md-inline">
                        Halo, <?= htmlspecialchars($currentUser) ?>
                    </span>
                    <a href="<?= BASE_URL ?>logout.php" class="btn btn-sm btn-danger pill-btn ms-2">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>login.php" class="btn btn-sm btn-outline-light pill-btn">
                        Login
                    </a>
                    <a href="<?= BASE_URL ?>register.php" class="btn btn-sm btn-primary pill-btn">
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
