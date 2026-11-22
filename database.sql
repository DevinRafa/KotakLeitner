-- KotakLeitner : struktur database + data contoh
-- Cara pakai: buka phpMyAdmin -> tab Import -> pilih file ini
-- (atau jalankan: mysql -u root < database.sql)

CREATE DATABASE IF NOT EXISTS kotakleitner CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE kotakleitner;

DROP TABLE IF EXISTS kartu;
DROP TABLE IF EXISTS deck;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(60) NOT NULL,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(32) NOT NULL,
  avatar VARCHAR(20) NOT NULL DEFAULT 'kucing',
  dibuat DATE NOT NULL
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

-- Akun contoh: username "demo", password "demo123"
INSERT INTO users (nama, username, password, avatar, dibuat)
VALUES ('Akun Demo', 'demo', MD5('demo123'), 'kucing', CURDATE());

INSERT INTO deck (user_id, nama, deskripsi, warna, karakter) VALUES
(1, 'Kosakata Inggris', 'Kata-kata dasar bahasa Inggris sehari-hari', 1, 'kucing'),
(1, 'Ibu Kota Dunia', 'Hafalan ibu kota negara', 3, 'bintang');

INSERT INTO kartu (deck_id, depan, belakang, kotak, jadwal) VALUES
(1, 'Apple', 'Apel', 1, CURDATE()),
(1, 'Library', 'Perpustakaan', 1, CURDATE()),
(1, 'Rabbit', 'Kelinci', 2, CURDATE()),
(1, 'Window', 'Jendela', 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(1, 'Beautiful', 'Indah / cantik', 5, DATE_ADD(CURDATE(), INTERVAL 10 DAY)),
(2, 'Jepang', 'Tokyo', 1, CURDATE()),
(2, 'Australia', 'Canberra', 2, CURDATE()),
(2, 'Mesir', 'Kairo', 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY));
