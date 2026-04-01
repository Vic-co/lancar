<?php
/**
 * Halaman Login Aplikasi Pengaduan Sarana Sekolah
 * Theme: Ultra-Luxury Matching Version
 */

session_start();

if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: siswa/dashboard.php');
    }
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config/koneksi.php';
    $username = keamanan($_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM admin WHERE username = '$username'";
    $result = eksekusi_query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['password'] == $password || password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
            header('Location: admin/dashboard.php');
            exit;
        } else {
            $error = 'Kombinasi password tidak valid.';
        }
    } else {
        $error = 'Username tidak ditemukan.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal - Sarana Sekolah</title>
    
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
            display: flex;
            align-items: center;
        }

        /* Top Accent Layer Matching */
        .page-header-box {
            background: var(--gradient);
            height: 350px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            border-radius: 0 0 60px 60px;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.2);
        }

        .container { position: relative; z-index: 10; }

        /* Admin Badge / Status Badge Matching */
        .system-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 8px 18px;
            border-radius: 50px;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .system-badge .dot {
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

        /* Main Login Card Matching */
        .login-main-card {
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

        /* Input Styling Matching */
        .form-label {
            font-weight: 700;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 10px;
            margin-left: 5px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            z-index: 10;
        }

        .form-control {
            padding: 16px 20px 16px 50px;
            border-radius: 20px;
            border: 2px solid #f1f5f9;
            background: #f8fafc;
            font-weight: 600;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.1);
        }

        /* Button Styling Matching */
        .btn-login-premium {
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 18px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: 0.4s;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }

        .btn-login-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-siswa-outline {
            border: 2px solid #f1f5f9;
            border-radius: 20px;
            padding: 14px;
            color: #64748b;
            font-weight: 700;
            transition: 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-siswa-outline:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        .alert-custom {
            background: #fff1f2;
            color: #e11d48;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <div class="page-header-box"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-7">
                
                <div class="text-center mb-4">
                    <div class="system-badge">
                        <span class="dot"></span>
                        SECURE ACCESS MODE
                    </div>
                </div>

                <div class="login-main-card">
                    <div class="p-4 p-md-5 text-center border-bottom bg-light bg-opacity-30">
                        <div class="mb-3">
                            <i class="fas fa-school fa-3x text-primary" style="filter: drop-shadow(0 10px 15px rgba(79,70,229,0.2));"></i>
                        </div>
                        <h3 class="fw-800 text-dark mb-1">Akses Masuk</h3>
                        <p class="text-muted small mb-0">Silakan autentikasi untuk mengelola laporan sarana sekolah.</p>
                    </div>

                    <div class="p-4 p-md-5">
                        <?php if ($error): ?>
                            <div class="alert alert-custom d-flex align-items-center mb-4" role="alert">
                                <i class="fas fa-circle-exclamation me-2"></i>
                                <div><?php echo $error; ?></div>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label text-uppercase">Username</label>
                                <div class="input-group-custom">
                                    <i class="fas fa-user-shield"></i>
                                    <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-uppercase">Password</label>
                                <div class="input-group-custom">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-login-premium">
                                    Masuk Ke Admin <i class="fas fa-arrow-right-to-bracket ms-2"></i>
                                </button>
                                
                                <div class="text-center my-3">
                                    <span class="text-muted small fw-600">ATAU</span>
                                </div>

                                <a href="siswa/dashboard.php" class="btn-siswa-outline">
                                    <i class="fas fa-user-graduate me-2"></i> Portal Siswa
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-white-50 small fw-600">
                        <i class="fas fa-shield-halved me-1"></i> 
                        Protected by Secure System &copy; 2026
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>