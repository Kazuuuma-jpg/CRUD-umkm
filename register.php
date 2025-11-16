<?php
require_once "koneksi.php";
session_start();

class Admin {
    private $conn;
    private $table = "admin";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function usernameExists($username, $email) {
        $sql = "SELECT id_admin FROM {$this->table} WHERE username=? OR email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function register($username, $password, $nama_lengkap, $email) {
        $sql = "INSERT INTO {$this->table} (username, password, nama_lengkap, email, last_login) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $password, $nama_lengkap, $email);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}

$err_msg = "";
$success_msg = "";

// Jika sudah login
if (isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Proses registrasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($nama_lengkap === '' || $email === '' || $username === '' || $password === '') {
        $err_msg = "Semua field harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err_msg = "Format email tidak valid.";
    } elseif ($password !== $confirm) {
        $err_msg = "Konfirmasi password tidak cocok.";
    } else {
        $admin = new Admin($conn);

        if ($admin->usernameExists($username, $email)) {
            $err_msg = "Username atau email sudah digunakan.";
        } else {
            if ($admin->register($username, $password, $nama_lengkap, $email)) {
                $success_msg = "Pendaftaran berhasil! Silakan <a href='login.php' class='text-blue-600 font-semibold'>login</a>.";
            } else {
                $err_msg = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Admin - Mie Setan</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center h-screen bg-cover bg-center bg-no-repeat" 
      style="background-image: url('bg-register.jpeg');">

<div class="bg-black/40 backdrop-blur-md p-8 rounded-xl shadow-2xl w-full max-w-md">
    <h1 class="text-2xl font-bold mb-6 text-center text-white hover:text-red-300 transition">
        Register Admin
    </h1>

    <?php if($err_msg): ?>
        <div class="bg-red-500/30 text-white p-3 rounded mb-4"><?= $err_msg ?></div>
    <?php endif; ?>

    <?php if($success_msg): ?>
        <div class="bg-green-500/30 text-white p-3 rounded mb-4"><?= $success_msg ?></div>
    <?php endif; ?>

        <form method="POST" class="space-y-4">
            <input type="text" name="nama_lengkap" placeholder="Nama Lengkap"
                class="w-full p-3 border border-white/30 text-white placeholder-white/80 rounded bg-white/10 focus:bg-white/20 focus:border-white/60 focus:outline-none transition" required>

            <input type="email" name="email" placeholder="Email"
                class="w-full p-3 border border-white/30 text-white placeholder-white/80 rounded bg-white/10 focus:bg-white/20 focus:border-white/60 focus:outline-none transition" required>

            <input type="text" name="username" placeholder="Username"
                class="w-full p-3 border border-white/30 text-white placeholder-white/80 rounded bg-white/10 focus:bg-white/20 focus:border-white/60 focus:outline-none transition" required>

            <input type="password" name="password" placeholder="Password"
                class="w-full p-3 border border-white/30 text-white placeholder-white/80 rounded bg-white/10 focus:bg-white/20 focus:border-white/60 focus:outline-none transition" required>

            <input type="password" name="confirm_password" placeholder="Konfirmasi Password"
                class="w-full p-3 border border-white/30 text-white placeholder-white/80 rounded bg-white/10 focus:bg-white/20 focus:border-white/60 focus:outline-none transition" required>

            <button type="submit"
                class="w-full bg-red-600/80 text-white p-3 rounded font-bold hover:bg-blue-700/90 transition">
                Daftar
            </button>
        </form>

    <p class="mt-4 text-sm text-white text-center">
        Sudah punya akun? <a href="login.php" class="text-white font-semibold underline hover:text-red-300">Login</a>
    </p>
</div>


</body>
</html>
