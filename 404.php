<?php
// Halaman 404 (dipakai di hosting Vercel untuk alamat yang tidak ada)
require_once 'koneksi.php';
http_response_code(404);
$judul = 'Halaman tidak ditemukan';
$basis = '/';
include 'header.php';
?>
<div class="wrap" style="padding-top:56px;padding-bottom:56px">
  <div class="empty" style="max-width:720px;margin:0 auto">
    <img src="assets/img/maskot-cari.svg" alt="" width="180" height="195">
    <p style="font-family:Fredoka,sans-serif;font-size:96px;font-weight:700;line-height:1;color:#5B54D6;margin:0">404</p>
    <h1 style="font-size:36px">Halaman tidak ditemukan</h1>
    <p class="muted">Halaman yang kamu cari mungkin sudah dipindahkan, dihapus, atau alamatnya salah ketik.</p>
    <div class="row" style="justify-content:center">
      <?php if ($sudah_login): ?>
        <a class="btn btn-pri" href="dashboard.php">Ke Dashboard</a>
        <a class="btn btn-sec" href="deck.php">Ke Deck Saya</a>
      <?php else: ?>
        <a class="btn btn-pri" href="index.php">Ke Beranda</a>
        <a class="btn btn-sec" href="login.php">Masuk</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
