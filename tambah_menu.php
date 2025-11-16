<?php
require_once "database.php";

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "error: method bukan POST";
    exit;
}

if (!isset($_POST['nama_menu'], $_POST['kategori'], $_POST['harga'], $_POST['stok'])) {
    echo "error: parameter tidak lengkap";
    exit;
}

$nama     = $conn->real_escape_string($_POST['nama_menu']);
$kategori = $conn->real_escape_string($_POST['kategori']);
$harga    = (int) $_POST['harga'];
$stok     = (int) $_POST['stok'];

$sql = "INSERT INTO menu (nama_menu, kategori, harga, stok)
        VALUES ('$nama', '$kategori', $harga, $stok)";

if ($conn->query($sql)) {
    echo "success";
} else {
    echo "error: " . $conn->error;
}
?>
