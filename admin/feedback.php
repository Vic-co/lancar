<?php
/**
 * Halaman Tambah/Ubah Feedback - Admin (Luxury Version)
 */

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/koneksi.php';

$id_aspirasi = isset($_GET['id']) ? keamanan($_GET['id']) : 0;

// Ambil data aspirasi
$aspirasi = ambil_satu_data("SELECT * FROM aspirasi WHERE id_aspirasi = '$id_aspirasi'");

if (!$aspirasi) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feedback = keamanan($_POST['feedback']);
    
    if (empty($feedback)) {
        $error = 'Feedback tidak boleh kosong!';
    } else {
        $query = "UPDATE aspirasi SET feedback = '$feedback' WHERE id_aspirasi = '$id_aspirasi'";
        
        if (eksekusi_query($query)) {
            $_SESSION['pesan_sukses'] = 'Tanggapan untuk aspirasi #' . $id_aspirasi . ' berhasil disimpan!';
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Gagal menyimpan feedback!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback #<?php echo $id_aspirasi; ?> - Admin Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --bg: #f8fafc;
        }

        body {
            background-color: var(--bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }

        .header-bg {
            background: var(--primary-gradient);
            height: 300px;
            width: 100%;
            position: absolute;
            top: 0;
            z-index: -1;
            border-radius: 0 0 50px 50px;
        }

        .container { margin-top: 40px; position: relative; z-index: 10; }

        /* Admin Badge */
        .admin-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 8px 18px;
            border-radius: 50px;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
        }

        .admin-badge .dot {
            width: 8px; height: 8px;
            background-color: #4ade80;
            border-radius: 50%;
            margin-right: 10px;
            box-shadow: 0 0 10px #4ade80;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.7; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.7; }
        }

        .lux-card {
            background: rgba(255, 255, 255, 0.98);
            border: none;
            border-radius: 30px;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .feedback-header {
            padding: 40px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
            background: #fcfcfd;
        }

        .icon-box {
            width: 60px; height: 60px;
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
            border-radius: 20px;
            display: flex;
            align-items: center; justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.5rem;
        }

        .form-area { padding: 40px; }

        .previous-feedback {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 700;
            color: #64748b;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            border-radius: 20px;
            border: 2px solid #f1f5f9;
            padding: 20px;
            font-size: 15px;
            transition: 0.3s;
            background: #f8fafc;
        }

        .form-control:focus {
            background: #fff;
            border-color: #4f46e5;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.05);
        }

        .btn-submit {
            background: var(--primary-gradient);
            color: white; border: none;
            border-radius: 18px;
            padding: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
            transition: 0.4s;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-back-custom {
            background: #ffffff;
            color: #4f46e5;
            border: none;
            font-weight: 700;
            border-radius: 50px;
            padding: 8px 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <div class="header-bg"></div>

    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="dashboard.php" class="btn btn-back-custom">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
            <div class="admin-badge">
                <span class="dot"></span>
                ADMIN MODE
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="lux-card">
                    <div class="feedback-header">
                        <div class="icon-box">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <h4 class="fw-800 text-dark mb-1">Berikan Tanggapan</h4>
                        <p class="text-muted small mb-0">Memberikan informasi progres kepada Laporan <strong>#<?php echo $id_aspirasi; ?></strong></p>
                    </div>

                    <div class="form-area">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger border-0 rounded-4 mb-4 small fw-600">
                                <i class="fas fa-circle-exclamation me-2"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($aspirasi['feedback'])): ?>
                            <div class="previous-feedback">
                                <h6 class="fw-800 text-success small mb-2 uppercase"><i class="fas fa-history me-1"></i> Feedback Terakhir:</h6>
                                <p class="mb-0 text-dark-50 small italic">"<?php echo nl2br($aspirasi['feedback']); ?>"</p>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-4">
                                <label for="feedback" class="form-label">Tulis Umpan Balik Baru</label>
                                <textarea name="feedback" id="feedback" class="form-control" rows="6" 
                                          placeholder="Contoh: Terima kasih atas laporannya. Saat ini teknisi sedang menuju lokasi untuk pengecekan AC di ruang kelas..." required><?php echo $aspirasi['feedback']; ?></textarea>
                                <div class="mt-2 px-1">
                                    <small class="text-muted" style="font-size: 11px;">
                                        <i class="fas fa-info-circle me-1"></i> Gunakan bahasa yang sopan dan jelas agar siswa mudah memahami progres laporan mereka.
                                    </small>
                                </div>
                            </div>

                            <div class="d-grid gap-3 mt-5">
                                <button type="submit" class="btn btn-submit">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Tanggapan
                                </button>
                                <a href="dashboard.php" class="btn btn-link text-muted text-decoration-none fw-bold small text-center">
                                    Batalkan
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <p class="text-center mt-4 text-white-50 small">
                    ID Laporan: <strong>#<?php echo $id_aspirasi; ?></strong> | Terkait NIS: <?php echo $aspirasi['nis']; ?>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>