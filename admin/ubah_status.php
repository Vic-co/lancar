<?php
/**
 * Halaman Ubah Status Aspirasi - Admin (Ultra-Luxury Full Version)
 */

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/koneksi.php';

$id_aspirasi = isset($_GET['id']) ? keamanan($_GET['id']) : 0;
$aspirasi = ambil_satu_data("SELECT * FROM aspirasi WHERE id_aspirasi = '$id_aspirasi'");

if (!$aspirasi) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status_baru = keamanan($_POST['status']);
    if (empty($status_baru)) {
        $error = 'Silakan pilih salah satu status.';
    } else {
        $query_update = "UPDATE aspirasi SET status = '$status_baru' WHERE id_aspirasi = '$id_aspirasi'";
        if (eksekusi_query($query_update)) {
            $_SESSION['pesan_sukses'] = "Status laporan #$id_aspirasi berhasil diperbarui!";
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Gagal memperbarui status ke database.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status #<?php echo $id_aspirasi; ?> - Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --bg: #f8fafc;
        }

        body {
            background-color: var(--bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Top Accent Layer */
        .page-header-box {
            background: var(--gradient);
            height: 320px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            border-radius: 0 0 60px 60px;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.2);
        }

        .container { margin-top: 40px; position: relative; z-index: 10; }

        /* Admin Badge Styling */
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .admin-badge .dot {
            width: 8px;
            height: 8px;
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

        /* Card Styling */
        .status-main-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 35px;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Status Selection Radio Styling */
        .status-option {
            position: relative;
            cursor: pointer;
            margin-bottom: 15px;
            display: block;
        }

        .status-option input {
            position: absolute;
            opacity: 0;
        }

        .status-content {
            padding: 22px;
            border: 2px solid #f1f5f9;
            border-radius: 22px;
            display: flex;
            align-items: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 18px;
            font-size: 1.3rem;
            transition: 0.3s;
        }

        .opt-menunggu .status-icon { background: #fff7ed; color: #f59e0b; }
        .opt-proses .status-icon { background: #eff6ff; color: #3b82f6; }
        .opt-selesai .status-icon { background: #f0fdf4; color: #10b981; }

        /* Checked State */
        .status-option input:checked + .status-content {
            border-color: var(--primary);
            background-color: #f5f3ff;
            transform: scale(1.03);
            box-shadow: 0 15px 30px -10px rgba(79, 70, 229, 0.2);
        }

        .status-option input:checked + .status-content::after {
            content: "\f058";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 25px;
            color: var(--primary);
            font-size: 1.4rem;
        }

        .btn-update-status {
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 20px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: 0.4s;
            margin-top: 15px;
        }

        .btn-update-status:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.4);
            color: white;
        }

        .btn-light-custom {
            background: #ffffff;
            color: var(--primary);
            border: none;
            font-weight: 700;
            border-radius: 50px;
            padding: 8px 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <div class="page-header-box"></div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="dashboard.php" class="btn btn-light-custom">
                        <i class="fas fa-arrow-left me-2"></i> Dashboard
                    </a>
                    <div class="admin-badge">
                        <span class="dot"></span>
                        ADMIN MODE
                    </div>
                </div>

                <div class="status-main-card">
                    <div class="p-4 p-md-5 text-center border-bottom bg-light bg-opacity-30">
                        <div class="mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-800">
                                <i class="fas fa-hashtag me-1"></i> ID LAPORAN: <?php echo $id_aspirasi; ?>
                            </span>
                        </div>
                        <h3 class="fw-800 text-dark mb-1">Update Status</h3>
                        <p class="text-muted small mb-0 px-lg-3">Pilih satu tahapan di bawah ini untuk memperbarui progres laporan sarana.</p>
                    </div>

                    <div class="p-4 p-md-5">
                        <?php if ($error): ?>
                            <div class="alert alert-danger border-0 rounded-4 mb-4 small fw-600">
                                <i class="fas fa-circle-exclamation me-2"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <label class="status-option opt-menunggu">
                                <input type="radio" name="status" value="Menunggu" <?php echo ($aspirasi['status'] == 'Menunggu') ? 'checked' : ''; ?>>
                                <div class="status-content">
                                    <div class="status-icon"><i class="fas fa-clock"></i></div>
                                    <div>
                                        <div class="fw-800 text-dark mb-0">Menunggu</div>
                                        <div class="text-muted small">Laporan baru & butuh verifikasi.</div>
                                    </div>
                                </div>
                            </label>

                            <label class="status-option opt-proses">
                                <input type="radio" name="status" value="Proses" <?php echo ($aspirasi['status'] == 'Proses') ? 'checked' : ''; ?>>
                                <div class="status-content">
                                    <div class="status-icon"><i class="fas fa-gears"></i></div>
                                    <div>
                                        <div class="fw-800 text-dark mb-0">Diproses</div>
                                        <div class="text-muted small">Petugas sedang memperbaiki sarana.</div>
                                    </div>
                                </div>
                            </label>

                            <label class="status-option opt-selesai">
                                <input type="radio" name="status" value="Selesai" <?php echo ($aspirasi['status'] == 'Selesai') ? 'checked' : ''; ?>>
                                <div class="status-content">
                                    <div class="status-icon"><i class="fas fa-circle-check"></i></div>
                                    <div>
                                        <div class="fw-800 text-dark mb-0">Selesai</div>
                                        <div class="text-muted small">Masalah tuntas & laporan ditutup.</div>
                                    </div>
                                </div>
                            </label>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-update-status">
                                    Simpan Perubahan
                                </button>
                                <a href="dashboard.php" class="btn btn-link text-muted mt-3 text-decoration-none fw-bold small">
                                    Batalkan Aksi
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-white-50 small fw-600">
                        <i class="fas fa-lock me-1"></i> Perubahan status akan tercatat secara otomatis
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>