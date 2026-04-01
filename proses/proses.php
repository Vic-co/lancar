<?php
/**
 * File Proses - CRUD Aspirasi
 * Menangani: Tambah, Ubah, Hapus Data Aspirasi
 */

require_once '../config/koneksi.php';

// PROSES TAMBAH ASPIRASI (INSERT)
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah_aspirasi') {
    $nis = keamanan($_POST['nis']);
    $id_kategori = keamanan($_POST['id_kategori']);
    $lokasi = keamanan($_POST['lokasi']);
    $ket = keamanan($_POST['ket']);
    
    // Validasi input
    if (empty($nis) || empty($id_kategori) || empty($lokasi) || empty($ket)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Semua field harus diisi!']);
        exit;
    }
    
    // Query INSERT ke tabel input_aspirasi
    $query = "INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket) 
              VALUES ('$nis', '$id_kategori', '$lokasi', '$ket')";
    
    if (eksekusi_query($query)) {
        // Ambil id_pelaporan yang baru dibuat
        $id_pelaporan = $conn->insert_id;
        
        // Masukkan data ke tabel aspirasi dengan id_pelaporan dan status default 'Menunggu'
        $query_aspirasi = "INSERT INTO aspirasi (id_pelaporan, id_kategori, status) 
                          VALUES ('$id_pelaporan', '$id_kategori', 'Menunggu')";
        
        if (eksekusi_query($query_aspirasi)) {
            $_SESSION['pesan_sukses'] = 'Aspirasi berhasil diajukan!';
            $_SESSION['success_animation'] = true;
            header('Location: ../siswa/dashboard.php');
            exit;
        } else {
            echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan ke tabel aspirasi!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menambah aspirasi: ' . $conn->error]);
    }
    exit;
}

// PROSES UBAH STATUS ASPIRASI (UPDATE)
if (isset($_POST['aksi']) && $_POST['aksi'] == 'ubah_status') {
    $id_aspirasi = keamanan($_POST['id_aspirasi']);
    $status = keamanan($_POST['status']);
    
    if (empty($id_aspirasi) || empty($status)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Data tidak lengkap!']);
        exit;
    }
    
    $query = "UPDATE aspirasi SET status = '$status' WHERE id_aspirasi = '$id_aspirasi'";
    
    if (eksekusi_query($query)) {
        $_SESSION['pesan_sukses'] = 'Status aspirasi berhasil diperbarui!';
        header('Location: ../admin/dashboard.php');
        exit;
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal mengubah status!']);
    }
    exit;
}

// PROSES TAMBAH FEEDBACK (UPDATE)
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah_feedback') {
    $id_aspirasi = keamanan($_POST['id_aspirasi']);
    $feedback = keamanan($_POST['feedback']);
    
    if (empty($id_aspirasi) || empty($feedback)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Data tidak lengkap!']);
        exit;
    }
    
    // Simpan feedback ke tabel feedback jika ada, atau update di aspirasi
    $query = "UPDATE aspirasi SET feedback = '$feedback' WHERE id_aspirasi = '$id_aspirasi'";
    
    if (eksekusi_query($query)) {
        $_SESSION['pesan_sukses'] = 'Feedback berhasil ditambahkan!';
        header('Location: ../admin/dashboard.php');
        exit;
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menambah feedback!']);
    }
    exit;
}

// PROSES HAPUS ASPIRASI (DELETE)
if (isset($_POST['aksi']) && $_POST['aksi'] == 'hapus_aspirasi') {
    $id_aspirasi = keamanan($_POST['id_aspirasi']);
    
    if (empty($id_aspirasi)) {
        echo json_encode(['status' => 'error', 'pesan' => 'ID aspirasi tidak ada!']);
        exit;
    }
    
    // Hapus dari aspirasi
    $query = "DELETE FROM aspirasi WHERE id_aspirasi = '$id_aspirasi'";
    
    if (eksekusi_query($query)) {
        $_SESSION['pesan_sukses'] = 'Aspirasi berhasil dihapus!';
        header('Location: ../admin/dashboard.php');
        exit;
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus aspirasi!']);
    }
    exit;
}

// Jika tidak ada aksi, redirect
header('Location: ../login.php');
exit;
?>
