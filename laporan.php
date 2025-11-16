<?php include "koneksi.php"; 
$current_page = basename($_SERVER['PHP_SELF']);?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Penjualan - Mie Setan</title>

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
  <div class="content-area">

    <!-- Header -->
    <header>
      <h1 class="fs-3 fw-bold text-dark mb-0">Laporan Penjualan</h1>
      <div class="dropdown">
        <button class="btn btn-danger rounded-circle dropdown-toggle fw-bold" data-bs-toggle="dropdown" aria-expanded="false">Admin</button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </header>

    <!-- Main -->
    <main>
      <!-- Filter -->
      <section class="bg-white p-4 rounded-4 shadow-sm mb-4">
        <h2 class="fs-4 fw-bold text-dark border-bottom pb-2 mb-3">Filter Laporan</h2>
        <form id="filter-form" class="row g-3 align-items-end">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Pilih Bulan</label>
            <input type="month" id="bulan" name="bulan" required class="form-control border-secondary">
          </div>
          <div class="col-md-3">
            <button type="button" id="btn-export" class="btn btn-success fw-bold w-100">
              Export Excel
            </button>
          </div>
        </form>
      </section>

      <!-- Tabel -->
      <section class="bg-white p-4 rounded-4 shadow-sm">
        <h2 class="fs-4 fw-bold text-dark border-bottom pb-2 mb-3">Hasil Laporan Penjualan</h2>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-warning text-center text-dark">
              <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pembeli</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody id="laporan-body" class="text-center">
              <tr>
                <td colspan="6" class="py-3 text-muted">Pilih bulan untuk melihat laporan.</td>
              </tr>
            </tbody>
            <tfoot class="fw-bold bg-light">
              <tr>
                <td colspan="5" class="text-end px-3">Total Pemasukan:</td>
                <td id="total-pemasukan" class="text-end px-3">Rp 0</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </section>
    </main>
  </div>

  <script>
    const formatRupiah = (n) => new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', minimumFractionDigits:0 }).format(n);

    const bulanInput = document.getElementById('bulan');
    const tbody = document.getElementById('laporan-body');
    const totalPemasukan = document.getElementById('total-pemasukan');

    bulanInput.addEventListener('change', function() {
      const bulan = bulanInput.value;
      if (bulan) loadLaporan(bulan);
    });

    function loadLaporan(bulan) {
      fetch('get_laporan.php?bulan=' + bulan)
        .then(res => res.json())
        .then(data => {
          tbody.innerHTML = '';
          let total = 0;

          if (!data || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="py-3 text-muted">Tidak ada transaksi di bulan ini.</td></tr>`;
            totalPemasukan.textContent = 'Rp 0';
            return;
          }

          data.forEach(trx => {
            tbody.innerHTML += `
              <tr>
                <td>${trx.id_transaksi}</td>
                <td>${trx.tanggal}</td>
                <td>${trx.nama_pembeli}</td>
                <td>${trx.metode_pembayaran}</td>
                <td>${trx.status}</td>
                <td class="text-end">${formatRupiah(trx.total)}</td>
              </tr>`;
            total += parseFloat(trx.total);
          });

          totalPemasukan.textContent = formatRupiah(total);
        })
        .catch(err => {
          console.error('Gagal memuat laporan:', err);
          tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-3">Gagal memuat laporan.</td></tr>`;
        });
    }

    document.getElementById('btn-export').addEventListener('click', function() {
      const bulan = bulanInput.value;
      if (!bulan) { alert("Pilih bulan dulu sebelum export!"); return; }
      window.location.href = 'export_excel.php?bulan=' + bulan;
      loadLaporan(bulan);
    });
  </script>

</body>
</html>
