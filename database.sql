-- KotakLeitner : struktur database + data contoh
-- Cara pakai: buka phpMyAdmin -> tab Import -> pilih file ini
-- (atau jalankan: mysql -u root < database.sql)

CREATE DATABASE IF NOT EXISTS kotakleitner CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE kotakleitner;
SET NAMES utf8mb4;

DROP TABLE IF EXISTS kartu;
DROP TABLE IF EXISTS deck;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS sesi;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(60) NOT NULL,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(32) NOT NULL,
  avatar VARCHAR(20) NOT NULL DEFAULT 'kucing',
  dibuat TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE deck (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  nama VARCHAR(60) NOT NULL,
  deskripsi VARCHAR(200) NOT NULL DEFAULT '',
  warna TINYINT NOT NULL DEFAULT 1,
  karakter VARCHAR(20) NOT NULL DEFAULT 'kucing',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE kartu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  deck_id INT NOT NULL,
  depan VARCHAR(200) NOT NULL,
  belakang VARCHAR(300) NOT NULL,
  kotak TINYINT NOT NULL DEFAULT 1,
  jadwal DATE NOT NULL,
  FOREIGN KEY (deck_id) REFERENCES deck(id) ON DELETE CASCADE
);

-- Dipakai hanya saat di-hosting (lihat session_db.php); di XAMPP boleh diabaikan
CREATE TABLE sesi (
  id VARCHAR(128) PRIMARY KEY,
  data TEXT NOT NULL,
  diperbarui TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Akun contoh: username "demo", password "demo123"
INSERT INTO users (nama, username, password, avatar)
VALUES ('Akun Demo', 'demo', MD5('demo123'), 'kucing');

INSERT INTO deck (user_id, nama, deskripsi, warna, karakter) VALUES
(1, 'Kosakata Inggris', 'Kata-kata dasar bahasa Inggris sehari-hari', 1, 'kucing'),
(1, 'Ibu Kota Dunia', 'Hafalan ibu kota negara', 3, 'bintang'),
(1, 'Dasar Pemrograman Web', 'HTML, CSS, JavaScript, PHP, dan SQL dari modul praktikum', 5, 'lampu'),
(1, 'Sains Dasar', 'Fakta ilmu pengetahuan alam yang sering keluar di ujian', 4, 'roket'),
(1, 'Sejarah Indonesia', 'Tokoh dan peristiwa penting menuju kemerdekaan', 2, 'beruang'),
(1, 'Rumus Matematika', 'Rumus bangun datar dan bangun ruang', 6, 'kelinci');

INSERT INTO kartu (deck_id, depan, belakang, kotak, jadwal) VALUES
(1, 'Apple', 'Apel', 1, CURDATE()),
(1, 'Library', 'Perpustakaan', 1, CURDATE()),
(1, 'Rabbit', 'Kelinci', 2, CURDATE()),
(1, 'Window', 'Jendela', 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(1, 'Beautiful', 'Indah / cantik', 5, DATE_ADD(CURDATE(), INTERVAL 10 DAY)),
(1, 'Kitchen', 'Dapur', 1, CURDATE()),
(1, 'Neighbor', 'Tetangga', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(1, 'Borrow', 'Meminjam', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(1, 'Weather', 'Cuaca', 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(1, 'Ticket', 'Tiket', 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY)),
(1, 'Breakfast', 'Sarapan', 4, DATE_ADD(CURDATE(), INTERVAL 6 DAY)),
(1, 'Umbrella', 'Payung', 1, CURDATE()),
(2, 'Jepang', 'Tokyo', 1, CURDATE()),
(2, 'Australia', 'Canberra', 2, CURDATE()),
(2, 'Mesir', 'Kairo', 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY)),
(2, 'Brasil', 'Brasilia', 1, CURDATE()),
(2, 'Kanada', 'Ottawa', 1, CURDATE()),
(2, 'Korea Selatan', 'Seoul', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(2, 'Thailand', 'Bangkok', 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(2, 'Turki', 'Ankara', 1, CURDATE()),
(2, 'Jerman', 'Berlin', 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(2, 'Argentina', 'Buenos Aires', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(2, 'Maroko', 'Rabat', 4, DATE_ADD(CURDATE(), INTERVAL 6 DAY)),
(2, 'Selandia Baru', 'Wellington', 5, DATE_ADD(CURDATE(), INTERVAL 11 DAY)),
(3, 'Tag HTML untuk membuat tautan', '<a>', 5, DATE_ADD(CURDATE(), INTERVAL 9 DAY)),
(3, 'Atribut untuk alamat tujuan tautan', 'href', 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY)),
(3, 'Properti CSS untuk warna teks', 'color', 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(3, 'Properti CSS untuk jarak di dalam elemen', 'padding', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(3, 'Fungsi PHP untuk mencetak teks', 'echo', 1, CURDATE()),
(3, 'Variabel PHP diawali dengan simbol apa?', '$ (tanda dolar)', 1, CURDATE()),
(3, 'Fungsi JavaScript untuk mengambil elemen berdasarkan id', 'document.getElementById()', 2, CURDATE()),
(3, 'Variabel PHP untuk data form dengan method POST', '$_POST', 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(3, 'Fungsi PHP untuk memulai session', 'session_start()', 1, CURDATE()),
(3, 'Perintah SQL untuk menambah data', 'INSERT INTO', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(3, 'Perintah SQL untuk membaca data', 'SELECT', 4, DATE_ADD(CURDATE(), INTERVAL 6 DAY)),
(3, 'Method form yang tidak menampilkan data di URL', 'POST', 1, CURDATE()),
(4, 'Planet terdekat dari Matahari', 'Merkurius', 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(4, 'Gas yang kita hirup untuk bernapas', 'Oksigen', 1, CURDATE()),
(4, 'Rumus kimia air', 'H2O', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(4, 'Organ yang memompa darah', 'Jantung', 1, CURDATE()),
(4, 'Proses tumbuhan membuat makanan dengan bantuan cahaya', 'Fotosintesis', 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY)),
(4, 'Satuan gaya dalam SI', 'Newton', 2, CURDATE()),
(4, 'Planet terbesar di tata surya', 'Jupiter', 5, DATE_ADD(CURDATE(), INTERVAL 10 DAY)),
(4, 'Gas yang diserap tumbuhan saat fotosintesis', 'Karbon dioksida (CO2)', 1, CURDATE()),
(4, 'Lambang unsur emas', 'Au', 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(4, 'Titik didih air pada tekanan normal', '100 derajat Celsius', 4, DATE_ADD(CURDATE(), INTERVAL 6 DAY)),
(5, 'Tanggal proklamasi kemerdekaan Indonesia', '17 Agustus 1945', 1, CURDATE()),
(5, 'Presiden pertama Indonesia', 'Soekarno', 2, CURDATE()),
(5, 'Wakil presiden pertama Indonesia', 'Mohammad Hatta', 1, CURDATE()),
(5, 'Tokoh yang mengetik naskah proklamasi', 'Sayuti Melik', 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(5, 'Sumpah Pemuda diikrarkan pada tahun', '1928', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(5, 'Budi Utomo berdiri pada tahun', '1908', 4, DATE_ADD(CURDATE(), INTERVAL 6 DAY)),
(5, 'Hari Pahlawan diperingati setiap tanggal', '10 November', 1, CURDATE()),
(5, 'Konferensi Asia Afrika 1955 diadakan di kota', 'Bandung', 5, DATE_ADD(CURDATE(), INTERVAL 11 DAY)),
(5, 'Candi Buddha terbesar di Indonesia', 'Borobudur', 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY)),
(5, 'Kerajaan Hindu tertua di Indonesia', 'Kutai', 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(6, 'Luas lingkaran', 'pi x r x r', 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(6, 'Keliling lingkaran', '2 x pi x r', 1, CURDATE()),
(6, 'Luas segitiga', '1/2 x alas x tinggi', 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(6, 'Teorema Pythagoras', 'a kuadrat + b kuadrat = c kuadrat', 1, CURDATE()),
(6, 'Volume kubus dengan rusuk s', 's x s x s', 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY)),
(6, 'Luas persegi panjang', 'panjang x lebar', 2, CURDATE()),
(6, 'Volume balok', 'panjang x lebar x tinggi', 1, CURDATE()),
(6, 'Jumlah sudut dalam segitiga', '180 derajat', 5, DATE_ADD(CURDATE(), INTERVAL 9 DAY)),
(6, 'Luas trapesium', '1/2 x (jumlah sisi sejajar) x tinggi', 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(6, 'Luas jajar genjang', 'alas x tinggi', 4, DATE_ADD(CURDATE(), INTERVAL 6 DAY));
