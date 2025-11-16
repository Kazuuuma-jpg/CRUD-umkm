<?php
session_start();

class Auth {
    public function logout() {
        // Hapus semua session
        session_unset();
        session_destroy();
        
        // Redirect kembali ke halaman login
        header("Location: login.php");
        exit;
    }
}

// Jalankan logout
$auth = new Auth();
$auth->logout();
?>
