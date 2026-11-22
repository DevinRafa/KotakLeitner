<?php
require_once 'cek_login.php';
$uid = (int)$_SESSION['user_id'];

// Filter deck (boleh kosong = semua deck)
$deck_id = isset($_GET['deck']) ? (int)$_GET['deck'] : 0;
$syarat = '';
if ($deck_id > 0) {
    $q = mysqli_query($conn, "SELECT id FROM deck WHERE id=$deck_id AND user_id=$uid");
    if (mysqli_num_rows($q) == 1) {
        $syarat = "AND d.id = $deck_id";
    } else {
        $deck_id = 0;
    }
}

// Jumlah kartu di tiap kotak
$jumlah = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
$q = mysqli_query($conn, "SELECT k.kotak, COUNT(*) AS n FROM kartu k JOIN deck d ON k.deck_id = d.id
    WHERE d.user_id=$uid $syarat GROUP BY k.kotak");
while ($row = mysqli_fetch_assoc($q)) {
    $jumlah[(int)$row['kotak']] = (int)$row['n'];
}
$total = array_sum($jumlah);
$terbanyak = max($jumlah);

$q = mysqli_query($conn, "SELECT COUNT(*) AS n FROM kartu k JOIN deck d ON k.deck_id = d.id
    WHERE d.user_id=$uid $syarat AND k.jadwal <= CURDATE()");
$ulang = (int)mysqli_fetch_assoc($q)['n'];

// Daftar deck untuk pilihan filter dan ringkasan per deck
$q = mysqli_query($conn, "SELECT d.id, d.nama, COUNT(k.id) AS total, SUM(k.kotak = 5) AS hafal
    FROM deck d LEFT JOIN kartu k ON k.deck_id = d.id
    WHERE d.user_id=$uid GROUP BY d.id ORDER BY d.nama");
$semua_deck = [];
while ($row = mysqli_fetch_assoc($q)) {
    $semua_deck[] = $row;
}

$judul = 'Progres';
$aktif = 'progres';
include 'header.php';
?>
<div class="wrap stack">
  <div class="page-head">
    <div>
      <span class="eyebrow">Progres belajar</span>
      <h1>Kartumu di tiap kotak</h1>
      <p class="lead">Semakin ke kanan, semakin hafal.</p>
    </div>
    <form class="row" method="get" action="progres.php">
      <div class="sel">
        <select class="input" name="deck" aria-label="Pilih deck">
          <option value="0">Semua deck</option>
          <?php foreach ($semua_deck as $d): ?>
            <option value="<?= (int)$d['id'] ?>" <?= $deck_id == $d['id'] ? 'selected' : '' ?>><?= e($d['nama']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button class="btn btn-sec" type="submit">Tampilkan</button>
    </form>
  </div>

<?php if ($total == 0): ?>
  <div class="empty">
    <img src="assets/img/maskot-tidur.svg" alt="" width="160" height="173">
    <h3>Belum ada kartu</h3>
    <p class="muted">Tambahkan kartu ke deck dulu, lalu progresmu akan muncul di sini.</p>
    <a class="btn btn-pri" href="deck.php">Ke Deck Saya</a>
  </div>
<?php else: ?>
  <div class="grid g2">
    <div class="card stat">
      <div class="muted">Total kartu</div>
      <div class="num"><?= $total ?></div>
    </div>
    <div class="card stat">
      <div class="muted">Perlu diulang hari ini</div>
      <div class="num"><?= $ulang ?></div>
      <?php if ($ulang > 0): ?>
        <a class="link" href="belajar.php?<?= $deck_id > 0 ? 'deck=' . $deck_id . '&amp;' : '' ?>baru=1">Mulai belajar &rarr;</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="grid g5">
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <div class="col">
          <div class="tube">
            <?php if ($jumlah[$i] > 0): ?>
              <i class="kfill<?= $i ?>" style="height:<?= round($jumlah[$i] / $terbanyak * 100) ?>%"></i>
            <?php endif; ?>
          </div>
          <?= badge_kotak($i) ?>
          <div class="hd" style="font-size:28px;font-weight:700"><?= $jumlah[$i] ?></div>
          <div class="hint">ulang tiap <?= $INTERVAL[$i] ?> hari</div>
        </div>
      <?php endfor; ?>
    </div>
  </div>

  <h2>Per deck</h2>
  <div class="stack-s">
    <?php foreach ($semua_deck as $d): ?>
      <?php $persen = $d['total'] > 0 ? round($d['hafal'] / $d['total'] * 100) : 0; ?>
      <div class="card">
        <div class="row between">
          <h4><?= e($d['nama']) ?></h4>
          <span class="chip ok"><?= (int)$d['hafal'] ?> dari <?= (int)$d['total'] ?> kartu sudah di Kotak 5</span>
        </div>
        <div class="progress-row" style="margin-top:12px">
          <div class="bar"><i style="width:<?= $persen ?>%"></i></div>
          <b><?= $persen ?>%</b>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
</div>
<?php include 'footer.php'; ?>
