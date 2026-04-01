<?php
/**
 * LANCAR Platform - Clean Landing Style
 * Fix: CSS Background-clip warning & Layout Refinement
 */

session_start();
require_once '../config/koneksi.php';

if (isset($_GET['reset'])) {
    unset($_SESSION['nis_siswa']);
    unset($_SESSION['kelas_siswa']);
    header('Location: dashboard.php');
    exit;
}

$nis = $_SESSION['nis_siswa'] ?? '';
$kelas = $_SESSION['kelas_siswa'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi_login'])) {
    $post_nis = $_POST['nis'];
    $post_kelas = $_POST['kelas'] ?? '';
    if (!empty($post_kelas)) {
        $cek = ambil_satu_data("SELECT * FROM siswa WHERE nis = '$post_nis' AND kelas = '$post_kelas'");
        if ($cek) {
            $_SESSION['nis_siswa'] = $post_nis;
            $_SESSION['kelas_siswa'] = $post_kelas;
            header("Location: dashboard.php"); exit;
        } else { $error = 'NIS atau Kelas salah!'; }
    }
}

$nis_dicari = (isset($_GET['cari_nis']) && !empty($_GET['cari_nis'])) ? $_GET['cari_nis'] : $nis;
$is_viewing_others = (isset($_GET['cari_nis']) && $_GET['cari_nis'] !== $nis);

$aspirasi_tampil = [];
if (!empty($nis_dicari)) {
    $query = "SELECT ia.*, a.id_aspirasi, a.status, k.ket_kategori 
              FROM input_aspirasi ia
              INNER JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
              LEFT JOIN kategori k ON ia.id_kategori = k.id_kategori
              WHERE ia.nis = '$nis_dicari' ORDER BY ia.id_pelaporan DESC";
    $aspirasi_tampil = ambil_data($query);
}

$kategori = ambil_data("SELECT * FROM kategori ORDER BY ket_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LANCAR | Platform Aspirasi Modern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1; --secondary: #ec4899; --accent: #f59e0b;
            --dark: #0f172a; --light: #f8fafc;
        }

        body { font-family: 'Outfit', sans-serif; background-color: var(--light); color: var(--dark); scroll-behavior: smooth; }
        h1, h2, h3, .navbar-brand { font-family: 'Space Grotesk', sans-serif; font-weight: 700; }

        /* Navigation */
        .navbar { background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,0,0.05); padding: 15px 0; }
        .btn-admin { 
            border: 2px solid var(--accent); color: var(--accent); border-radius: 12px; 
            padding: 8px 20px; transition: 0.3s; font-weight: 600;
        }
        .btn-admin:hover { background: var(--accent); color: white; transform: scale(1.05); }

        /* Hero Landing */
        .hero-section {
            background: radial-gradient(circle at top right, #eef2ff, #fdf2f8);
            padding: 100px 0 60px; position: relative; overflow: hidden;
        }

        /* PERBAIKAN WARNING BACKGROUND-CLIP */
        .hero-title { 
            font-size: 4rem; 
            line-height: 1.1; 
            margin-bottom: 25px; 
            color: var(--primary); /* Fallback color */
            background: linear-gradient(to right, var(--primary), var(--secondary)); 
            background-clip: text; 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }
        
        /* Features Section */
        .feat-box {
            padding: 30px; border-radius: 30px; background: white; height: 100%;
            transition: 0.4s; border: 1px solid #f1f5f9;
        }
        .feat-box:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .feat-icon { width: 50px; height: 50px; background: #f5f3ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--primary); margin-bottom: 20px; }

        /* Modern Form & Tables */
        .glass-card {
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);
            border-radius: 32px; border: 1px solid white; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }
        .form-control, .form-select { border-radius: 14px; padding: 15px; border: 2px solid #f1f5f9; background: #f8fafc; }
        .btn-main { background: var(--dark); color: white; border-radius: 14px; padding: 15px 30px; border: none; font-weight: 600; transition: 0.3s; }
        .btn-main:hover { background: var(--primary); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); }

        /* Status Badges */
        .st-badge { padding: 6px 16px; border-radius: 10px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; }
        .st-1 { background: #fffbeb; color: #d97706; }
        .st-2 { background: #eff6ff; color: #2563eb; }
        .st-3 { background: #f0fdf4; color: #16a34a; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand text-dark" href="dashboard.php">
                <span class="text-primary">LAN</span>CAR.
            </a>
            <div class="ms-auto d-flex gap-3 align-items-center">
                <?php if($nis): ?>
                    <span class="fw-bold small d-none d-md-block"><i class="far fa-user-circle me-1"></i> <?php echo $nis; ?></span>
                    <a href="?reset=1" class="btn btn-dark btn-sm rounded-pill px-4">Logout</a>
                <?php else: ?>
                    <a href="../login.php" class="btn-admin text-decoration-none">
                        <i class="fas fa-user-shield me-2"></i>Admin Area
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if(!$nis): ?>
    <header class="hero-section">
        <div class="container text-center">
            <h1 class="hero-title">Sampaikan Suaramu<br>Untuk Sekolah Terbaik.</h1>
            <p class="lead text-muted mb-5 mx-auto" style="max-width: 600px;">
                Platform digital resmi untuk mengawal perbaikan sarana sekolah. Cepat, transparan, dan terintegrasi langsung ke manajemen.
            </p>
            <a href="#login-area" class="btn btn-main btn-lg px-5 shadow-lg">Mulai Melapor Sekarang <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </header>

    <section class="container py-5" id="login-area">
        <div class="row g-5">
            <div class="col-lg-7">
                <h2 class="mb-4 text-dark">Kenapa Memakai LANCAR?</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="feat-box">
                            <div class="feat-icon"><i class="fas fa-bolt"></i></div>
                            <h5 class="fw-bold">Lapor Instant</h5>
                            <p class="small text-muted mb-0">Hanya perlu NIS dan Kelas, tidak perlu pusing buat akun baru.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feat-box">
                            <div class="feat-icon"><i class="fas fa-eye"></i></div>
                            <h5 class="fw-bold">Transparan</h5>
                            <p class="small text-muted mb-0">Pantau status laporanmu secara real-time kapanpun dibutuhkan.</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="feat-box">
                            <div class="feat-icon"><i class="fas fa-headset"></i></div>
                            <h5 class="fw-bold">Respons Cepat</h5>
                            <p class="small text-muted mb-0">Laporan langsung diteruskan ke bagian sarana prasarana untuk penanganan prioritas agar segera ditindaklanjuti.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="glass-card p-5 border-top border-5 border-primary">
                    <h3 class="mb-4">Masuk Sistem</h3>
                    <form method="POST">
                        <input type="hidden" name="aksi_login" value="1">
                        <div class="mb-3">
                            <label class="fw-bold mb-2">NIS Kamu</label>
                            <input type="text" name="nis" id="n_in" class="form-control" onchange="getKelas('n_in','k_in')" placeholder="Masukkan NIS..." required>
                        </div>
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Kelas</label>
                            <input type="text" name="kelas" id="k_in" class="form-control" readonly placeholder="..." required>
                        </div>
                        <button type="submit" class="btn-main w-100 py-3">Buka Dashboard <i class="fas fa-rocket ms-2"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php else: ?>
    <div class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="mb-2 text-dark">Halo, Siswa Aktif! 👋</h1>
                    <p class="lead text-muted">Ayo jaga sekolah kita bersama. Laporkan setiap kendala sarana agar segera kami perbaiki.</p>
                </div>
                <div class="col-lg-5">
                    <div class="glass-card p-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-search me-2"></i>Lacak Aspirasi Teman</h6>
                        <form method="GET">
                            <div class="input-group">
                                <input type="text" name="cari_nis" class="form-control" placeholder="Masukkan NIS lain...">
                                <button class="btn btn-dark px-3" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                            <?php if($is_viewing_others): ?>
                                <a href="dashboard.php" class="btn btn-link btn-sm text-danger mt-2 p-0 fw-bold">Tutup Pencarian</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="glass-card p-4 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-4">Buat Aspirasi Baru</h5>
                    <form action="../proses/proses.php" method="POST">
                        <input type="hidden" name="aksi" value="tambah_aspirasi">
                        <input type="hidden" name="nis" value="<?php echo $nis; ?>">
                        <div class="mb-3">
                            <select name="id_kategori" class="form-select" required>
                                <option value="">Pilih Kategori...</option>
                                <?php foreach($kategori as $k): ?>
                                    <option value="<?php echo $k['id_kategori']; ?>"><?php echo $k['ket_kategori']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="lokasi" class="form-control" placeholder="Lokasi Spesifik..." required>
                        </div>
                        <div class="mb-3">
                            <textarea name="ket" class="form-control" rows="4" placeholder="Jelaskan masalahnya..." required></textarea>
                        </div>
                        <button type="submit" class="btn-main w-100">Kirim Laporan <i class="fas fa-paper-plane ms-2"></i></button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="glass-card overflow-hidden">
                    <div class="p-4 bg-white border-bottom">
                        <h5 class="fw-bold mb-0"><?php echo $is_viewing_others ? 'Aspirasi NIS: '.$nis_dicari : 'Aspirasi Saya'; ?></h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Informasi</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($aspirasi_tampil): foreach($aspirasi_tampil as $row): 
                                    $st = strtolower($row['status']);
                                    $c = (strpos($st, 'selesai') !== false) ? '3' : ((strpos($st, 'proses') !== false) ? '2' : '1');
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?php echo $row['id_aspirasi']; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo $row['ket_kategori']; ?></div>
                                        <div class="small text-muted"><i class="fas fa-map-marker-alt me-1"></i> <?php echo $row['lokasi']; ?></div>
                                    </td>
                                    <td><span class="st-badge st-<?php echo $c; ?>"><?php echo $row['status']; ?></span></td>
                                    <td class="pe-4 text-end">
                                        <a href="detail.php?id=<?php echo $row['id_aspirasi']; ?>&nis=<?php echo $nis_dicari; ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold text-decoration-none">Detail</a>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada aspirasi tercatat.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function getKelas(n, k) {
            const val = document.getElementById(n).value;
            if(val) {
                fetch('../proses/get_kelas.php?nis=' + val)
                    .then(r => r.json()).then(d => { if(d.status==='success') document.getElementById(k).value = d.kelas; });
            }
        }
    </script>
</body>
</html>