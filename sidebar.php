<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar d-none d-md-flex flex-column shadow">
  <div class="logo">Mie DASH</div>
  <nav class="nav flex-column gap-2">
    <a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">Dashboard</a>
    <a href="#menu" class="<?= ($current_page == 'index.php') ? '' : '' ?>">Menu</a>
    <a href="laporan.php" class="<?= ($current_page == 'laporan.php') ? 'active' : '' ?>">Laporan Penjualan</a>
    <a href="transaksi.php" class="<?= ($current_page == 'transaksi.php') ? 'active' : '' ?>">Transaksi</a>
    <a href="supplier.php" class="<?= ($current_page == 'supplier.php') ? 'active' : '' ?>">Supplier</a>
  </nav>
</div>
