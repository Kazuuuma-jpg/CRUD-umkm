<?php
require_once "Database.php";

class LaporanPenjualan {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTransaksiByBulan($bulan) {
        $year = substr($bulan, 0, 4);
        $month = substr($bulan, 5, 2);

        $stmt = $this->conn->prepare("
            SELECT id_transaksi, tanggal, total, nama_pembeli, metode_pembayaran, status
            FROM transaksi 
            WHERE YEAR(tanggal) = ? AND MONTH(tanggal) = ?
        ");
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function logExport($nama_admin, $bulan) {
        // Cek apakah tabel log_export sudah ada
        $check = $this->conn->query("SHOW TABLES LIKE 'log_export'");
        if ($check->num_rows == 0) {
            $create = "
                CREATE TABLE log_export (
                    id_log INT AUTO_INCREMENT PRIMARY KEY,
                    nama_admin VARCHAR(50),
                    bulan_laporan VARCHAR(7),
                    waktu_export DATETIME
                )
            ";
            $this->conn->query($create);
        }

        // Simpan log
        $stmt = $this->conn->prepare("
            INSERT INTO log_export (nama_admin, bulan_laporan, waktu_export)
            VALUES (?, ?, NOW())
        ");
        $stmt->bind_param("ss", $nama_admin, $bulan);
        $stmt->execute();
    }
}

// --- MAIN PROCESS ---
if (!isset($_GET['bulan'])) {
    die("Parameter bulan tidak ditemukan.");
}

$bulan = $_GET['bulan'];
$nama_admin = "Admin"; // Bisa diganti dari session

$db = new Database();
$conn = $db->getConnection();

$laporan = new LaporanPenjualan($conn);

// Ambil data transaksi
$result = $laporan->getTransaksiByBulan($bulan);

// Simpan log export
$laporan->logExport($nama_admin, $bulan);

// Header agar browser langsung download sebagai file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_penjualan_$bulan.xls");

// Header kolom Excel
echo "ID Transaksi\tTanggal\tTotal\tNama Pembeli\tMetode Pembayaran\tStatus\n";

$total_semua = 0;
while ($row = $result->fetch_assoc()) {
    echo "{$row['id_transaksi']}\t{$row['tanggal']}\t{$row['total']}\t{$row['nama_pembeli']}\t{$row['metode_pembayaran']}\t{$row['status']}\n";
    $total_semua += $row['total'];
}

// Tambahkan total akhir
echo "\n\t\tTOTAL\t\t\t$total_semua\n";

$db->close();
?>
