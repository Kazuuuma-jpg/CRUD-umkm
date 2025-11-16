<?php
require_once "Database.php";

class LaporanService {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getLaporanBulanan($bulan) {
        // Validasi format bulan (YYYY-MM)
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            return ["error" => "Format bulan tidak valid (harus YYYY-MM)."];
        }

        $year = (int) substr($bulan, 0, 4);
        $month = (int) substr($bulan, 5, 2);

        $sql = "
            SELECT id_transaksi, tanggal, nama_pembeli, metode_pembayaran, status, total
            FROM transaksi 
            WHERE YEAR(tanggal) = ? AND MONTH(tanggal) = ?
            ORDER BY tanggal DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }
}

// === MAIN EXECUTION ===
if (!isset($_GET['bulan'])) {
    echo json_encode(["error" => "Parameter bulan tidak ditemukan."]);
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$laporan = new LaporanService($conn);
$data = $laporan->getLaporanBulanan($_GET['bulan']);

echo json_encode($data);

$db->close();
?>
