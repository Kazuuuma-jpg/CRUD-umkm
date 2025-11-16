<?php
require_once "Database.php";

class Menu {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function updateMenu($id_menu, $nama, $harga, $kategori, $stok) {
        $stmt = $this->conn->prepare(
            "UPDATE menu 
             SET nama_menu = ?, Harga = ?, kategori = ?, stok = ?
             WHERE id_menu = ?"
        );
        $stmt->bind_param("sdsii", $nama, $harga, $kategori, $stok, $id_menu);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error: " . $stmt->error;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    $menu = new Menu($conn);

    $id_menu  = $_POST['id_menu'];
    $nama     = $_POST['nama_menu'];
    $harga    = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $stok     = $_POST['stok'];

    echo $menu->updateMenu($id_menu, $nama, $harga, $kategori, $stok);

    $db->close();
} else {
    echo "method bukan POST";
}
?>
