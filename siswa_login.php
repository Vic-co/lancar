<?php
session_start();

// Jika sudah login, redirect ke dashboard siswa
if (isset($_SESSION['nis_siswa'])) {
    header('Location: siswa/dashboard.php');
    exit;
}

include 'config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis = keamanan($_POST['nis'] ?? '');
    $kelas = keamanan($_POST['kelas'] ?? '');

    // Validasi input
    if (empty($nis) || empty($kelas)) {
        $error = 'NIS dan Kelas harus diisi!';
    } else {
        // Cek data siswa di database
        $query = "SELECT * FROM siswa WHERE nis = '$nis' AND kelas = '$kelas'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Login berhasil
            $data = $result->fetch_assoc();
            $_SESSION['nis_siswa'] = $data['nis'];
            $_SESSION['kelas_siswa'] = $data['kelas'];
            $_SESSION['logged_in_siswa'] = true;

            // Redirect ke dashboard siswa
            header('Location: siswa/dashboard.php');
            exit;
        } else {
            $error = 'NIS atau Kelas tidak ditemukan!';
        }
    }
}

// Ambil daftar kelas unik dari database
$kelas_list = [];
$query_kelas = "SELECT DISTINCT kelas FROM siswa ORDER BY kelas";
$result_kelas = $conn->query($query_kelas);
while ($row = $result_kelas->fetch_assoc()) {
    $kelas_list[] = $row['kelas'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa - Aplikasi Pengaduan Sarana Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .login-header p {
            font-size: 14px;
            margin: 0;
            opacity: 0.9;
        }

        .login-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-back:hover {
            text-decoration: underline;
            color: #764ba2;
        }

        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .icon-group {
            text-align: center;
            margin-bottom: 20px;
        }

        .icon-group i {
            font-size: 48px;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="icon-group">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h1>Login Siswa</h1>
            <p>Aplikasi Pengaduan Sarana Sekolah</p>
        </div>

        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nis" class="form-label">
                        <i class="fas fa-id-card"></i> Nomor Induk Siswa (NIS)
                    </label>
                    <input type="text" class="form-control" id="nis" name="nis" 
                           placeholder="Masukkan NIS Anda" required autofocus>
                </div>

                <div class="form-group">
                    <label for="kelas" class="form-label">
                        <i class="fas fa-graduation-cap"></i> Kelas
                    </label>
                    <select class="form-control" id="kelas" name="kelas" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas_list as $k): ?>
                            <option value="<?php echo htmlspecialchars($k); ?>">
                                <?php echo htmlspecialchars($k); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <a href="login.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Login Admin
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
