<?php
/**
 * File Koneksi Database
 * Database: lancar
 * Sistem: Aplikasi Pengaduan Sarana Sekolah
 */

// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'lancar');

// Membuat koneksi ke database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset untuk mendukung UTF-8
$conn->set_charset("utf8mb4");

// Fungsi untuk mengamankan input dari SQL Injection
function keamanan($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Fungsi untuk eksekusi query
function eksekusi_query($query) {
    global $conn;
    $result = $conn->query($query);
    return $result;
}

// Fungsi untuk mendapatkan hasil query sebagai array asosiatif
function ambil_data($query) {
    $result = eksekusi_query($query);
    $data = array();
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    return $data;
}

// Fungsi untuk mendapatkan satu baris data
function ambil_satu_data($query) {
    $result = eksekusi_query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Fungsi untuk menghitung baris
function hitung_baris($query) {
    $result = eksekusi_query($query);
    
    if ($result) {
        return $result->num_rows;
    }
    
    return 0;
}

// Fungsi untuk mengecek keberadaan data
function data_ada($table, $where) {
    global $conn;
    $query = "SELECT * FROM $table WHERE $where";
    $result = $conn->query($query);
    
    return $result->num_rows > 0;
}
?>
