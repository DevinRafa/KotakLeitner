<?php
// Taruh di baris paling atas halaman yang butuh login
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    set_flash('info', 'Silakan masuk dulu ya.');
    header('Location: login.php');
    exit;
}
