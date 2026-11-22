<?php
require_once 'cek_login.php';
$uid = (int)$_SESSION['user_id'];

$error = '';
$edit = null;   // data deck yang sedang diedit

// ---------- Proses form (tambah / ubah / hapus) ----------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';

    if ($aksi == 'hapus') {
        $id = (int)$_POST['id'];
        mysqli_query($conn, "DELETE FROM deck WHERE id=$id AND user_id=$uid");
        set_flash('ok', 'Deck berhasil dihapus.');
        header('Location: deck.php');
        exit;
    }

    // tambah / ubah
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    $warna = isset($_POST['warna']) ? (int)$_POST['warna'] : 1;
    $karakter = isset($_POST['karakter']) ? $_POST['karakter'] : 'kucing';

    if ($nama == '') {
        $error = 'Nama deck wajib diisi.';
    } elseif (strlen($nama) > 60) {
        $error = 'Nama deck maksimal 60 karakter.';
    } elseif ($warna < 1 || $warna > 6 || !in_array($karakter, $KARAKTER)) {
        $error = 'Pilihan warna atau karakter tidak valid.';
    }

    if ($error == '') {
        $n = mysqli_real_escape_string($conn, $nama);
        $d = mysqli_real_escape_string($conn, substr($deskripsi, 0, 200));
        $k = mysqli_real_escape_string($conn, $karakter);

        if ($aksi == 'ubah') {
            $id = (int)$_POST['id'];
            mysqli_query($conn, "UPDATE deck SET nama='$n', deskripsi='$d', warna=$warna, karakter='$k' WHERE id=$id AND user_id=$uid");
            set_flash('ok', 'Deck berhasil diperbarui.');
        } else {
            mysqli_query($conn, "INSERT INTO deck (user_id, nama, deskripsi, warna, karakter) VALUES ($uid, '$n', '$d', $warna, '$k')");
            set_flash('ok', 'Deck baru berhasil dibuat.');
        }
        header('Location: deck.php');
        exit;
    }

    // Kalau error, isi ulang form dengan yang tadi diketik
    $edit = ['id' => isset($_POST['id']) ? (int)$_POST['id'] : 0, 'nama' => $nama, 'deskripsi' => $deskripsi, 'warna' => max(1, $warna), 'karakter' => $karakter];
    if ($aksi != 'ubah') { $edit['id'] = 0; }
}

// Mode edit: deck.php?edit=ID
if ($edit === null && isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM deck WHERE id=$id AND user_id=$uid");
    if (mysqli_num_rows($q) == 1) {
        $edit = mysqli_fetch_assoc($q);
    }
}

$mode_edit = ($edit !== null && $edit['id'] > 0);
$form = $edit !== null ? $edit : ['id' => 0, 'nama' => '', 'deskripsi' => '', 'warna' => 1, 'karakter' => 'kucing'];

// ---------- Ambil daftar deck ----------
$hasil = mysqli_query($conn, "SELECT d.*, COUNT(k.id) AS total, SUM(k.jadwal <= CURDATE()) AS ulang
    FROM deck d LEFT JOIN kartu k ON k.deck_id = d.id
    WHERE d.user_id = $uid GROUP BY d.id ORDER BY d.id DESC");
$daftar = [];
while ($row = mysqli_fetch_assoc($hasil)) {
    $daftar[] = $row;
}

$judul = 'Deck Saya';
$aktif = 'deck';
include 'header.php';
?>
<div class="wrap stack">
  <div class="page-head">
    <div>
      <span class="eyebrow">Modul kartu</span>
      <h1>Deck Saya</h1>
      <p class="lead">Kelompokkan kartumu berdasarkan topik.</p>
    </div>
  </div>

  <form class="card stack" method="post" action="deck.php" id="form-deck">
    <h3><?= $mode_edit ? 'Ubah deck' : 'Buat deck baru' ?></h3>
    <?php if ($error != ''): ?>
      <div class="alert err"><?= e($error) ?></div>
    <?php endif; ?>

    <input type="hidden" name="aksi" value="<?= $mode_edit ? 'ubah' : 'tambah' ?>">
    <input type="hidden" name="id" value="<?= (int)$form['id'] ?>">

    <div class="grid g2">
      <div class="field">
        <label for="nama">Nama deck</label>
        <input class="input" type="text" id="nama" name="nama" maxlength="60" value="<?= e($form['nama']) ?>" placeholder="contoh: Kosakata Inggris">
      </div>
      <div class="field">
        <label for="deskripsi">Deskripsi (boleh kosong)</label>
        <input class="input" type="text" id="deskripsi" name="deskripsi" maxlength="200" value="<?= e($form['deskripsi']) ?>" placeholder="Isi deck ini tentang apa?">
      </div>
    </div>

    <div class="field">
      <label>Warna sampul</label>
      <div class="pilihan">
        <?php for ($i = 1; $i <= 6; $i++): ?>
          <label class="sw cv-<?= $i ?> <?= $form['warna'] == $i ? 'on' : '' ?>" title="Warna <?= $i ?>">
            <input class="pilih-opsi" type="radio" name="warna" value="<?= $i ?>" <?= $form['warna'] == $i ? 'checked' : '' ?>>
          </label>
        <?php endfor; ?>
      </div>
    </div>

    <div class="field">
      <label>Karakter sampul</label>
      <div class="pilihan">
        <?php foreach ($KARAKTER as $kr): ?>
          <label class="avpick <?= $form['karakter'] == $kr ? 'on' : '' ?>" title="<?= e(ucfirst($kr)) ?>">
            <input class="pilih-opsi" type="radio" name="karakter" value="<?= e($kr) ?>" <?= $form['karakter'] == $kr ? 'checked' : '' ?>>
            <img src="assets/img/<?= e($kr) ?>.svg" alt="<?= e(ucfirst($kr)) ?>">
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="row">
      <button class="btn btn-pri" type="submit"><?= $mode_edit ? 'Simpan perubahan' : 'Buat deck' ?></button>
      <?php if ($mode_edit): ?>
        <a class="btn btn-sec" href="deck.php">Batal</a>
      <?php endif; ?>
    </div>
  </form>

  <h2>Semua deck (<?= count($daftar) ?>)</h2>
  <?php if (count($daftar) == 0): ?>
    <div class="empty">
      <img src="assets/img/maskot-tidur.svg" alt="" width="160" height="173">
      <h3>Belum ada deck</h3>
      <p class="muted">Isi formulir di atas untuk membuat deck pertamamu.</p>
    </div>
  <?php else: ?>
    <div class="grid g3">
      <?php foreach ($daftar as $d): ?>
        <div class="stack-s">
          <?= kartu_deck($d) ?>
          <div class="row">
            <a class="btn btn-sec btn-sm" href="deck.php?edit=<?= (int)$d['id'] ?>#form-deck">Ubah</a>
            <form method="post" action="deck.php" onsubmit="return confirm('Hapus deck ini beserta semua kartunya?');">
              <input type="hidden" name="aksi" value="hapus">
              <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
              <button class="btn btn-dan-o btn-sm" type="submit">Hapus</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
