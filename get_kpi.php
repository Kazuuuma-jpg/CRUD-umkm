<?php
require_once "database.php";

class KPIService {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getDataHarian() {
        $sql = "
            SELECT 
                COALESCE(SUM(total), 0) AS total_harian,
                COUNT(*) AS pesanan_harian
            FROM transaksi 
            WHERE DATE(tanggal) = CURDATE()
        ";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : ['total_harian'=>0,'pesanan_harian'=>0];
    }

    public function getDataBulanan() {
        $sql = "
            SELECT 
                COALESCE(SUM(total), 0) AS total_bulanan
            FROM transaksi 
            WHERE MONTH(tanggal) = MONTH(CURDATE())
            AND YEAR(tanggal) = YEAR(CURDATE())
        ";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : ['total_bulanan'=>0];
    }
}

$db = new Database();
$conn = $db->getConnection();

$kpi = new KPIService($conn);
$data_harian = $kpi->getDataHarian();
$data_bulanan = $kpi->getDataBulanan();

echo json_encode([
    "total_harian" => (float)$data_harian['total_harian'],
    "pesanan_harian" => (int)$data_harian['pesanan_harian'],
    "total_bulanan" => (float)$data_bulanan['total_bulanan']
]);

$db->close();
?>
