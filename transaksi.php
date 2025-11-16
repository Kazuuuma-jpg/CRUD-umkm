<?php
session_start();
require_once "database.php";

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
  echo "<script>alert('Sesi Anda sudah berakhir, silakan login kembali.'); window.location='login.php';</script>";
  exit;
}

// Tentukan halaman aktif untuk sidebar
$current_page = basename($_SERVER['PHP_SELF']);

class Transaksi {
  private $conn;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function tambahTransaksi($tanggal, $total, $nama_pembeli, $metode, $status) {
    $stmt = $this->conn->prepare("INSERT INTO transaksi (tanggal, total, nama_pembeli, metode_pembayaran, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $tanggal, $total, $nama_pembeli, $metode, $status);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
  }
}

$database = new Database();
$conn = $database->getConnection();
$transaksi = new Transaksi($conn);

// Proses simpan transaksi
if (isset($_POST['submit'])) {
  $tanggal = $_POST['tanggal'];
  $total = $_POST['total'];
  $nama_pembeli = $_POST['nama_pembeli'];
  $metode = $_POST['metode_pembayaran'];
  $status = $_POST['status'];

  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

  if ($transaksi->tambahTransaksi($tanggal, $total, $nama_pembeli, $metode, $status)) {
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'success',
          title: 'Data berhasil disimpan!',
          showConfirmButton: false,
          timer: 1500
        }).then(() => window.location = 'transaksi.php');
      });
    </script>";
  } else {
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'error',
          title: 'Gagal menyimpan data!',
          text: 'Periksa kembali inputan Anda.'
        });
      });
    </script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Transaksi - Dashboard Mie Setan</title>

   <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f7f7f7; margin:0; }

    /* Sidebar */
    .sidebar {
      background-color: #5d4283ff;
      color: white;
      min-height: 100vh;
      min-width: 260px;
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
    }
    .sidebar .logo {
      color: #e56d3c;
      font-weight: bold;
      font-size: 1.5rem;
      text-align: center;
      margin-bottom: 2rem;
      border-bottom: 1px solid #555;
      padding-bottom: 1rem;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 15px;
      border-radius: 8px;
      white-space: nowrap;
      margin-bottom: 0.3rem;
      transition: background-color 0.2s;
    }
    .sidebar a:hover {
      background-color: rgba(229, 109, 60, 0.3);
    }
    .sidebar a.active {
        background-color: rgba(229, 109, 60, 0.3); /* warna latar menu aktif */
    }


    /* Main layout */
    .content-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
    header {
      background-color: #fff;
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 10;
    }
    main { flex-grow: 1; overflow-y: auto; padding: 2rem; }
    .card-custom { border: 1px solid #eee; border-radius: 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
    .primary-bg { background-color: #e56d3c; color:white; }
  </style>
</head>

<body class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar d-none d-md-flex flex-column shadow">
    <div class="logo">Mie DASH</div>
    <nav class="nav flex-column gap-2">
      <a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">Dashboard</a>
      <a href="index.php" class="<?= ($current_page == 'index.php') ? '' : '' ?>">Menu</a>
      <a href="laporan.php" class="<?= ($current_page == 'laporan.php') ? 'active' : '' ?>">Laporan Penjualan</a>
      <a href="transaksi.php" class="<?= ($current_page == 'transaksi.php') ? 'active' : '' ?>">Transaksi</a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="flex-grow-1 d-flex flex-column">
    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center p-3 bg-white border-bottom shadow-sm sticky-top">
      <h1 class="h3 fw-bold text-dark m-0">Input Transaksi</h1>
      <div class="dropdown">
        <button class="btn btn-danger rounded-circle dropdown-toggle fw-bold" data-bs-toggle="dropdown" aria-expanded="false">Admin</button>
        <ul class="dropdown-menu dropdown-menu-end shadow">
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </header>

    <!-- Form Transaksi -->
    <main class="flex-grow-1 overflow-auto p-4">
      <section class="card card-custom p-4 mx-auto" style="max-width: 600px;">
        <h2 class="h4 fw-bold text-dark text-center mb-4">Form Transaksi Baru</h2>

        <form method="POST" class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Total (Rp)</label>
            <input type="number" name="total" class="form-control" required>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Nama Pembeli</label>
            <input type="text" name="nama_pembeli" class="form-control" required>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-select">
              <option value="Cash">Cash</option>
              <option value="QRIS">QRIS</option>
              <option value="Transfer">Transfer</option>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
              <option value="Lunas">Lunas</option>
              <option value="Belum Lunas">Belum Lunas</option>
            </select>
          </div>

          <div class="col-12">
            <button type="submit" name="submit" class="btn primary-bg text-white fw-bold w-100">Simpan Transaksi</button>
          </div>
        </form>
      </section>
    </main>
  </div>
</body>
</html>
