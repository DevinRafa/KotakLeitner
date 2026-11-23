<?php
require_once 'cek_login.php';
$uid = (int)$_SESSION['user_id'];

// belajar.php?deck=ID  -> satu deck;  belajar.php -> semua deck
$deck_id = isset($_GET['deck']) ? (int)$_GET['deck'] : 0;
$nama_deck = 'Semua deck';
$syarat_deck = '';
if ($deck_id > 0) {
    $q = mysqli_query($conn, "SELECT * FROM deck WHERE id=$deck_id AND user_id=$uid");
    if (mysqli_num_rows($q) != 1) {
        set_flash('err', 'Deck tidak ditemukan.');
        header('Location: deck.php');
        exit;
    }
    $deck = mysqli_fetch_assoc($q);
    $nama_deck = $deck['nama'];
    $syarat_deck = "AND d.id = $deck_id";
}
$kembali = $deck_id > 0 ? "belajar.php?deck=$deck_id" : 'belajar.php';

// Hitungan sesi belajar (benar / salah) disimpan di session
if (isset($_GET['baru']) || !isset($_SESSION['sesi'])) {
    $_SESSION['sesi'] = ['benar' => 0, 'salah' => 0];
}

// ---------- Jawaban dikirim: pindahkan kartu antar kotak ----------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $hasil = isset($_POST['hasil']) ? $_POST['hasil'] : '';

    $q = mysqli_query($conn, "SELECT k.kotak FROM kartu k JOIN deck d ON k.deck_id = d.id WHERE k.id=$id AND d.user_id=$uid");
    if (mysqli_num_rows($q) == 1 && ($hasil == 'benar' || $hasil == 'salah')) {
        $kartu = mysqli_fetch_assoc($q);
        if ($hasil == 'benar') {
            $kotak_baru = min((int)$kartu['kotak'] + 1, 5);   // naik satu kotak (maks. 5)
            $_SESSION['sesi']['benar']++;
        } else {
            $kotak_baru = 1;                                   // salah: kembali ke kotak 1
            $_SESSION['sesi']['salah']++;
        }
        $hari = $INTERVAL[$kotak_baru];
        mysqli_query($conn, "UPDATE kartu SET kotak=$kotak_baru, jadwal=DATE_ADD(CURDATE(), INTERVAL $hari DAY) WHERE id=$id");
    }
    header("Location: $kembali");
    exit;
}

// ---------- Ambil satu kartu yang jatuh tempo (kotak rendah dulu) ----------
$sql_due = "FROM kartu k JOIN deck d ON k.deck_id = d.id WHERE d.user_id=$uid AND k.jadwal <= CURDATE() $syarat_deck";
$q = mysqli_query($conn, "SELECT k.* $sql_due ORDER BY k.kotak, k.id LIMIT 1");
$kartu = mysqli_fetch_assoc($q);

$q = mysqli_query($conn, "SELECT COUNT(*) AS n $sql_due");
$sisa = (int)mysqli_fetch_assoc($q)['n'];

$benar = $_SESSION['sesi']['benar'];
$salah = $_SESSION['sesi']['salah'];
$sudah = $benar + $salah;
$persen = ($sudah + $sisa) > 0 ? round($sudah / ($sudah + $sisa) * 100) : 0;

$judul = 'Belajar';
$aktif = 'deck';
include 'header.php';
?>
<div class="wrap stack">
  <div class="page-head" style="margin-bottom:0">
    <div>
      <span class="eyebrow">Mode belajar</span>
      <h1 style="font-size:38px"><?= e($nama_deck) ?></h1>
    </div>
    <a class="btn btn-sec" href="<?= $deck_id > 0 ? 'kartu.php?deck=' . $deck_id : 'dashboard.php' ?>">Keluar sesi</a>
  </div>

<?php if ($kartu): ?>
  <div class="progress-row">
    <div class="bar"><i style="width:<?= $persen ?>%"></i></div>
    <span class="chip"><?= $sisa ?> kartu tersisa</span>
  </div>

  <div class="scene" style="max-width:720px;margin:0 auto;width:100%">
    <div class="fc3d" id="kartu3d">
      <div class="face">
        <div class="fc">
          <span class="tag"><?= badge_kotak($kartu['kotak']) ?></span>
          <span class="side">Pertanyaan</span>
          <div class="big"><?= nl2br(e($kartu['depan'])) ?></div>
          <p class="muted">Coba jawab dalam hati, lalu balik kartunya.</p>
        </div>
      </div>
      <div class="face rev">
        <div class="fc back">
          <span class="tag"><?= badge_kotak($kartu['kotak']) ?></span>
          <span class="side">Jawaban</span>
          <div class="big"><?= nl2br(e($kartu['belakang'])) ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="flipbtns">
    <button class="btn btn-pri btn-lg" type="button" id="tombolBalik">Balik kartu</button>

    <form class="hidden" id="tombolJawab" method="post" action="<?= e($kembali) ?>">
      <input type="hidden" name="id" value="<?= (int)$kartu['id'] ?>">
      <div class="stack-s center">
        <p class="muted">Tadi jawabanmu benar?</p>
        <div class="flipbtns">
          <button class="btn btn-warn btn-xl" type="submit" name="hasil" value="salah">Belum tepat<small>kembali ke Kotak 1</small></button>
          <button class="btn btn-ok btn-xl" type="submit" name="hasil" value="benar">Aku benar!<small>naik ke Kotak <?= min((int)$kartu['kotak'] + 1, 5) ?></small></button>
        </div>
      </div>
    </form>
  </div>

<?php else: ?>
  <div class="empty" style="max-width:640px;margin:0 auto">
    <img src="assets/img/maskot-senang.svg" alt="" width="180" height="195">
    <?php if ($sudah > 0): ?>
      <h2>Sesi selesai!</h2>
      <div class="row" style="justify-content:center">
        <span class="chip ok"><?= $benar ?> benar</span>
        <span class="chip warm"><?= $salah ?> perlu diulang</span>
      </div>
      <p class="muted">Kartu yang salah kembali ke Kotak 1 dan muncul lagi besok.</p>
    <?php else: ?>
      <?php
      // Jadwal terdekat kartu berikutnya (semua kartu yang jatuh tempo sudah dijawab)
      $q = mysqli_query($conn, "SELECT MIN(k.jadwal) AS berikutnya FROM kartu k JOIN deck d ON k.deck_id = d.id WHERE d.user_id=$uid $syarat_deck");
      $berikutnya = mysqli_fetch_assoc($q)['berikutnya'];
      ?>
      <?php if ($berikutnya): ?>
        <h2>Semua kartu hari ini sudah beres!</h2>
        <p class="muted">Hebat, kamu konsisten. Istirahat dulu, besok kita lanjut lagi.</p>
        <span class="chip">Sesi berikutnya: <b><?= e(date('d/m/Y', strtotime($berikutnya))) ?></b></span>
      <?php else: ?>
        <h2>Belum ada kartu untuk dipelajari</h2>
        <p class="muted">Tambahkan kartu ke deck-mu dulu, lalu mulai belajar.</p>
      <?php endif; ?>
    <?php endif; ?>
    <div class="row" style="justify-content:center">
      <a class="btn btn-pri" href="progres.php">Lihat progres</a>
      <a class="btn btn-sec" href="dashboard.php">Ke Dashboard</a>
    </div>
  </div>
  <?php $_SESSION['sesi'] = ['benar' => 0, 'salah' => 0]; ?>
<?php endif; ?>
</div>
<?php include 'footer.php'; ?>
