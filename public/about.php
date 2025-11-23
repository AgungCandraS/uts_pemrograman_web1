<?php
// public/about.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Tentang Kami';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>

<main class="py-5">
    <div class="container">

        <!-- HEADER / HERO TENTANG KAMI -->
        <div class="row g-4 align-items-center mb-5">
            <div class="col-lg-6">
                <span class="hero-kicker mb-2 d-inline-block">Tentang Brand</span>

                <h1 class="hero-title mb-3">
                    Saputra Rajoet,<br>
                    knitwear lokal untuk gaya modern.
                </h1>

                <p class="hero-subtitle mb-3">
                    Saputra Rajoet adalah brand fashion rajut lokal yang berfokus pada
                    kenyamanan, detail, dan desain yang timeless. Setiap cardigan, vest,
                    dan knitwear dirancang agar mudah dipadupadankan dan nyaman dipakai
                    seharian.
                </p>

                <p class="text-soft mb-4">
                    Website ini menjadi pusat katalog resmi dan identitas digital Saputra Rajoet:
                    menampilkan koleksi produk, profil brand, hingga menghubungkan pelanggan ke
                    berbagai kanal pembelian seperti WhatsApp, Shopee, dan TikTok Shop.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= BASE_URL ?>products.php" class="btn btn-light pill-btn">
                        Lihat Koleksi Produk
                    </a>
                    <a href="<?= BASE_URL ?>contact.php" class="btn btn-outline-light pill-btn">
                        Hubungi Kami
                    </a>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <img src="<?= BASE_URL ?>assets/img/logo.png"
                     alt="Saputra Rajoet"
                     class="img-fluid hero-image">
            </div>
        </div>

        <!-- HIGHLIGHT ANGKA BRAND -->
        <div class="row g-3 mb-5">
            <div class="col-6 col-md-3">
                <div class="card card-glass h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-4 text-white">3+</div>
                        <div class="text-soft small">Tahun pengalaman<br>di knitwear</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-glass h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-4 text-white">100+</div>
                        <div class="text-soft small">Model & varian<br>produk rajut</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-glass h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-4 text-white">Banyak</div>
                        <div class="text-soft small">Pelanggan puas<br>di berbagai kota</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-glass h-100">
                    <div class="card-body text-center">
                        <div class="fw-bold fs-4 text-white">Multi</div>
                        <div class="text-soft small">Channel penjualan<br>online & offline</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TIGA KARTU PENJELASAN BRAND -->
        <div class="row g-4">
            <!-- Visi -->
            <div class="col-md-4">
                <div class="card card-glass h-100">
                    <div class="card-body">
                        <h2 class="h6 text-white mb-2">Visi Brand</h2>
                        <p class="text-soft mb-0">
                            Menjadi brand knitwear lokal yang dipercaya karena kualitas,
                            kenyamanan, dan desain yang mudah dipadupadankan untuk berbagai
                            kesempatan: kuliah, kerja, hingga hangout santai.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Nilai Utama -->
            <div class="col-md-4">
                <div class="card card-glass h-100">
                    <div class="card-body">
                        <h2 class="h6 text-white mb-2">Nilai Utama</h2>
                        <ul class="text-soft mb-0 small">
                            <li>Kenyamanan bahan yang lembut & tidak gerah.</li>
                            <li>Detail rajutan rapi dan awet digunakan.</li>
                            <li>Desain simple yang mudah di-mix & match.</li>
                            <li>Pelayanan responsif melalui chat & media sosial.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Channel Penjualan -->
            <div class="col-md-4">
                <div class="card card-glass h-100">
                    <div class="card-body">
                        <h2 class="h6 text-white mb-2">Channel Penjualan</h2>
                        <p class="text-soft small mb-3">
                            Produk Saputra Rajoet dapat dibeli melalui beberapa kanal resmi:
                        </p>
                        <ul class="text-soft small mb-3">
                            <li>Order langsung via website (form pemesanan).</li>
                            <li>Chat langsung via WhatsApp untuk tanya stok & warna.</li>
                            <li>Official store di marketplace (Shopee & TikTok Shop).</li>
                        </ul>
                        <p class="text-soft small mb-0">
                            Detail tombol pembelian akan muncul di setiap halaman produk,
                            sehingga pelanggan bisa memilih cara belanja yang paling nyaman.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
