<?php
// api/delete_data.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, GET');

require_once __DIR__ . '/connection.php';

// ambil id dari query string (?id=)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode([
        'status'  => false,
        'message' => 'Parameter id wajib dan harus angka > 0.',
    ], JSON_PRETTY_PRINT);
    exit;
}

$sql  = "DELETE FROM produk WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo json_encode([
        'status'  => true,
        'message' => 'Data produk berhasil dihapus.',
        'id'      => $id,
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        'status'  => false,
        'message' => 'Gagal menghapus data.',
        'error'   => $stmt->error,
    ], JSON_PRETTY_PRINT);
}
