<?php
/**
 * LANCAR Platform - Detail Aspirasi (Student View)
 * Tema: Clean Landing Style - Subtitle NIS
 */

session_start();
require_once '../config/koneksi.php';

$id_aspirasi = isset($_GET['id']) ? keamanan($_GET['id']) : 0;
$nis = isset($_GET['nis']) ? keamanan($_GET['nis']) : 0;

// Ambil data aspirasi
$query = "SELECT ia.id_pelaporan, a.id_aspirasi, a.status, a.feedback,
                 ia.lokasi, ia.ket, ia.nis,
                 k.ket_kategori, s.kelas
          FROM input_aspirasi ia
          INNER JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
          LEFT JOIN kategori k ON ia.id_kategori = k.id_kategori
          LEFT JOIN siswa s ON ia.nis = s.nis
          WHERE a.id_aspirasi = '$id_aspirasi' AND ia.nis = '$nis'";

$aspirasi = ambil_satu_data($query);

if (!$aspirasi) {
    header('Location: dashboard.php');
    exit;
}

// Logika badge warna
$st = strtolower($aspirasi['status']);
$badge_class = (strpos($st, 'selesai') !== false) ? '3' : ((strpos($st, 'proses') !== false) ? '2' : '1');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aspirasi | LANCAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1; --secondary: #ec4899; --accent: #f59e0b;
            --dark: #0f172a; --light: #f8fafc;
        }

        body { font-family: 'Outfit', sans-serif; background-color: var(--light); color: var(--dark); padding-bottom: 50px; }
        h1, h2, h3, .navbar-brand { font-family: 'Space Grotesk', sans-serif; font-weight: 700; }

        .navbar { background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,0,0.05); padding: 15px 0; }
        
        .hero-mini {
            background: radial-gradient(circle at top right, #eef2ff, #fdf2f8);
            padding: 60px 0; border-bottom: 1px solid rgba(0,0,0,0.03);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);
            border-radius: 32px; border: 1px solid white; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }

        .st-badge { padding: 8px 20px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; display: inline-block; }
        .st-1 { background: #fffbeb; color: #d97706; } 
        .st-2 { background: #eff6ff; color: #2563eb; } 
        .st-3 { background: #f0fdf4; color: #16a34a; } 

        .label-muted { color: #64748b; font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .content-text { font-size: 1.1rem; color: var(--dark); margin-bottom: 25px; }
        
        .feedback-box {
            background: #f8fafc; border-radius: 20px; border-left: 5px solid var(--primary); padding: 25px;
        }

        .btn-back {
            background: var(--dark); color: white; border-radius: 14px; padding: 12px 25px; 
            border: none; font-weight: 600; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center;
        }
        .btn-back:hover { background: var(--primary); color: white; transform: translateX(-5px); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container text-center">
            <a class="navbar-brand text-dark mx-auto" href="dashboard.php">
                <span class="text-primary">LAN</span>CAR.
            </a>
        </div>
    </nav>

    <header class="hero-mini">
        <div class="container text-center">
            <h2 class="mb-2">Detail Pengaduan</h2>
            <p class="text-muted"><i class="far fa-user-circle me-1"></i> Pelapor: <strong><?php echo $aspirasi['nis']; ?></strong></p>
        </div>
    </header>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="mb-4">
                    <a href="dashboard.php" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Riwayat
                    </a>
                </div>

                <div class="glass-card p-4 p-md-5">
                    <div class="row mb-5">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="label-muted">Status Saat Ini</div>
                            <span class="st-badge st-<?php echo $badge_class; ?>">
                                <i class="fas fa-circle me-2 small"></i> <?php echo $aspirasi['status']; ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="label-muted">Kategori Masalah</div>
                            <div class="content-text fw-bold text-primary">
                                <i class="fas fa-tag me-2"></i> <?php echo !empty($aspirasi['ket_kategori']) ? $aspirasi['ket_kategori'] : '-'; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="label-muted">Lokasi Kejadian</div>
                        <div class="content-text">
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i> <strong><?php echo $aspirasi['lokasi']; ?></strong>
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="label-muted">Deskripsi Laporan</div>
                        <div class="content-text p-3 bg-light rounded-4">
                            <?php echo nl2br($aspirasi['ket']); ?>
                        </div>
                    </div>

                    <hr class="my-5 opacity-25">

                    <div class="mb-4">
                        <h5 class="fw-bold mb-4"><i class="fas fa-comment-dots me-2 text-primary"></i> Respon Petugas</h5>
                        <?php if (!empty($aspirasi['feedback'])): ?>
                            <div class="feedback-box">
                                <div class="label-muted mb-2 text-primary">Catatan Admin:</div>
                                <p class="mb-0 lead" style="font-size: 1rem;"><?php echo nl2br($aspirasi['feedback']); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 border rounded-4 border-dashed">
                                <i class="fas fa-clock fa-2x text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0 italic">Belum ada feedback dari admin. Laporan Anda sedang dalam antrean peninjauan.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>