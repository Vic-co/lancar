<?php
/**
 * Halaman Lihat Feedback - Siswa
 */

session_start();

require_once '../config/koneksi.php';

$id_aspirasi = isset($_GET['id']) ? keamanan($_GET['id']) : 0;

// Ambil data aspirasi
$aspirasi = ambil_satu_data("SELECT * FROM aspirasi WHERE id_aspirasi = '$id_aspirasi'");

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
    <title>Feedback Aspirasi - Pengaduan Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f7fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-bullhorn"></i> Pengaduan Sarana
            </a>
            <div class="ms-auto">
                <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="mb-3">
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-comment"></i> Feedback untuk Aspirasi #<?php echo $aspirasi['id_aspirasi']; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($aspirasi['feedback'])): ?>
                            <div class="alert alert-success">
                                <h5><i class="fas fa-check-circle"></i> Feedback dari Admin</h5>
                                <hr>
                                <p><?php echo nl2br($aspirasi['feedback']); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <h5><i class="fas fa-clock"></i> Feedback Belum Ada</h5>
                                <p>Admin belum memberikan feedback untuk aspirasi ini. Silakan tunggu atau hubungi admin untuk informasi lebih lanjut.</p>
                            </div>
                        <?php endif; ?>

                        <div class="text-center mt-4">
                            <a href="detail.php?id=<?php echo $aspirasi['id_aspirasi']; ?>" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Lihat Detail Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
