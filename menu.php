<?php
require_once "koneksi.php";

class Menu {
    private $conn;
    private $table = "menu";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY id_menu DESC";
        $result = $this->conn->query($query);

        if (!$result) {
            die(json_encode(["error" => $this->conn->error]));
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}

// --- Eksekusi ---
$menu = new Menu($conn);
echo json_encode($menu->getAll());
?>



