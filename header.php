<?php
// Variabel opsional sebelum include: $judul (judul tab), $aktif (menu yang menyala)
$judul = isset($judul) ? $judul : 'KotakLeitner';
$aktif = isset($aktif) ? $aktif : '';
$sudah_login = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?> · KotakLeitner</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="kl">
<header class="nav">
  <div class="nav-in">
    <a class="logo" href="<?= $sudah_login ? 'dashboard.php' : 'index.php' ?>">
      <svg width="30" height="30" viewBox="0 0 30 30" aria-hidden="true"><g stroke="#2B2650" stroke-width="2.5" stroke-linejoin="round"><rect x="3" y="3" width="11" height="11" rx="3" fill="#F6A38D"/><rect x="16" y="3" width="11" height="11" rx="3" fill="#F8D56B"/><rect x="3" y="16" width="11" height="11" rx="3" fill="#8FD9BB"/><rect x="16" y="16" width="11" height="11" rx="3" fill="#5B54D6"/></g></svg>
      Kotak<b>Leitner</b>
    </a>
    <?php if ($sudah_login): ?>
      <a class="nl <?= $aktif == 'dashboard' ? 'on' : '' ?>" href="dashboard.php">Dashboard</a>
      <a class="nl <?= $aktif == 'deck' ? 'on' : '' ?>" href="deck.php">Deck Saya</a>
      <a class="nl <?= $aktif == 'progres' ? 'on' : '' ?>" href="progres.php">Progres</a>
      <a class="nl <?= $aktif == 'cari' ? 'on' : '' ?>" href="cari.php">Cari</a>
      <a class="nl <?= $aktif == 'profil' ? 'on' : '' ?>" href="profil.php"><span class="avatar"><?= e(strtoupper(substr($_SESSION['nama'], 0, 1))) ?></span><?= e(nama_depan($_SESSION['nama'])) ?> · Profil</a>
      <a class="nl out" href="logout.php">Keluar</a>
    <?php else: ?>
      <a class="nl <?= $aktif == 'login' ? 'on' : '' ?>" href="login.php">Masuk</a>
      <a class="btn btn-pri btn-sm" href="register.php">Daftar</a>
    <?php endif; ?>
  </div>
</header>
<main>
<?php tampil_flash(); ?>
