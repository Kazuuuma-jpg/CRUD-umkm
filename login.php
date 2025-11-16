<?php
session_start();
require_once 'koneksi.php';

$err_msg = "";

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $err_msg = "Username dan password harus diisi.";
    } else {
        $sql = "SELECT id_admin, username, password, nama_lengkap FROM admin WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id_admin, $db_username, $db_password, $nama_lengkap);
        $found = mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($found && $password === $db_password) {
            $_SESSION['admin_id'] = $id_admin;
            $_SESSION['admin_username'] = $db_username;
            $_SESSION['admin_name'] = $nama_lengkap;

            header("Location: index.php");
            exit;
        } else {
            $err_msg = "Username atau password salah.";
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Admin - Mie Setan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background-image: url("bg-register.jpeg"); 
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  margin: 0;
  font-family: 'Poppins', sans-serif;
}

body::before {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  z-index: 0;
}

.card {
  border-radius: 15px;
  position: relative;
  z-index: 1;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: white;
}

.card input {
  background: rgba(255, 255, 255, 0.25);
  border: none;
  color: #fff;
}

.card input::placeholder {
  color: #f1f1f1;
}

.btn-danger {
  background: rgba(255, 0, 0, 0.8);
  border: none;
  transition: 0.3s;
}

.btn-danger:hover {
  background: rgba(255, 0, 0, 1);
}

.text-danger {
  color: #ff4444 !important;
}
</style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
  <div class="col-12 col-md-8 col-lg-5">
    <div class="card shadow-lg border-0 p-4">
      <div class="card-body">
        <h3 class="card-title mb-3 text-center fw-bold text-danger">Login Admin</h3>
        <p class="text-center text-light mb-4">Masukkan username dan password Anda</p>

        <?php if ($err_msg): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($err_msg) ?></div>
        <?php endif; ?>

        <form method="post">
          <div class="mb-3">
            <label class="form-label fw-semibold text-light">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold text-light">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-danger w-100 mt-3">Masuk</button>
        </form>

        <div class="text-center mt-3">
          <small class="text-light">Belum punya akun? 
            <a href="register.php" class="text-decoration-none text-danger fw-semibold">Daftar di sini</a>
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
