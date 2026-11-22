<?php
require_once 'koneksi.php';
$judul = 'Belajar jadi lebih mudah';
include 'header.php';
?>
<div class="wrap">
  <section class="hero">
    <div class="stack">
      <span class="eyebrow">Flashcard sistem kotak Leitner</span>
      <h1>Hafal lebih lama dengan <span class="rainbow"><span>k</span><span>o</span><span>t</span><span>a</span><span>k</span></span> kecil yang pintar</h1>
      <p class="lead">Buat deck, tulis kartu, lalu belajar dengan membalik kartu. Jawab benar, kartu naik ke kotak berikutnya. Jawab salah, kartu kembali ke awal untuk diulang.</p>
      <div class="row">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a class="btn btn-pri btn-lg" href="dashboard.php">Ke Dashboard</a>
        <?php else: ?>
          <a class="btn btn-pri btn-lg" href="register.php">Mulai Gratis</a>
          <a class="btn btn-sec btn-lg" href="login.php">Masuk</a>
        <?php endif; ?>
      </div>
    </div>
    <img src="assets/img/hero-kotak.svg" alt="Lima kotak Leitner berisi kartu">
  </section>

  <section class="stack" style="margin-top:72px">
    <div class="center stack-s">
      <span class="eyebrow">Cara kerjanya</span>
      <h2>Lima kotak, lima jadwal ulang</h2>
      <p class="lead">Semakin sering kamu benar, semakin jarang kartu muncul.</p>
    </div>
    <div class="grid g5">
      <?php
      $warna = [1 => 'k1', 2 => 'k2', 3 => 'k3', 4 => 'k4', 5 => 'k5'];
      foreach ($INTERVAL as $kotak => $hari):
      ?>
        <div class="step <?= $warna[$kotak] ?>">
          <div class="n"><?= $kotak ?></div>
          <div class="hd" style="font-weight:600;font-size:20px">Kotak <?= $kotak ?></div>
          <div>Diulang tiap <?= $hari ?> hari</div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="grid g3" style="margin-top:64px">
    <div class="card"><h3>1. Buat deck</h3><p class="muted" style="margin-top:8px">Kelompokkan kartu berdasarkan topik, lengkap dengan warna dan karakter lucu.</p></div>
    <div class="card"><h3>2. Belajar</h3><p class="muted" style="margin-top:8px">Lihat sisi depan, ingat jawabannya, lalu balik kartu untuk mengecek.</p></div>
    <div class="card"><h3>3. Pantau progres</h3><p class="muted" style="margin-top:8px">Lihat berapa kartu di tiap kotak dan mana yang perlu diulang hari ini.</p></div>
  </section>
</div>
<?php include 'footer.php'; ?>
