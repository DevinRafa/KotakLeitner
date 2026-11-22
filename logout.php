<?php
require_once 'koneksi.php';

// Hapus semua data session lalu kembali ke landing
session_unset();
session_destroy();

session_start();
set_flash('ok', 'Kamu sudah keluar. Sampai jumpa!');
header('Location: index.php');
exit;
