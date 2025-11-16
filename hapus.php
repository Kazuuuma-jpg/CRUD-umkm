<?php
require_once "Database.php";

class MenuService {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function hapusMenu($id) {
        $sql = "DELETE FROM menu WHERE id_menu = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            return "success";
        } else {
            $error = $stmt->error;
            $stmt->close();
            return "error: " . $error;
        }
    }
}

// === MAIN EXECUTION ===
if (!isset($_GET['id'])) {
    echo "error: id tidak ditemukan";
    exit;
}

$id = intval($_GET['id']);

$db = new Database();
$conn = $db->getConnection();

$menu = new MenuService($conn);
echo $menu->hapusMenu($id);

$db->close();
?>
