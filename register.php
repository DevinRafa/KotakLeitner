<?php
require_once 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$nama = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($nama == '') {
        $errors['nama'] = 'Nama wajib diisi.';
    }
    if ($username == '') {
        $errors['username'] = 'Username wajib diisi.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $errors['username'] = 'Username 3-30 karakter, hanya huruf, angka, dan garis bawah.';
    }
    if (strlen($password) < 6) {
        $errors['password'] = 'Password minimal 6 karakter.';
    }
    if ($password != $konfirmasi) {
        $errors['konfirmasi'] = 'Konfirmasi password tidak sama.';
    }

    // Cek username sudah dipakai atau belum
    if (!isset($errors['username'])) {
        $u = mysqli_real_escape_string($conn, $username);
        $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='$u'");
        if (mysqli_num_rows($cek) > 0) {
            $errors['username'] = 'Username sudah dipakai, coba yang lain.';
        }
    }

    if (count($errors) == 0) {
        $n = mysqli_real_escape_string($conn, $nama);
        $p = md5($password);
        mysqli_query($conn, "INSERT INTO users (nama, username, password) VALUES ('$n', '$u', '$p')");
        set_flash('ok', 'Akun berhasil dibuat. Silakan masuk.');
        header('Location: login.php');
        exit;
    }
}

function cls($errors, $f) { return isset($errors[$f]) ? ' err' : ''; }
function pesan($errors, $f) {
    return isset($errors[$f]) ? '<div class="msg-err">' . e($errors[$f]) . '</div>' : '';
}

$judul = 'Daftar';
$aktif = '';
include 'header.php';
?>
<div class="wrap">
  <div class="form-narrow stack">
    <div class="center">
      <img src="assets/img/maskot-senang.svg" alt="" width="140" height="152">
      <h2>Buat akunmu</h2>
      <p class="muted">Gratis dan cuma butuh semenit.</p>
    </div>

    <form class="card stack" method="post" action="register.php">
      <div class="field">
        <label for="nama">Nama</label>
        <input class="input<?= cls($errors, 'nama') ?>" type="text" id="nama" name="nama" value="<?= e($nama) ?>" placeholder="Nama lengkapmu">
        <?= pesan($errors, 'nama') ?>
      </div>
      <div class="field">
        <label for="username">Username</label>
        <input class="input<?= cls($errors, 'username') ?>" type="text" id="username" name="username" value="<?= e($username) ?>" placeholder="contoh: devin_01">
        <?= pesan($errors, 'username') ?>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input class="input<?= cls($errors, 'password') ?>" type="password" id="password" name="password" placeholder="Minimal 6 karakter">
        <?= pesan($errors, 'password') ?>
      </div>
      <div class="field">
        <label for="konfirmasi">Ulangi password</label>
        <input class="input<?= cls($errors, 'konfirmasi') ?>" type="password" id="konfirmasi" name="konfirmasi" placeholder="Ketik ulang password">
        <?= pesan($errors, 'konfirmasi') ?>
      </div>
      <button class="btn btn-pri btn-lg" type="submit">Daftar</button>
      <p class="center muted">Sudah punya akun? <a class="link" href="login.php">Masuk</a></p>
    </form>
  </div>
</div>
<?php include 'footer.php'; ?>
