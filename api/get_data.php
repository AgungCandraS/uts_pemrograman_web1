<?php
// api/get_data.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/connection.php'; // di sini $conn sudah siap

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$data  = [];
$query = '';
$stmt  = null;

if ($id > 0) {
    // GET satu data produk berdasarkan id
    $query = "SELECT * FROM produk WHERE id = ? AND status = 'aktif'";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param('i', $id);
} else {
    // GET semua produk aktif
    $query = "SELECT * FROM produk WHERE status = 'aktif' ORDER BY created_at DESC";
    $stmt  = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    'status'  => true,
    'message' => 'Data produk berhasil diambil',
    'count'   => count($data),
    'data'    => $data,
], JSON_PRETTY_PRINT);
