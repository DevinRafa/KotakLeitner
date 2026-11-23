<?php
// Pintu masuk khusus hosting Vercel: meneruskan permintaan ke file .php di folder utama.
// Di komputer lokal (XAMPP / php -S) file ini tidak dipakai.
$root = dirname(__DIR__);
$file = basename($_GET['f'] ?? 'index.php');
unset($_GET['f'], $_REQUEST['f']);
if (substr($file, -4) !== '.php' || !is_file($root . '/' . $file)) {
    $file = '404.php';
}
chdir($root);
require $root . '/' . $file;
