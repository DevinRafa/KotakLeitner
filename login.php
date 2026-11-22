<?php
require_once 'koneksi.php';

// Kalau sudah login, langsung ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username == '' || $password == '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $u = mysqli_real_escape_string($conn, $username);
        $p = md5($password);
        $hasil = mysqli_query($conn, "SELECT * FROM users WHERE username='$u' AND password='$p'");

        if (mysqli_num_rows($hasil) == 1) {
            $user = mysqli_fetch_assoc($hasil);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}

$judul = 'Masuk';
$aktif = 'login';
include 'header.php';
?>
<div class="wrap">
  <div class="form-narrow stack">
    <div class="center">
      <img src="assets/img/maskot-senang.svg" alt="" width="140" height="152">
      <h2>Selamat datang kembali!</h2>
      <p class="muted">Masuk untuk lanjut belajar kartumu.</p>
    </div>

    <form class="card stack" method="post" action="login.php">
      <?php if ($error != ''): ?>
        <div class="alert err"><?= e($error) ?></div>
      <?php endif; ?>

      <div class="field">
        <label for="username">Username</label>
        <input class="input" type="text" id="username" name="username" value="<?= e($username) ?>" placeholder="contoh: devin" autofocus>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input class="input" type="password" id="password" name="password" placeholder="Password kamu">
      </div>
      <button class="btn btn-pri btn-lg" type="submit">Masuk</button>
      <p class="center muted">Belum punya akun? <a class="link" href="register.php">Daftar di sini</a></p>
    </form>
  </div>
</div>
<?php include 'footer.php'; ?>
