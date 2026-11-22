<?php
// Pintu masuk khusus hosting Vercel: meneruskan permintaan ke file .php di folder utama.
// Di komputer lokal (XAMPP / php -S) file ini tidak dipakai.
$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = basename($path);
if ($file === '' || $file === 'api' || $file === 'index.php') {
    $file = 'index.php';
}
if (substr($file, -4) !== '.php' || !is_file($root . '/' . $file)) {
    http_response_code(404);
    echo 'Halaman tidak ditemukan.';
    exit;
}
chdir($root);
require $root . '/' . $file;
