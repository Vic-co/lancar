<?php
/**
 * Halaman Detail Aspirasi - Admin (Luxury Animated Version)
 */

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/koneksi.php';

$id_aspirasi = isset($_GET['id']) ? keamanan($_GET['id']) : 0;

// Ambil data aspirasi
$query = "SELECT a.id_aspirasi, a.status, a.id_kategori, a.id_pelaporan, a.feedback, 
                 ia.id_pelaporan, ia.nis, ia.lokasi, ia.ket,
                 k.ket_kategori, s.kelas
          FROM aspirasi a
          INNER JOIN input_aspirasi ia ON a.id_pelaporan = ia.id_pelaporan
          LEFT JOIN kategori k ON ia.id_kategori = k.id_kategori
          LEFT JOIN siswa s ON ia.nis = s.nis
          WHERE a.id_aspirasi = '$id_aspirasi'";

$aspirasi = ambil_satu_data($query);

if (!$aspirasi) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail #<?php echo $aspirasi['id_aspirasi']; ?> - Pengaduan Sarana</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --body-bg: #f8fafc;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* ANIMASI */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-up {
            animation: fadeInUp 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        /* HEADER */
        .navbar {
            background: transparent !important;
            padding: 2rem 0;
            position: relative;
            z-index: 10;
        }

        .header-bg {
            background: var(--primary-gradient);
            height: 320px;
            width: 100%;
            position: absolute;
            top: 0;
            z-index: -1;
            border-radius: 0 0 50px 50px;
        }

        /* CARD STYLE */
        .detail-card {
            background: rgba(255, 255, 255, 0.98);
            border: none;
            border-radius: 35px;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-top: -50px;
        }

        .card-top-bar {
            padding: 30px 40px;
            border-bottom: 1px solid #f1f5f9;
            background: #fcfcfd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-label {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 8px;
        }

        .info-value {
            font-weight: 700;
            color: #1e293b;
            font-size: 16px;
        }

        .description-box {
            background: #f8fafc;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid #f1f5f9;
            transition: 0.3s;
        }
        .description-box:hover {
            border-color: #4f46e5;
            background: #ffffff;
        }

        .feedback-box {
            background: #f0fdf4;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid #dcfce7;
            position: relative;
        }

        /* BADGE LUXE */
        .badge-status {
            padding: 10px 20px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bg-menunggu { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
        .bg-proses { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .bg-selesai { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }

        /* BUTTONS */
        .btn-luxury {
            border-radius: 16px;
            padding: 14px 28px;
            font-weight: 800;
            transition: 0.4s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-luxury:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.12);
        }

        .btn-back-custom {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-weight: 700;
            border-radius: 50px;
            padding: 10px 25px;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn-back-custom:hover {
            background: white;
            color: #4f46e5;
        }
    </style>
</head>
<body>

    <div class="header-bg"></div>

    <nav class="navbar navbar-expand-lg">
        <div class="container d-flex justify-content-between">
            <a href="dashboard.php" class="btn-back-custom">
                <i class="fas fa-arrow-left me-2"></i> KEMBALI
            </a>
            <div class="text-white fw-800" style="letter-spacing: 2px; font-size: 0.8rem;">
                ADMIN MODE / DETAIL LAPORAN
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                
                <div class="detail-card animate-fade-up">
                    <div class="card-top-bar">
                        <div>
                            <div class="info-label mb-0">Tiket Laporan</div>
                            <h3 class="fw-800 mb-0 text-primary">#<?php echo $aspirasi['id_aspirasi']; ?></h3>
                        </div>
                        <div class="badge-status bg-<?php echo strtolower($aspirasi['status']); ?>">
                            <span class="pulse-dot"></span>
                            <?php echo strtoupper($aspirasi['status']); ?>
                        </div>
                    </div>

                    <div class="p-4 p-md-5">
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <div class="info-label">Pelapor</div>
                                <div class="info-value d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-3"><i class="fas fa-user text-primary"></i></div>
                                    <?php echo !empty($aspirasi['nis']) ? $aspirasi['nis'] : '-'; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Kelas</div>
                                <div class="info-value d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-3"><i class="fas fa-graduation-cap text-primary"></i></div>
                                    <?php echo !empty($aspirasi['kelas']) ? $aspirasi['kelas'] : '-'; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Kategori</div>
                                <div class="info-value d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-3"><i class="fas fa-tag text-primary"></i></div>
                                    <span class="badge bg-white text-dark border-0 shadow-sm px-3"><?php echo !empty($aspirasi['ket_kategori']) ? $aspirasi['ket_kategori'] : '-'; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="info-label">Lokasi Kejadian</div>
                            <div class="info-value text-danger fs-5">
                                <i class="fas fa-map-marker-alt me-2"></i><?php echo !empty($aspirasi['lokasi']) ? $aspirasi['lokasi'] : '-'; ?>
                            </div>
                        </div>

                        <hr class="opacity-10 my-5">

                        <div class="mb-5">
                            <h6 class="fw-800 mb-3"><i class="fas fa-align-left me-2 text-primary"></i>Isi Laporan Siswa</h6>
                            <div class="description-box">
                                <p class="mb-0 lh-lg"><?php echo !empty($aspirasi['ket']) ? nl2br($aspirasi['ket']) : '<em class="text-muted">Tidak ada deskripsi tambahan.</em>'; ?></p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-800 mb-3"><i class="fas fa-comment-dots me-2 text-success"></i>Tanggapan Admin</h6>
                            <div class="feedback-box">
                                <?php if(!empty($aspirasi['feedback'])): ?>
                                    <p class="mb-0 fw-600 text-dark"><?php echo nl2br($aspirasi['feedback']); ?></p>
                                <?php else: ?>
                                    <p class="mb-0 text-muted italic small"><i class="fas fa-info-circle me-1"></i> Belum ada tanggapan yang dikirimkan kepada pelapor.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-light border-top d-flex flex-wrap gap-3 justify-content-center">
                        <a href="ubah_status.php?id=<?php echo $aspirasi['id_aspirasi']; ?>" class="btn btn-warning btn-luxury text-white">
                            <i class="fas fa-sync-alt"></i> Update Status
                        </a>
                        <a href="feedback.php?id=<?php echo $aspirasi['id_aspirasi']; ?>" class="btn btn-primary btn-luxury" style="background: var(--primary-gradient)">
                            <i class="fas fa-pen-nib"></i> Edit Tanggapan
                        </a>
                    </div>
                </div>

                <p class="text-center mt-5 text-muted small animate-fade-up" style="animation-delay: 0.3s">
                    System ID: <span class="fw-bold">APP-LNC-<?php echo time(); ?></span>
                </p>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
//