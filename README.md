# KotakLeitner

Aplikasi web flashcard dengan **sistem kotak Leitner** untuk membantu belajar mandiri secara teratur. Kartu yang dijawab benar naik ke kotak berikutnya dan diulang lebih jarang, sedangkan kartu yang salah kembali ke kotak 1 dan diulang lebih sering.

Proyek akhir (Responsi 2) Praktikum Pemrograman Web I, Informatika Universitas Jenderal Soedirman.

## Tim (Kelompok 2, Shift I)

| Nama | NIM |
| --- | --- |
| Devin Rafa Haryanto | H1D025066 |
| Nashiruddin | H1D025089 |
| Revina Albani Nur Islami | H1D025073 |

## Latar Belakang

Banyak pelajar dan mahasiswa belajar dengan membaca ulang catatan menjelang ujian. Cara ini membuat materi cepat lupa, dan tidak ada yang mengingatkan bagian mana yang perlu diulang lebih sering. Belajar mandiri jadi tidak teratur, apalagi bagi yang tidak punya pendamping belajar.

**Sistem kotak Leitner** adalah metode belajar dengan kartu (flashcard) yang dikembangkan Sebastian Leitner pada 1970-an. Kartu disimpan di beberapa kotak. Kartu yang sudah dikuasai diulang semakin jarang, sedangkan kartu yang masih salah diulang lebih sering. KotakLeitner membawa metode ini ke aplikasi web sederhana, sehingga jadwal pengulangan dihitung otomatis dan pengguna cukup fokus belajar.

## Tujuan

1. Membantu pengguna membuat kartu belajar sendiri dan mengelompokkannya dalam deck.
2. Mengatur jadwal pengulangan otomatis dengan sistem kotak Leitner (kotak 1 sampai 5).
3. Menampilkan progres belajar supaya pengguna tahu kartu mana yang sudah dikuasai.
4. Menyediakan alat belajar mandiri yang gratis, ringan, dan mudah dipakai.

## Manfaat

- Belajar lebih teratur karena aplikasi menunjukkan kartu yang perlu diulang hari ini.
- Waktu belajar lebih efisien: materi yang sudah dikuasai tidak diulang terus-menerus.
- Pengguna bisa menyusun materi sendiri untuk mata pelajaran atau kuliah apa pun.

## Keterkaitan dengan SDGs

**SDG 4: Pendidikan Berkualitas.** Aplikasi ini mendukung belajar mandiri yang efektif dan bisa diakses siapa saja lewat browser, sehingga membantu pengguna menguasai materi dengan cara belajar yang terbukti terstruktur. Ini sejalan dengan tujuan SDG 4 untuk memastikan pendidikan yang berkualitas dan kesempatan belajar bagi semua orang.

## Cara Kerja Kotak Leitner

| Kotak | Diulang setiap |
| --- | --- |
| 1 | 1 hari |
| 2 | 2 hari |
| 3 | 4 hari |
| 4 | 7 hari |
| 5 | 14 hari (dianggap dikuasai) |

- Kartu baru masuk ke **Kotak 1**.
- Jawaban **benar**: kartu naik satu kotak (maksimal Kotak 5).
- Jawaban **salah**: kartu kembali ke Kotak 1.
- Jadwal ulang kartu = hari ini + interval kotak barunya.

## Fitur / Modul

| No | Modul | Keterangan |
| --- | --- | --- |
| 1 | Registrasi Akun | Daftar akun baru, cek username sudah dipakai |
| 2 | Login, Logout, dan Session | Autentikasi user dan proteksi halaman |
| 3 | Dashboard | Ringkasan jumlah deck, kartu, dan kartu yang perlu diulang hari ini |
| 4 | Kelola Deck | Tambah, lihat, ubah, hapus deck (CRUD) |
| 5 | Kelola Kartu | Tambah, lihat, ubah, hapus kartu di dalam deck (CRUD) |
| 6 | Mode Belajar | Kartu dibalik (flip), jawab benar/salah, kartu pindah kotak Leitner |
| 7 | Progres Belajar | Sebaran kartu di kotak 1 sampai 5 per deck |
| 8 | Pencarian Kartu | Cari kartu berdasarkan kata kunci |
| 9 | Profil Pengguna | Lihat dan ubah nama serta password |

## Teknologi

- HTML, CSS, JavaScript (native)
- PHP (native)
- MySQL / MariaDB (XAMPP)

Tanpa framework dan tanpa library.

## Struktur Folder

| File / Folder | Fungsi |
| --- | --- |
| `database.sql` | Struktur tabel dan data contoh |
| `koneksi.php` | Koneksi database, session, dan fungsi bantu |
| `header.php`, `footer.php` | Bagian atas dan bawah semua halaman |
| `cek_login.php` | Penjaga halaman yang wajib login |
| `index.php` | Halaman awal |
| `register.php`, `login.php`, `logout.php` | Registrasi, login, logout |
| `dashboard.php` | Dashboard |
| `deck.php`, `kartu.php` | Kelola deck dan kartu |
| `belajar.php` | Mode belajar |
| `progres.php` | Progres belajar |
| `cari.php` | Pencarian kartu |
| `profil.php` | Profil pengguna |
| `404.php` | Halaman tidak ditemukan |
| `assets/` | CSS, JavaScript, dan gambar |

## Instalasi dan Konfigurasi

Kebutuhan: [XAMPP](https://www.apachefriends.org/) (Apache, PHP, dan MySQL/MariaDB) dan browser.

1. Salin folder proyek ini ke `C:\xampp\htdocs\KotakLeitner` (di Linux/macOS: folder `htdocs` XAMPP).
2. Buka XAMPP Control Panel, lalu jalankan **Apache** dan **MySQL**.
3. Buka `http://localhost/phpmyadmin`, pilih tab **Import**, pilih file `database.sql`, lalu klik **Import**. File ini otomatis membuat database `kotakleitner` beserta tabel dan data contoh.
4. Konfigurasi koneksi ada di `koneksi.php`. Nilai bawaannya cocok dengan XAMPP standar, jadi tidak perlu diubah:

| Pengaturan | Nilai bawaan |
| --- | --- |
| Host | `localhost` |
| User | `root` |
| Password | (kosong) |
| Database | `kotakleitner` |
| Port | `3306` |

## Menjalankan Aplikasi

1. Pastikan Apache dan MySQL di XAMPP menyala.
2. Buka `http://localhost/KotakLeitner/` di browser.
3. Masuk dengan akun contoh, atau daftar akun baru:

| Username | Password |
| --- | --- |
| `demo` | `demo123` |

Alur singkat: buat deck, tambah kartu, klik **Mulai belajar**, balik kartu, lalu pilih **Aku benar!** atau **Belum tepat**. Lihat sebaran kartu di halaman **Progres**.

## Alur Kontribusi

1. Jangan push langsung ke `main` (branch `main` diproteksi).
2. Buat branch baru dari `main`, contoh: `modul-8-pencarian`.
3. Commit di branch sendiri, lalu push branch tersebut.
4. Buka Pull Request ke `main` dan isi template PR.
5. PR wajib di-review dan disetujui oleh @DevinRafa sebelum di-merge.
