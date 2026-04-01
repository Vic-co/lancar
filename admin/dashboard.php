<?php
/**
 * Dashboard Admin - Aplikasi Pengaduan Sarana Sekolah
 * Fitur: Filter Status/Kategori & Cek Riwayat Aspirasi Siswa
 */

session_start();

// Keamanan: Cek Login & Role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/koneksi.php';

/* ==========================================================================
   1. LOGIKA FILTER & PENGAMBILAN DATA UTAMA
   ========================================================================== */

$filter_status   = isset($_GET['status']) ? keamanan($_GET['status']) : '';
$filter_kategori = isset($_GET['kategori']) ? keamanan($_GET['kategori']) : '';

$query_aspirasi = "SELECT a.id_aspirasi, a.status, a.id_kategori, a.id_pelaporan, a.feedback,
                          ia.nis, ia.lokasi, ia.ket,
                          k.ket_kategori, s.kelas
                   FROM aspirasi a
                   INNER JOIN input_aspirasi ia ON a.id_pelaporan = ia.id_pelaporan
                   LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
                   LEFT JOIN siswa s ON ia.nis = s.nis
                   WHERE 1=1";

if (!empty($filter_status))   $query_aspirasi .= " AND a.status = '$filter_status'";
if (!empty($filter_kategori)) $query_aspirasi .= " AND a.id_kategori = '$filter_kategori'";

$query_aspirasi .= " ORDER BY a.id_aspirasi DESC";

$data_aspirasi = ambil_data($query_aspirasi);
$kategori      = ambil_data("SELECT * FROM kategori ORDER BY ket_kategori ASC");

/* ==========================================================================
   2. STATISTIK DASHBOARD
   ========================================================================== */

$total_aspirasi    = hitung_baris("SELECT * FROM aspirasi");
$aspirasi_menunggu = hitung_baris("SELECT * FROM aspirasi WHERE status='Menunggu'");
$aspirasi_proses   = hitung_baris("SELECT * FROM aspirasi WHERE status='Proses'");
$aspirasi_selesai  = hitung_baris("SELECT * FROM aspirasi WHERE status='Selesai'");

/* ==========================================================================
   3. CEK RIWAYAT SISWA (BY NIS)
   ========================================================================== */

$filter_nis = isset($_GET['nis']) ? keamanan($_GET['nis']) : '';
$data_riwayat = [];

if (!empty($filter_nis)) {
    $query_riwayat = "SELECT a.id_aspirasi, a.status, a.feedback, ia.lokasi, ia.ket, k.ket_kategori
                      FROM aspirasi a
                      INNER JOIN input_aspirasi ia ON a.id_pelaporan = ia.id_pelaporan
                      LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
                      WHERE ia.nis = '$filter_nis'
                      ORDER BY a.id_aspirasi DESC";
    $data_riwayat = ambil_data($query_riwayat);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Pengaduan Sekolah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root { --grad: linear-gradient(135deg, #4f46e5, #7c3aed); }
        
        body { 
            background: #f8fafc; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        
        .navbar { background: var(--grad); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        
        .welcome-section {
            background: var(--grad);
            color: #fff;
            padding: 50px 0 100px;
            text-align: center;
        }

        .stat-container { margin-top: -60px; }
        
        .lux-card, .data-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,.05);
            transition: transform 0.3s ease;
        }

        .table thead th {
            background: #f8fafc;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
        }

        .badge-status { 
            padding: 6px 12px; 
            border-radius: 8px; 
            font-size: 11px; 
            font-weight: 700; 
        }

        .bg-menunggu { background: #fff7ed; color: #c2410c; }
        .bg-proses { background: #eff6ff; color: #1d4ed8; }
        .bg-selesai { background: #f0fdf4; color: #15803d; }

        .btn-action {
            width: 35px; height: 35px; 
            border-radius: 8px;
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-action:hover { opacity: 0.8; transform: translateY(-2px); }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark mb-0">
        <div class="container">
            <span class="navbar-brand fw-bold"><i class="fas fa-headset me-2"></i>ADMIN PANEL</span>
            <a href="../logout.php" class="btn btn-light btn-sm fw-bold px-3">
                <i class="fas fa-power-off me-1"></i> Logout
            </a>
        </div>
    </nav>

    <section class="welcome-section">
        <div class="container">
            <h2 class="fw-bold">Dashboard Pengaduan</h2>
            <p class="opacity-75">Sistem Informasi Pengelolaan Sarana Sekolah</p>
        </div>
    </section>

    <div class="container pb-5">

        <div class="row stat-container g-4">
            <?php
            $stat_items = [
                ['TOTAL', $total_aspirasi, 'primary', 'folder'],
                ['MENUNGGU', $aspirasi_menunggu, 'warning', 'clock'],
                ['PROSES', $aspirasi_proses, 'info', 'spinner'],
                ['SELESAI', $aspirasi_selesai, 'success', 'check']
            ];
            foreach($stat_items as $s):
            ?>
            <div class="col-6 col-md-3">
                <div class="lux-card text-center">
                    <i class="fas fa-<?= $s[3] ?> text-<?= $s[2] ?> mb-2 fs-4"></i><br>
                    <small class="fw-bold text-muted"><?= $s[0] ?></small>
                    <h3 class="fw-bold text-<?= $s[2] ?> mb-0"><?= $s[1] ?></h3>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-lg-8">
                <div class="lux-card h-100">
                    <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-filter me-2"></i>Filter Data</h6>
                    <form method="GET" class="row g-3">
                        <div class="col-md-5">
                            <label class="fw-bold small mb-1">Status Laporan</label>
                            <select name="status" class="form-select bg-light border-0">
                                <option value="">Semua Status</option>
                                <?php foreach(['Menunggu', 'Proses', 'Selesai'] as $st): ?>
                                    <option value="<?= $st ?>" <?= $filter_status == $st ? 'selected' : '' ?>><?= $st ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="fw-bold small mb-1">Kategori</label>
                            <select name="kategori" class="form-select bg-light border-0">
                                <option value="">Semua Kategori</option>
                                <?php foreach($kategori as $k): ?>
                                    <option value="<?= $k['id_kategori'] ?>" <?= $filter_kategori == $k['id_kategori'] ? 'selected' : '' ?>>
                                        <?= $k['ket_kategori'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100 fw-bold">Pilih</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="lux-card h-100">
                    <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-search me-2"></i>Cek Riwayat Siswa</h6>
                    <form method="GET">
                        <div class="input-group">
                            <input type="text" name="nis" value="<?= $filter_nis ?>" class="form-control bg-light border-0" placeholder="Masukkan NIS...">
                            <button class="btn btn-primary px-3"><i class="fas fa-search"></i></button>
                        </div>
                        <?php if(!empty($filter_nis)): ?>
                            <a href="dashboard.php" class="d-block mt-2 small text-decoration-none text-danger fw-bold">
                                <i class="fas fa-times-circle"></i> Reset Pencarian
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <?php if(!empty($filter_nis)): ?>
        <div class="data-card mt-4 border border-primary border-opacity-25">
            <h5 class="fw-bold mb-3"><i class="fas fa-history me-2 text-primary"></i>Riwayat Aspirasi: <?= htmlspecialchars($filter_nis) ?></h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th><th>Kategori</th><th>Lokasi</th><th>Status</th><th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data_riwayat)): ?>
                            <tr><td colspan="5" class="text-center py-4">Tidak ada riwayat pengaduan.</td></tr>
                        <?php else: foreach($data_riwayat as $r): ?>
                            <tr>
                                <td class="fw-bold">#<?= $r['id_aspirasi'] ?></td>
                                <td><?= $r['ket_kategori'] ?></td>
                                <td><?= $r['lokasi'] ?></td>
                                <td><span class="badge-status bg-<?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
                                <td><small class="text-muted"><?= $r['feedback'] ?: '<em>Belum ditanggapi</em>' ?></small></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <div class="data-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Daftar Aspirasi Masuk</h5>
                <span class="badge bg-primary rounded-pill"><?= count($data_aspirasi) ?> Laporan</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th><th>ID</th><th>Siswa</th><th>Kategori</th><th>Lokasi</th><th>Status</th><th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($data_aspirasi as $a): ?>
                        <tr>
                            <td class="text-muted"><?= $no++ ?></td>
                            <td class="fw-bold">#<?= $a['id_aspirasi'] ?></td>
                            <td>
                                <div class="fw-bold"><?= $a['nis'] ?></div>
                                <div class="small text-muted"><?= $a['kelas'] ?></div>
                            </td>
                            <td><?= $a['ket_kategori'] ?></td>
                            <td><?= $a['lokasi'] ?></td>
                            <td><span class="badge-status bg-<?= strtolower($a['status']) ?>"><?= $a['status'] ?></span></td>
                            <td class="text-center">
                                <a href="detail.php?id=<?= $a['id_aspirasi'] ?>" class="btn-action bg-info bg-opacity-10 text-info" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                <a href="ubah_status.php?id=<?= $a['id_aspirasi'] ?>" class="btn-action bg-warning bg-opacity-10 text-warning" title="Ubah Status"><i class="fas fa-edit"></i></a>
                                <a href="feedback.php?id=<?= $a['id_aspirasi'] ?>" class="btn-action bg-success bg-opacity-10 text-success" title="Beri Feedback"><i class="fas fa-comment"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($data_aspirasi)): ?>
                            <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada data laporan masuk.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>