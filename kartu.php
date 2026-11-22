<?php
require_once 'cek_login.php';
$uid = (int)$_SESSION['user_id'];

// Deck harus ada dan milik user yang login
$deck_id = isset($_GET['deck']) ? (int)$_GET['deck'] : 0;
$q = mysqli_query($conn, "SELECT * FROM deck WHERE id=$deck_id AND user_id=$uid");
if (mysqli_num_rows($q) != 1) {
    set_flash('err', 'Deck tidak ditemukan.');
    header('Location: deck.php');
    exit;
}
$deck = mysqli_fetch_assoc($q);

$error = '';
$edit = null;

// ---------- Proses form (tambah / ubah / hapus kartu) ----------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';

    if ($aksi == 'hapus') {
        $id = (int)$_POST['id'];
        mysqli_query($conn, "DELETE FROM kartu WHERE id=$id AND deck_id=$deck_id");
        set_flash('ok', 'Kartu berhasil dihapus.');
        header("Location: kartu.php?deck=$deck_id");
        exit;
    }

    $depan = isset($_POST['depan']) ? trim($_POST['depan']) : '';
    $belakang = isset($_POST['belakang']) ? trim($_POST['belakang']) : '';

    if ($depan == '' || $belakang == '') {
        $error = 'Sisi depan dan belakang wajib diisi.';
    } elseif (strlen($depan) > 200 || strlen($belakang) > 300) {
        $error = 'Teks terlalu panjang (depan maks. 200, belakang maks. 300 karakter).';
    }

    if ($error == '') {
        $d = mysqli_real_escape_string($conn, $depan);
        $b = mysqli_real_escape_string($conn, $belakang);

        if ($aksi == 'ubah') {
            $id = (int)$_POST['id'];
            mysqli_query($conn, "UPDATE kartu SET depan='$d', belakang='$b' WHERE id=$id AND deck_id=$deck_id");
            set_flash('ok', 'Kartu berhasil diperbarui.');
        } else {
            // Kartu baru selalu masuk Kotak 1 dan langsung siap dipelajari
            mysqli_query($conn, "INSERT INTO kartu (deck_id, depan, belakang, kotak, jadwal) VALUES ($deck_id, '$d', '$b', 1, CURDATE())");
            set_flash('ok', 'Kartu baru ditambahkan ke Kotak 1.');
        }
        header("Location: kartu.php?deck=$deck_id");
        exit;
    }

    $edit = ['id' => ($aksi == 'ubah' ? (int)$_POST['id'] : 0), 'depan' => $depan, 'belakang' => $belakang];
}

if ($edit === null && isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM kartu WHERE id=$id AND deck_id=$deck_id");
    if (mysqli_num_rows($q) == 1) {
        $edit = mysqli_fetch_assoc($q);
    }
}
$mode_edit = ($edit !== null && $edit['id'] > 0);
$form = $edit !== null ? $edit : ['id' => 0, 'depan' => '', 'belakang' => ''];

// ---------- Daftar kartu ----------
$hasil = mysqli_query($conn, "SELECT * FROM kartu WHERE deck_id=$deck_id ORDER BY id DESC");
$daftar = [];
$ulang = 0;
$hari_ini = date('Y-m-d');
while ($row = mysqli_fetch_assoc($hasil)) {
    $daftar[] = $row;
    if ($row['jadwal'] <= $hari_ini) { $ulang++; }
}

$judul = $deck['nama'];
$aktif = 'deck';
include 'header.php';
?>
<div class="wrap stack">
  <a class="link" href="deck.php">&larr; Semua deck</a>

  <div class="card">
    <div class="grid g2" style="align-items:center">
      <div class="cv cv-<?= (int)$deck['warna'] ?> hasch" style="height:150px">
        <img class="cvchar" src="assets/img/<?= e($deck['karakter']) ?>.svg" height="130" alt="">
      </div>
      <div class="stack-s">
        <h1 style="font-size:38px"><?= e($deck['nama']) ?></h1>
        <?php if ($deck['deskripsi'] != ''): ?><p class="muted"><?= e($deck['deskripsi']) ?></p><?php endif; ?>
        <div class="row">
          <span class="chip"><?= count($daftar) ?> kartu</span>
          <?php if ($ulang > 0): ?>
            <span class="chip warm"><?= $ulang ?> perlu diulang</span>
          <?php else: ?>
            <span class="chip ok">Aman hari ini</span>
          <?php endif; ?>
        </div>
        <div class="row">
          <?php if ($ulang > 0): ?>
            <a class="btn btn-pri" href="belajar.php?deck=<?= $deck_id ?>">Belajar sekarang</a>
          <?php endif; ?>
          <a class="btn btn-sec" href="deck.php?edit=<?= $deck_id ?>#form-deck">Ubah deck</a>
        </div>
      </div>
    </div>
  </div>

  <form class="card stack" method="post" action="kartu.php?deck=<?= $deck_id ?>" id="form-kartu">
    <h3><?= $mode_edit ? 'Ubah kartu' : 'Tambah kartu' ?></h3>
    <?php if ($error != ''): ?>
      <div class="alert err"><?= e($error) ?></div>
    <?php endif; ?>
    <input type="hidden" name="aksi" value="<?= $mode_edit ? 'ubah' : 'tambah' ?>">
    <input type="hidden" name="id" value="<?= (int)$form['id'] ?>">
    <div class="grid g2">
      <div class="field">
        <label for="depan">Sisi depan (pertanyaan)</label>
        <textarea class="input" id="depan" name="depan" maxlength="200" placeholder="contoh: Library"><?= e($form['depan']) ?></textarea>
      </div>
      <div class="field">
        <label for="belakang">Sisi belakang (jawaban)</label>
        <textarea class="input" id="belakang" name="belakang" maxlength="300" placeholder="contoh: Perpustakaan"><?= e($form['belakang']) ?></textarea>
      </div>
    </div>
    <div class="row">
      <button class="btn btn-pri" type="submit"><?= $mode_edit ? 'Simpan perubahan' : 'Tambah kartu' ?></button>
      <?php if ($mode_edit): ?>
        <a class="btn btn-sec" href="kartu.php?deck=<?= $deck_id ?>">Batal</a>
      <?php endif; ?>
    </div>
  </form>

  <h2>Isi deck</h2>
  <?php if (count($daftar) == 0): ?>
    <div class="empty">
      <img src="assets/img/maskot-tidur.svg" alt="" width="160" height="173">
      <h3>Deck ini masih kosong</h3>
      <p class="muted">Tambahkan kartu pertamamu lewat formulir di atas.</p>
    </div>
  <?php else: ?>
    <div class="tblbox">
      <table class="tbl">
        <thead>
          <tr><th>Depan</th><th>Belakang</th><th>Kotak</th><th>Jadwal ulang</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php foreach ($daftar as $k): ?>
            <tr>
              <td><?= e($k['depan']) ?></td>
              <td><?= e($k['belakang']) ?></td>
              <td><?= badge_kotak($k['kotak']) ?></td>
              <td>
                <?php if ($k['jadwal'] <= $hari_ini): ?>
                  <span class="chip warm">Hari ini</span>
                <?php else: ?>
                  <?= e(date('d/m/Y', strtotime($k['jadwal']))) ?>
                <?php endif; ?>
              </td>
              <td>
                <div class="row">
                  <a class="btn btn-sec btn-sm" href="kartu.php?deck=<?= $deck_id ?>&amp;edit=<?= (int)$k['id'] ?>#form-kartu">Ubah</a>
                  <form method="post" action="kartu.php?deck=<?= $deck_id ?>" onsubmit="return confirm('Hapus kartu ini?');">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= (int)$k['id'] ?>">
                    <button class="btn btn-dan-o btn-sm" type="submit">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
