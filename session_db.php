<?php
// Khusus hosting: server tidak punya penyimpanan tetap, jadi session disimpan di tabel `sesi`
function sesi_buka() { return true; }
function sesi_tutup() { return true; }

function sesi_baca($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $hasil = mysqli_query($conn, "SELECT data FROM sesi WHERE id = '$id'");
    $baris = mysqli_fetch_assoc($hasil);
    return $baris ? $baris['data'] : '';
}

function sesi_tulis($id, $data) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $data = mysqli_real_escape_string($conn, $data);
    mysqli_query($conn, "REPLACE INTO sesi (id, data) VALUES ('$id', '$data')");
    return true;
}

function sesi_hapus($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    mysqli_query($conn, "DELETE FROM sesi WHERE id = '$id'");
    return true;
}

function sesi_bersih($umur) {
    global $conn;
    $umur = intval($umur);
    mysqli_query($conn, "DELETE FROM sesi WHERE diperbarui < NOW() - INTERVAL $umur SECOND");
    return true;
}

session_set_save_handler('sesi_buka', 'sesi_tutup', 'sesi_baca', 'sesi_tulis', 'sesi_hapus', 'sesi_bersih');
register_shutdown_function('session_write_close');
