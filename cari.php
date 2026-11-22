<?php
// Pastikan hanya pengguna yang sudah login yang bisa mengakses halaman ini
require_once 'cek_login.php';

$uid = (int)$_SESSION['user_id'];
$kata = isset($_GET['q']) ? trim($_GET['q']) : '';
$hasil = [];
$hari_ini = date('Y-m-d'); // Dipakai untuk mengecek jadwal jatuh tempo kartu

// Jalankan pencarian jika kata kunci tidak kosong
if ($kata != '') {
    $like = mysqli_real_escape_string($conn, addcslashes($kata, '%_'));
    $q = mysqli_query($conn, "SELECT k.*, d.nama AS nama_deck, d.id AS id_deck
        FROM kartu k JOIN deck d ON k.deck_id = d.id
        WHERE d.user_id = $uid AND (k.depan LIKE '%$like%' OR k.belakang LIKE '%$like%')
        ORDER BY d.nama, k.id LIMIT 100");
    while ($row = mysqli_fetch_assoc($q)) {
        $hasil[] = $row;
    }
}

// Beri tanda kuning (<mark>) pada bagian teks yang cocok dengan kata kunci
function sorot($teks, $kata) {
    $aman = e($teks);
    if ($kata == '') { return $aman; }
    $pola = '/(' . preg_quote(e($kata), '/') . ')/iu';
    return preg_replace($pola, '<mark>$1</mark>', $aman);
}

$judul = 'Cari Kartu';
$aktif = 'cari';
include 'header.php';
?>
<div class="wrap stack">
  <div class="page-head" style="margin-bottom:0">
    <div>
      <span class="eyebrow">Pencarian</span>
      <h1>Cari kartu</h1>
      <p class="lead">Cari di sisi depan maupun belakang semua kartumu.</p>
    </div>
  </div>

  <!-- Form pencarian kata kunci dengan metode GET -->
  <form class="row" method="get" action="cari.php" style="flex-wrap:nowrap">
    <input class="input" type="search" name="q" value="<?= e($kata) ?>" placeholder="Ketik kata yang dicari..." aria-label="Kata kunci" autofocus>
    <button class="btn btn-pri" type="submit">Cari</button>
  </form>

<?php if ($kata == ''): ?>
  <div class="empty">
    <img src="assets/img/maskot-cari.svg" alt="" width="160" height="173">
    <h3>Mau cari apa?</h3>
    <p class="muted">Ketik kata kunci di atas untuk menemukan kartu dari semua deckmu.</p>
  </div>
<?php elseif (count($hasil) == 0): ?>
  <div class="empty">
    <img src="assets/img/maskot-cari.svg" alt="" width="160" height="173">
    <h3>Kartu tidak ditemukan</h3>
    <p class="muted">Tidak ada kartu yang cocok dengan “<?= e($kata) ?>”. Coba kata kunci lain.</p>
  </div>
<?php else: ?>
  <p class="muted"><b><?= count($hasil) ?></b> kartu ditemukan untuk “<?= e($kata) ?>”</p>
  <div class="tblbox">
    <table class="tbl">
      <thead>
        <tr>
          <th>Depan</th>
          <th>Belakang</th>
          <th>Deck</th>
          <th>Kotak</th>
          <th>Jadwal ulang</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($hasil as $k): ?>
          <tr>
            <td><?= sorot($k['depan'], $kata) ?></td>
            <td><?= sorot($k['belakang'], $kata) ?></td>
            <td><a class="link" href="kartu.php?deck=<?= (int)$k['id_deck'] ?>"><?= e($k['nama_deck']) ?></a></td>
            <td><?= badge_kotak($k['kotak']) ?></td>
            <td>
              <?php if ($k['jadwal'] <= $hari_ini): ?>
                <span class="chip warm">Hari ini</span>
              <?php else: ?>
                <?= e(date('d/m/Y', strtotime($k['jadwal']))) ?>
              <?php endif; ?>
            </td>
            <td>
              <!-- Aksi cepat untuk langsung masuk mode belajar deck kartu ini -->
              <a class="btn btn-sec btn-sm" href="belajar.php?deck=<?= (int)$k['id_deck'] ?>&baru=1">Belajar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
</div>
<?php include 'footer.php'; ?>