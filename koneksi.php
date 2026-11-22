<?php
// Koneksi database + fungsi bantu yang dipakai semua halaman
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'kotakleitner');
if (!$conn) {
    die('Koneksi database gagal. Pastikan MySQL menyala dan database.sql sudah diimport.');
}
mysqli_set_charset($conn, 'utf8mb4');

// Interval pengulangan tiap kotak (hari)
$INTERVAL = [1 => 1, 2 => 2, 3 => 4, 4 => 7, 5 => 14];

// Karakter yang boleh dipakai untuk avatar & sampul deck
$KARAKTER = ['kucing', 'burung', 'bintang', 'apel', 'ikan', 'beruang', 'kelinci', 'monster', 'lampu', 'roket', 'bunga'];

// Singkat htmlspecialchars supaya aman dari XSS
function e($teks) {
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

// Pesan sekali tampil (berhasil / gagal) setelah redirect
function set_flash($tipe, $pesan) {
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function tampil_flash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="wrap" style="margin-bottom:20px"><div class="alert ' . e($f['tipe']) . '">' . e($f['pesan']) . '</div></div>';
    }
}

// Lencana kotak 1-5
function badge_kotak($k) {
    return '<span class="bdg k' . (int)$k . '"><i>' . (int)$k . '</i>Kotak ' . (int)$k . '</span>';
}

// Nama depan untuk sapaan di navbar
function nama_depan($nama) {
    $bagian = explode(' ', trim($nama));
    return $bagian[0];
}
