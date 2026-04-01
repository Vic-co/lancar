<?php
/**
 * Logout Siswa
 */

session_start();

// Destroy session siswa
unset($_SESSION['nis_siswa']);
unset($_SESSION['kelas_siswa']);
unset($_SESSION['logged_in_siswa']);

session_destroy();

// Redirect ke login siswa
header('Location: siswa_login.php');
exit;
