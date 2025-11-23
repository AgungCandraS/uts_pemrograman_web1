<?php
// api/connection.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

// gunakan fungsi yang sama dengan website
$conn = getConnection();

// kalau gagal, kirim JSON error dan stop
if ($conn->connect_errno) {
    http_response_code(500);
    echo json_encode([
        'status'  => false,
        'message' => 'Gagal koneksi database',
        'error'   => $conn->connect_error,
    ], JSON_PRETTY_PRINT);
    exit;
}

/**
 * Kalau file ini diakses langsung (bukan di-include),
 * kirim JSON sederhana sebagai "ping" untuk test di Bruno.
 */
if (realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    echo json_encode([
        'status'  => true,
        'message' => 'Connection successful',
        'database'=> 'uts_pemweb1'
    ], JSON_PRETTY_PRINT);
    exit;
}

// kalau di-include file lain, cukup sediakan $conn saja (tanpa echo apa-apa)
