<?php
require_once 'cek_login.php';
$uid = (int)$_SESSION['user_id'];

// Daftar deck milik user beserta jumlah kartu dan kartu yang jatuh tempo hari ini
$hasil = mysqli_query($conn, "SELECT d.*, COUNT(k.id) AS total, SUM(k.jadwal <= CURDATE()) AS ulang
    FROM deck d LEFT JOIN kartu k ON k.deck_id = d.id
    WHERE d.user_id = $uid GROUP BY d.id ORDER BY d.id DESC");

$daftar = [];
$total_kartu = 0;
$total_ulang = 0;
while ($row = mysqli_fetch_assoc($hasil)) {
    $daftar[] = $row;
    $total_kartu += (int)$row['total'];
    $total_ulang += (int)$row['ulang'];
}
$total_deck = count($daftar);

$judul = 'Dashboard';
$aktif = 'dashboard';
include 'header.php';
?>
<div class="wrap stack">
  <div class="page-head">
    <div>
      <span class="eyebrow">Dashboard</span>
      <h1>Halo, <?= e(nama_depan($_SESSION['nama'])) ?>!</h1>
      <p class="lead">
        <?php if ($total_ulang > 0): ?>
          Ada <b><?= $total_ulang ?> kartu</b> yang siap diulang hari ini.
        <?php else: ?>
          Belum ada kartu yang perlu diulang hari ini.
        <?php endif; ?>
      </p>
    </div>
    <div class="row">
      <?php if ($total_ulang > 0): ?>
        <a class="btn btn-pri btn-lg" href="belajar.php?baru=1">Mulai belajar</a>
      <?php endif; ?>
      <a class="btn btn-sec btn-lg" href="deck.php">Kelola Deck</a>
    </div>
  </div>

  <div class="grid g3">
    <div class="card stat">
      <div class="bub kfill1"><svg class="ico" viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="14" rx="3"/><path d="M4 10h16"/></svg></div>
      <div class="num"><?= $total_deck ?></div>
      <div class="muted">Deck</div>
    </div>
    <div class="card stat">
      <div class="bub kfill2"><svg class="ico" viewBox="0 0 24 24"><rect x="6" y="3" width="12" height="18" rx="3"/><path d="M9 8h6M9 12h4"/></svg></div>
      <div class="num"><?= $total_kartu ?></div>
      <div class="muted">Total kartu</div>
    </div>
    <div class="card stat">
      <div class="bub kfill3"><svg class="ico" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
      <div class="num"><?= $total_ulang ?></div>
      <div class="muted">Perlu diulang hari ini</div>
    </div>
  </div>

  <h2>Deck-mu</h2>
  <?php if ($total_deck == 0): ?>
    <div class="empty">
      <img src="assets/img/maskot-tidur.svg" alt="" width="160" height="173">
      <h3>Belum ada deck</h3>
      <p class="muted">Buat deck pertamamu, lalu isi dengan kartu untuk mulai belajar.</p>
      <a class="btn btn-pri" href="deck.php">Buat Deck Pertama</a>
    </div>
  <?php else: ?>
    <div class="grid g3">
      <?php foreach ($daftar as $d): ?>
        <?= kartu_deck($d) ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
