<?php
/**
 * File Konfigurasi Khusus UKK
 * Berisi konstanta dan konfigurasi aplikasi
 * 
 * STANDAR KOMPETENSI: KM25.4.1.1
 * KOMPETENSI DASAR: Membuat aplikasi pengolahan data menggunakan PHP dan MySQL
 */

// ============================================
// 1. DATABASE CONFIGURATION
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'lancar');

// ============================================
// 2. APPLICATION CONFIGURATION
// ============================================

define('APP_NAME', 'Sistem Pengaduan Sarana Sekolah');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'production'); // production, development, testing

// ============================================
// 3. STATUS ASPIRASI
// ============================================

$STATUS_ASPIRASI = array(
    'Menunggu' => 'Menunggu',
    'Proses' => 'Proses',
    'Selesai' => 'Selesai'
);

// ============================================
// 4. KATEGORI ASPIRASI
// ============================================

$KATEGORI = array(
    1 => 'Kursi/Meja Rusak',
    2 => 'Papan Tulis Rusak',
    3 => 'Kaca Pecah',
    4 => 'Pintu Rusak',
    5 => 'Atap Bocor',
    6 => 'AC Tidak Berfungsi',
    7 => 'Lampu Mati',
    8 => 'Saluran Air Tersumbat'
);

// ============================================
// 5. ROLE PENGGUNA
// ============================================

$ROLES = array(
    'admin' => 'Administrator',
    'siswa' => 'Siswa'
);

// ============================================
// 6. SETTING PAGINATION
// ============================================

define('ITEMS_PER_PAGE', 10);

// ============================================
// 7. DATE & TIME FORMAT
// ============================================

define('DATE_FORMAT', 'd-m-Y');
define('DATETIME_FORMAT', 'd-m-Y H:i:s');
define('TIME_ZONE', 'Asia/Jakarta');

// Set timezone
date_default_timezone_set(TIME_ZONE);

// ============================================
// 8. UPLOAD CONFIGURATION (Untuk future use)
// ============================================

define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_TYPES', array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'));

// ============================================
// 9. EMAIL CONFIGURATION (Untuk future use)
// ============================================

define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 25);
define('SMTP_FROM', 'noreply@sekolah.local');

// ============================================
// 10. SESSION CONFIGURATION
// ============================================

define('SESSION_TIMEOUT', 3600); // 1 jam
ini_set('session.gc_maxlifetime', SESSION_TIMEOUT);

// ============================================
// 11. SECURITY CONFIGURATION
// ============================================

define('USE_PASSWORD_HASH', false); // Set to true untuk production
define('HASH_ALGORITHM', 'sha256');

// ============================================
// 12. ERROR HANDLING CONFIGURATION
// ============================================

if (APP_ENV == 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}

// ============================================
// 13. HELPER FUNCTIONS
// ============================================

/**
 * Fungsi untuk mendapatkan nama status dengan terjemahan
 */
function get_status_name($status) {
    global $STATUS_ASPIRASI;
    return isset($STATUS_ASPIRASI[$status]) ? $STATUS_ASPIRASI[$status] : $status;
}

/**
 * Fungsi untuk mendapatkan nama kategori
 */
function get_kategori_name($id_kategori) {
    global $KATEGORI;
    return isset($KATEGORI[$id_kategori]) ? $KATEGORI[$id_kategori] : 'Tidak ada kategori';
}

/**
 * Fungsi untuk mendapatkan badge CSS untuk status
 */
function get_status_badge($status) {
    $badges = array(
        'Menunggu' => '<span class="badge bg-warning">Menunggu</span>',
        'Proses' => '<span class="badge bg-info">Diproses</span>',
        'Selesai' => '<span class="badge bg-success">Selesai</span>'
    );
    return isset($badges[$status]) ? $badges[$status] : '<span class="badge bg-secondary">' . $status . '</span>';
}

/**
 * Fungsi untuk format tanggal
 */
function format_date($date) {
    return date(DATE_FORMAT, strtotime($date));
}

/**
 * Fungsi untuk format tanggal dan waktu
 */
function format_datetime($datetime) {
    return date(DATETIME_FORMAT, strtotime($datetime));
}

/**
 * Fungsi untuk truncate text
 */
function truncate_text($text, $length = 100) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

// ============================================
// 14. API RESPONSE HELPER
// ============================================

/**
 * Return JSON response
 */
function json_response($status, $message, $data = null) {
    header('Content-Type: application/json');
    
    $response = array(
        'status' => $status,
        'message' => $message
    );
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

/**
 * Return success response
 */
function success_response($message, $data = null) {
    json_response('success', $message, $data);
}

/**
 * Return error response
 */
function error_response($message, $data = null) {
    json_response('error', $message, $data);
}

?>
