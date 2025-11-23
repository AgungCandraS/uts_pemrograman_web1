<?php
// api/insup_data.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status'  => false,
        'message' => 'Method not allowed. Gunakan POST.',
    ], JSON_PRETTY_PRINT);
    exit;
}

// ambil data dari body (x-www-form-urlencoded / form-data)
$id         = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nama       = trim($_POST['nama_produk'] ?? '');
$kategori   = trim($_POST['kategori'] ?? '');
$harga      = (int)($_POST['harga'] ?? 0);
$stok       = (int)($_POST['stok'] ?? 0);
$deskripsi  = trim($_POST['deskripsi'] ?? '');
$gambar     = trim($_POST['gambar_utama'] ?? '');
$statusProd = trim($_POST['status'] ?? 'aktif');

// validasi sederhana
if ($nama === '' || $harga <= 0) {
    echo json_encode([
        'status'  => false,
        'message' => 'Field nama_produk dan harga wajib diisi dan valid.',
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($id > 0) {
    // UPDATE
    $sql = "UPDATE produk 
            SET nama_produk = ?, kategori = ?, harga = ?, stok = ?, deskripsi = ?, gambar_utama = ?, status = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssissssi',
        $nama,
        $kategori,
        $harga,
        $stok,
        $deskripsi,
        $gambar,
        $statusProd,
        $id
    );

    $aksi = 'update';
} else {
    // INSERT
    $sql = "INSERT INTO produk (nama_produk, kategori, harga, stok, deskripsi, gambar_utama, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssissss',
        $nama,
        $kategori,
        $harga,
        $stok,
        $deskripsi,
        $gambar,
        $statusProd
    );

    $aksi = 'insert';
}

if ($stmt->execute()) {
    echo json_encode([
        'status'  => true,
        'message' => $aksi === 'insert'
            ? 'Data produk berhasil ditambahkan.'
            : 'Data produk berhasil diperbarui.',
        'action'  => $aksi,
        'id'      => $id > 0 ? $id : $conn->insert_id,
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        'status'  => false,
        'message' => 'Query gagal dieksekusi.',
        'error'   => $stmt->error,
    ], JSON_PRETTY_PRINT);
}
