<?php
/**
 * API untuk get kelas berdasarkan NIS
 */

header('Content-Type: application/json');

require_once '../config/koneksi.php';

$nis = isset($_GET['nis']) ? keamanan($_GET['nis']) : '';

if (empty($nis)) {
    echo json_encode(['status' => 'error', 'pesan' => 'NIS tidak boleh kosong']);
    exit;
}

// Cek apakah NIS ada di database
$query = "SELECT kelas FROM siswa WHERE nis = '$nis'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success',
        'kelas' => $data['kelas']
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'pesan' => 'NIS tidak ditemukan'
    ]);
}
exit;
?>
