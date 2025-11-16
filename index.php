<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Mie Setan</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f7f7f7; margin:0; }
    .sidebar { background-color: #5d4283ff; color: white; min-height: 100vh; min-width: 260px; padding: 1.5rem; display: flex; flex-direction: column; }
    .sidebar .logo { color: #e56d3c; font-weight: bold; font-size: 1.5rem; text-align: center; margin-bottom: 2rem; border-bottom: 1px solid #d64848ff; padding-bottom: 1rem; }
    .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 15px; border-radius: 8px; white-space: nowrap; margin-bottom: 0.3rem; transition: background-color 0.2s; }
    .sidebar a:hover { background-color: rgba(229, 109, 60, 0.3); }
    .sidebar a.active { background-color: rgba(229, 109, 60, 0.3); }
    .content-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
    header { background-color: #fff; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 4px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 10; }
    main { flex-grow: 1; overflow-y: auto; padding: 2rem; }
    .card-custom { border: 1px solid #eee; border-radius: 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
    .primary-bg { background-color: #e56d3c; color:white; }
  </style>
</head>

<body class="d-flex">
  <div class="sidebar d-none d-md-flex flex-column shadow">
    <div class="logo">Mie DASH</div>
    <nav class="nav flex-column gap-2">
      <a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">Dashboard</a>
      <a href="#menu" class="">Menu</a>
      <a href="laporan.php" class="<?= ($current_page == 'laporan.php') ? 'active' : '' ?>">Laporan Penjualan</a>
      <a href="transaksi.php" class="<?= ($current_page == 'transaksi.php') ? 'active' : '' ?>">Transaksi</a>
    </nav>
  </div>
  
  <div class="content-area d-flex flex-column flex-grow-1">
    <header>
      <h1 class="h3 fw-bold text-dark m-0">Dashboard Mie Setan</h1>
      <div class="dropdown">
        <button class="btn btn-danger rounded-circle dropdown-toggle fw-bold" data-bs-toggle="dropdown" aria-expanded="false">Admin</button>
        <ul class="dropdown-menu dropdown-menu-end shadow">
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </header>

    <main>
      <section id="overview" class="mb-5">
        <h2 class="h4 fw-bold text-dark border-bottom pb-2 mb-3">Ringkasan Hari Ini</h2>
        <div id="kpi-cards" class="row g-3"></div>
      </section>

      <section id="menu">
        <h2 class="h4 fw-bold text-dark border-bottom pb-2 mb-3">Manajemen Menu</h2>

        <div class="card card-custom mb-4">
          <div class="card-body">
            <h5 class="fw-semibold mb-3 text-dark">Tambah Menu Baru</h5>
            <form id="add-menu-form" class="row g-3">
              <div class="col-md-3">
                <input type="text" id="menu-name" class="form-control" placeholder="Nama Menu" required>
              </div>
              <div class="col-md-2">
                <input type="text" id="menu-category" class="form-control" placeholder="Kategori" required>
              </div>
              <div class="col-md-2">
                <input type="number" id="menu-price" class="form-control" placeholder="Harga (IDR)" required>
              </div>
              <div class="col-md-2">
                <input type="number" id="menu-stock" class="form-control" placeholder="Stok" required>
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn w-100 fw-bold primary-bg">Tambah Menu</button>
              </div>
            </form>
          </div>
        </div>

        <div class="card card-custom">
          <div class="card-body">
            <h5 class="fw-semibold mb-3 text-dark">Daftar Menu</h5>
            <div id="menu-list-container" class="table-responsive"></div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content rounded-4">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="editModalLabel">Edit Menu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="edit-form" class="row g-3">
            <input type="hidden" id="edit-id-menu">
            <div class="col-12">
              <label class="form-label fw-semibold">Nama Menu</label>
              <input type="text" id="edit-nama-menu" class="form-control" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Harga</label>
              <input type="number" id="edit-harga" class="form-control" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Kategori</label>
              <input type="text" id="edit-kategori" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Stok</label>
              <input type="number" id="edit-stok" class="form-control">
            </div>
            <div class="col-12 text-end mt-3">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    const formatRupiah = n => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n || 0);

    function loadKpiData() {
      fetch('get_kpi.php')
        .then(res => res.json())
        .then(data => {
          document.getElementById('kpi-cards').innerHTML = `
            <div class="col-lg-4 col-md-6">
              <div class="card card-custom p-3 bg-light">
                <p class="text-muted mb-1">Total Penjualan Hari Ini</p>
                <h5 class="fw-bold">${formatRupiah(data.total_harian)}</h5>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="card card-custom p-3 bg-warning-subtle">
                <p class="text-muted mb-1">Jumlah Pesanan Hari Ini</p>
                <h5 class="fw-bold">${data.pesanan_harian} Pesanan</h5>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="card card-custom p-3 bg-body-secondary">
                <p class="text-muted mb-1">Total Penjualan Bulan Ini</p>
                <h5 class="fw-bold">${formatRupiah(data.total_bulanan)}</h5>
              </div>
            </div>`;
        });
    }

    function loadMenuList() {
      fetch('menu.php')
        .then(res => res.json())
        .then(data => {
          const container = document.getElementById('menu-list-container');
          if (!data.length) { container.innerHTML = `<p class="text-muted">Belum ada menu di database.</p>`; return; }

          container.innerHTML = `
            <table class="table table-striped align-middle">
              <thead class="table-light">
                <tr>
                  <th>Nama Menu</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                ${data.map(menu => `
                  <tr>
                    <td>${menu.nama_menu}</td>
                    <td>${menu.kategori || '-'}</td>
                    <td>${formatRupiah(menu.harga)}</td>
                    <td>${parseInt(menu.stok || 0)}</td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-warning me-1" onclick="openEditModal(${menu.id_menu}, '${menu.nama_menu}', ${menu.harga}, '${menu.kategori}', ${menu.stok})">Edit</button>
                      <button class="btn btn-sm btn-danger" onclick="deleteMenuItem(${menu.id_menu})">Hapus</button>
                    </td>
                  </tr>`).join('')}
              </tbody>
            </table>`;
        });
    }

    function deleteMenuItem(id) {
      if (confirm(`Apakah Anda yakin ingin menghapus menu ID = ${id}?`)) {
        fetch(`hapus.php?id=${id}`)
          .then(res => res.text())
          .then(result => {
            if (result.includes("success")) { alert("Menu berhasil dihapus!"); loadMenuList(); }
            else alert("Gagal menghapus menu: " + result);
          });
      }
    }

    function openEditModal(id, nama, harga, kategori, stok) {
      const modal = new bootstrap.Modal(document.getElementById('editModal'));
      document.getElementById('edit-id-menu').value = id;
      document.getElementById('edit-nama-menu').value = nama;
      document.getElementById('edit-harga').value = harga;
      document.getElementById('edit-kategori').value = kategori;
      document.getElementById('edit-stok').value = stok;
      modal.show();
    }

    document.addEventListener('DOMContentLoaded', () => {
      loadKpiData();
      loadMenuList();

      document.getElementById('add-menu-form').addEventListener('submit', e => {
        e.preventDefault();
        const name = document.getElementById('menu-name').value.trim();
        const kategori = document.getElementById('menu-category').value.trim();
        const price = document.getElementById('menu-price').value.trim();
        const stok = document.getElementById('menu-stock').value.trim();

        fetch('tambah_menu.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `nama_menu=${encodeURIComponent(name)}&kategori=${encodeURIComponent(kategori)}&harga=${encodeURIComponent(price)}&stok=${encodeURIComponent(stok)}`
        })
        .then(res => res.text())
        .then(result => {
          if (result.includes("success")) { alert(`Menu "${name}" berhasil ditambahkan!`); e.target.reset(); loadMenuList(); }
          else alert("Gagal menambah menu: " + result);
        });
      });

      document.getElementById('edit-form').addEventListener('submit', e => {
        e.preventDefault();
        const id = document.getElementById('edit-id-menu').value;
        const nama = document.getElementById('edit-nama-menu').value.trim();
        const harga = document.getElementById('edit-harga').value.trim();
        const kategori = document.getElementById('edit-kategori').value.trim();
        const stok = document.getElementById('edit-stok').value.trim();

        fetch('edit_menu.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id_menu=${id}&nama_menu=${encodeURIComponent(nama)}&harga=${encodeURIComponent(harga)}&kategori=${encodeURIComponent(kategori)}&stok=${encodeURIComponent(stok)}`
        })
        .then(res => res.text())
        .then(result => {
          if (result.includes('success')) { alert('Menu berhasil diperbarui!'); bootstrap.Modal.getInstance(document.getElementById('editModal')).hide(); loadMenuList(); }
          else alert('Gagal memperbarui menu: ' + result);
        });
      });
    });
  </script>
</body>
</html>
