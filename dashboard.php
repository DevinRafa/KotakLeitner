<?php
require_once 'cek_login.php';
$judul = 'Dashboard';
$aktif = 'dashboard';
include 'header.php';
?>
<div class="wrap"><h2>Halo, <?= e($_SESSION['nama']) ?>!</h2></div>
<?php include 'footer.php'; ?>
