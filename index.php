<?php
/**
 * Halaman Utama Aplikasi Pengaduan Sarana Sekolah
 */

session_start();

// Jika admin sudah login, redirect ke admin dashboard
if (isset($_SESSION['username'])) {
    header('Location: admin/dashboard.php');
    exit;
}

// Redirect ke siswa dashboard (tidak perlu login, cukup input NIS/Kelas)
header('Location: siswa/dashboard.php');
exit;
?>
