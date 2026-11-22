<?php
require_once 'cek_login.php';
$uid = (int)$_SESSION['user_id'];

$err_profil = '';
$err_sandi = '';

// Data user terbaru
$q = mysqli_query($conn, "SELECT * FROM users WHERE id=$uid");
$user = mysqli_fetch_assoc($q);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';

    if ($aksi == 'profil') {
        $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
        $avatar = isset($_POST['avatar']) ? $_POST['avatar'] : '';
        if ($nama == '') {
            $err_profil = 'Nama tidak boleh kosong.';
        } elseif (strlen($nama) > 60) {
            $err_profil = 'Nama maksimal 60 karakter.';
        } elseif (!in_array($avatar, $KARAKTER)) {
            $err_profil = 'Avatar tidak valid.';
        } else {
            $n = mysqli_real_escape_string($conn, $nama);
            $a = mysqli_real_escape_string($conn, $avatar);
            mysqli_query($conn, "UPDATE users SET nama='$n', avatar='$a' WHERE id=$uid");
            $_SESSION['nama'] = $nama;   // supaya navbar ikut berubah
            set_flash('ok', 'Profil berhasil diperbarui.');
            header('Location: profil.php');
            exit;
        }
    }

    if ($aksi == 'sandi') {
        $lama = isset($_POST['lama']) ? $_POST['lama'] : '';
        $baru = isset($_POST['baru']) ? $_POST['baru'] : '';
        $konfirmasi = isset($_POST['konfirmasi']) ? $_POST['konfirmasi'] : '';
        if (md5($lama) != $user['password']) {
            $err_sandi = 'Password lama salah.';
        } elseif (strlen($baru) < 6) {
            $err_sandi = 'Password baru minimal 6 karakter.';
        } elseif ($baru != $konfirmasi) {
            $err_sandi = 'Konfirmasi password baru tidak sama.';
        } else {
            $p = md5($baru);
            mysqli_query($conn, "UPDATE users SET password='$p' WHERE id=$uid");
            set_flash('ok', 'Password berhasil diganti.');
            header('Location: profil.php');
            exit;
        }
    }
}

// Ringkasan akun
$q = mysqli_query($conn, "SELECT COUNT(DISTINCT d.id) AS deck, COUNT(k.id) AS kartu
    FROM deck d LEFT JOIN kartu k ON k.deck_id = d.id WHERE d.user_id=$uid");
$ringkas = mysqli_fetch_assoc($q);

$avatar_sekarang = $err_profil != '' && isset($_POST['avatar']) && in_array($_POST['avatar'], $KARAKTER) ? $_POST['avatar'] : $user['avatar'];
$nama_form = $err_profil != '' ? trim($_POST['nama']) : $user['nama'];

$judul = 'Profil';
$aktif = 'profil';
include 'header.php';
?>
<div class="wrap stack">
  <div class="page-head" style="margin-bottom:0">
    <div>
      <span class="eyebrow">Akun</span>
      <h1>Profil pengguna</h1>
    </div>
  </div>

  <div class="card">
    <div class="row" style="gap:24px">
      <span class="avatar big" style="background:var(--yellow-l)"><img src="assets/img/<?= e($user['avatar']) ?>.svg" alt=""></span>
      <div class="stack-s">
        <h2><?= e($user['nama']) ?></h2>
        <p class="muted">@<?= e($user['username']) ?> &middot; Bergabung <?= e(date('d/m/Y', strtotime($user['dibuat']))) ?></p>
        <div class="row">
          <span class="chip"><?= (int)$ringkas['deck'] ?> deck</span>
          <span class="chip yel"><?= (int)$ringkas['kartu'] ?> kartu</span>
        </div>
      </div>
    </div>
  </div>

  <div class="grid g2" style="align-items:start">
    <form class="card stack" method="post" action="profil.php">
      <h3>Ubah profil</h3>
      <?php if ($err_profil != ''): ?><div class="alert err"><?= e($err_profil) ?></div><?php endif; ?>
      <input type="hidden" name="aksi" value="profil">
      <div class="field">
        <label for="nama">Nama</label>
        <input class="input" type="text" id="nama" name="nama" maxlength="60" value="<?= e($nama_form) ?>">
      </div>
      <div class="field">
        <label>Avatar</label>
        <div class="pilihan">
          <?php foreach ($KARAKTER as $kr): ?>
            <label class="avpick <?= $avatar_sekarang == $kr ? 'on' : '' ?>" title="<?= e(ucfirst($kr)) ?>">
              <input class="pilih-opsi" type="radio" name="avatar" value="<?= e($kr) ?>" <?= $avatar_sekarang == $kr ? 'checked' : '' ?>>
              <img src="assets/img/<?= e($kr) ?>.svg" alt="<?= e(ucfirst($kr)) ?>">
            </label>
          <?php endforeach; ?>
        </div>
      </div>
      <button class="btn btn-pri" type="submit">Simpan profil</button>
    </form>

    <form class="card stack" method="post" action="profil.php">
      <h3>Ganti password</h3>
      <?php if ($err_sandi != ''): ?><div class="alert err"><?= e($err_sandi) ?></div><?php endif; ?>
      <input type="hidden" name="aksi" value="sandi">
      <div class="field">
        <label for="lama">Password lama</label>
        <input class="input" type="password" id="lama" name="lama">
      </div>
      <div class="field">
        <label for="baru">Password baru</label>
        <input class="input" type="password" id="baru" name="baru" placeholder="Minimal 6 karakter">
      </div>
      <div class="field">
        <label for="konfirmasi">Ulangi password baru</label>
        <input class="input" type="password" id="konfirmasi" name="konfirmasi">
      </div>
      <button class="btn btn-sec" type="submit">Ganti password</button>
    </form>
  </div>
</div>
<?php include 'footer.php'; ?>
