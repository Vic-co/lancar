-- ============================================
-- FILE: TEST_DATA_DAN_QUERY.sql
-- DOKUMENTASI QUERY MYSQL UNTUK UKK RPL
-- ============================================

-- ============================================
-- 1. TEST DATA - INSERT SAMPLE DATA
-- ============================================

-- Insert admin
INSERT INTO admin (username, password) 
VALUES ('admin', 'admin123');

-- Insert kategori
INSERT INTO kategori (ket_kategori) VALUES 
('Kursi/Meja Rusak'),
('Papan Tulis Rusak'),
('Kaca Pecah'),
('Pintu Rusak'),
('Atap Bocor'),
('AC Tidak Berfungsi'),
('Lampu Mati'),
('Saluran Air Tersumbat');

-- Insert siswa
INSERT INTO siswa (nis, kelas) VALUES
(12345, 'XII RPL'),
(12346, 'XII RPL'),
(12347, 'XII TKJ'),
(12348, 'XI ATPH'),
(12349, 'XI ATPH');

-- Insert aspirasi
INSERT INTO aspirasi (status, id_kategori, feedback) VALUES
('Menunggu', 1, NULL),
('Proses', 2, 'Papan tulis sedang diperbaiki, estimasi selesai hari Jumat'),
('Selesai', 3, 'Kaca sudah diganti dengan kaca baru'),
('Menunggu', 4, NULL),
('Proses', 5, 'Atap sedang diperbaiki oleh tukang bangunan');

-- Insert input_aspirasi
INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket) VALUES
(12345, 1, 'Ruang Kelas XII RPL', 'Kursi dan meja di pojok rusak, tidak bisa digunakan dengan baik'),
(12346, 2, 'Ruang Kelas XII RPL', 'Papan tulis sudah aus dan sulit dihapus'),
(12347, 3, 'Ruang Kelas XII TKJ', 'Jendela kaca pecah, berbahaya bagi siswa'),
(12348, 4, 'Ruang Kelas XI ATPH', 'Pintu masuk rusak, handle patah dan sulit dibuka'),
(12349, 5, 'Perpustakaan', 'Atap bocor saat hujan, air menetes ke koleksi buku');

-- ============================================
-- 2. QUERY SELECT - MENGAMBIL DATA
-- ============================================

-- Query 1: Ambil semua aspirasi dengan detail lengkap
SELECT a.id_aspirasi, a.status, a.id_kategori, a.feedback, 
       ia.id_pelaporan, ia.nis, ia.lokasi, ia.ket,
       k.ket_kategori, s.kelas
FROM aspirasi a
LEFT JOIN input_aspirasi ia ON a.id_kategori = ia.id_kategori
LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
LEFT JOIN siswa s ON ia.nis = s.nis
ORDER BY a.id_aspirasi DESC;

-- Query 2: Ambil aspirasi dengan status tertentu (Menunggu)
SELECT a.id_aspirasi, a.status, a.feedback, ia.nis, ia.lokasi, 
       ia.ket, k.ket_kategori
FROM aspirasi a
LEFT JOIN input_aspirasi ia ON a.id_kategori = ia.id_kategori
LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
WHERE a.status = 'Menunggu'
ORDER BY a.id_aspirasi DESC;

-- Query 3: Hitung jumlah aspirasi per status
SELECT status, COUNT(*) as jumlah
FROM aspirasi
GROUP BY status;

-- Query 4: Ambil aspirasi milik siswa tertentu
SELECT ia.id_pelaporan, a.id_aspirasi, a.status, a.feedback,
       ia.lokasi, ia.ket, k.ket_kategori
FROM input_aspirasi ia
LEFT JOIN aspirasi a ON ia.id_kategori = a.id_kategori
LEFT JOIN kategori k ON ia.id_kategori = k.id_kategori
WHERE ia.nis = 12345
ORDER BY ia.id_pelaporan DESC;

-- Query 5: Ambil aspirasi berdasarkan kategori
SELECT a.id_aspirasi, a.status, a.feedback, ia.nis, ia.lokasi, 
       ia.ket, k.ket_kategori, s.kelas
FROM aspirasi a
LEFT JOIN input_aspirasi ia ON a.id_kategori = ia.id_kategori
LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
LEFT JOIN siswa s ON ia.nis = s.nis
WHERE a.id_kategori = 1
ORDER BY a.id_aspirasi DESC;

-- Query 6: Ambil semua kategori
SELECT * FROM kategori ORDER BY ket_kategori ASC;

-- Query 7: Ambil detail siswa
SELECT * FROM siswa WHERE nis = 12345;

-- Query 8: Ambil aspirasi yang belum diberi feedback
SELECT a.id_aspirasi, a.status, ia.nis, ia.lokasi, ia.ket, k.ket_kategori
FROM aspirasi a
LEFT JOIN input_aspirasi ia ON a.id_kategori = ia.id_kategori
LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
WHERE a.feedback IS NULL
ORDER BY a.id_aspirasi;

-- Query 9: Ambil aspirasi selesai
SELECT a.id_aspirasi, a.status, a.feedback, ia.nis, ia.lokasi, 
       ia.ket, k.ket_kategori, s.kelas
FROM aspirasi a
LEFT JOIN input_aspirasi ia ON a.id_kategori = ia.id_kategori
LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
LEFT JOIN siswa s ON ia.nis = s.nis
WHERE a.status = 'Selesai'
ORDER BY a.id_aspirasi DESC;

-- Query 10: Hitung total aspirasi per kategori
SELECT k.ket_kategori, COUNT(a.id_aspirasi) as total
FROM aspirasi a
RIGHT JOIN kategori k ON a.id_kategori = k.id_kategori
GROUP BY a.id_kategori, k.ket_kategori
ORDER BY total DESC;

-- ============================================
-- 3. QUERY INSERT - MENAMBAH DATA
-- ============================================

-- Insert aspirasi baru
INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket)
VALUES (12345, 1, 'Ruang Kelas XII RPL', 'Kursi dan meja di pojok rusak');

-- Setelah insert ke input_aspirasi, insert ke aspirasi dengan status default
INSERT INTO aspirasi (id_kategori)
VALUES (1);

-- Insert dengan semua field
INSERT INTO aspirasi (status, id_kategori, feedback)
VALUES ('Menunggu', 1, NULL);

-- ============================================
-- 4. QUERY UPDATE - MENGUBAH DATA
-- ============================================

-- Update status aspirasi
UPDATE aspirasi 
SET status = 'Proses' 
WHERE id_aspirasi = 1;

-- Update status ke Selesai
UPDATE aspirasi 
SET status = 'Selesai' 
WHERE id_aspirasi = 2;

-- Update feedback aspirasi
UPDATE aspirasi 
SET feedback = 'Papan tulis sudah diperbaiki dengan baik. Terima kasih atas laporannya!' 
WHERE id_aspirasi = 2;

-- Update status dan feedback sekaligus
UPDATE aspirasi 
SET status = 'Proses', feedback = 'Sedang dalam proses perbaikan' 
WHERE id_aspirasi = 1;

-- ============================================
-- 5. QUERY DELETE - MENGHAPUS DATA
-- ============================================

-- Hapus aspirasi berdasarkan ID
DELETE FROM aspirasi 
WHERE id_aspirasi = 5;

-- Hapus semua aspirasi dengan status Menunggu (HATI-HATI!)
-- DELETE FROM aspirasi WHERE status = 'Menunggu';

-- ============================================
-- 6. QUERY KOMPLEKS - UNTUK LAPORAN
-- ============================================

-- Query Laporan: Aspirasi per Siswa
SELECT s.nis, s.kelas, COUNT(a.id_aspirasi) as total_aspirasi,
       SUM(CASE WHEN a.status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
       SUM(CASE WHEN a.status = 'Proses' THEN 1 ELSE 0 END) as proses,
       SUM(CASE WHEN a.status = 'Selesai' THEN 1 ELSE 0 END) as selesai
FROM siswa s
LEFT JOIN input_aspirasi ia ON s.nis = ia.nis
LEFT JOIN aspirasi a ON ia.id_kategori = a.id_kategori
GROUP BY s.nis, s.kelas;

-- Query Laporan: Aspirasi per Kategori
SELECT k.id_kategori, k.ket_kategori,
       COUNT(a.id_aspirasi) as total,
       SUM(CASE WHEN a.status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
       SUM(CASE WHEN a.status = 'Proses' THEN 1 ELSE 0 END) as proses,
       SUM(CASE WHEN a.status = 'Selesai' THEN 1 ELSE 0 END) as selesai
FROM kategori k
LEFT JOIN aspirasi a ON k.id_kategori = a.id_kategori
GROUP BY k.id_kategori, k.ket_kategori;

-- Query: Statistik Aspirasi
SELECT 
    (SELECT COUNT(*) FROM aspirasi) as total,
    (SELECT COUNT(*) FROM aspirasi WHERE status = 'Menunggu') as menunggu,
    (SELECT COUNT(*) FROM aspirasi WHERE status = 'Proses') as proses,
    (SELECT COUNT(*) FROM aspirasi WHERE status = 'Selesai') as selesai;

-- Query: Aspirasi dengan feedback terlengkap
SELECT a.id_aspirasi, a.status, ia.nis, ia.lokasi, k.ket_kategori,
       CHAR_LENGTH(a.feedback) as panjang_feedback
FROM aspirasi a
LEFT JOIN input_aspirasi ia ON a.id_kategori = ia.id_kategori
LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
WHERE a.feedback IS NOT NULL
ORDER BY CHAR_LENGTH(a.feedback) DESC;

-- ============================================
-- 7. PROCEDURE - UNTUK OPERASI BERULANG
-- ============================================

-- Procedure: Tambah Aspirasi Baru
DELIMITER //
CREATE PROCEDURE tambah_aspirasi (
    IN p_nis INT,
    IN p_id_kategori INT,
    IN p_lokasi VARCHAR(50),
    IN p_ket VARCHAR(255)
)
BEGIN
    INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket)
    VALUES (p_nis, p_id_kategori, p_lokasi, p_ket);
    
    INSERT INTO aspirasi (id_kategori)
    VALUES (p_id_kategori);
    
    SELECT 'Aspirasi berhasil ditambahkan!' as pesan;
END //
DELIMITER ;

-- Panggil procedure:
-- CALL tambah_aspirasi(12345, 1, 'Ruang Kelas', 'Kursi rusak');

-- ============================================
-- 8. FUNCTION - UNTUK PERHITUNGAN
-- ============================================

-- Function: Hitung aspirasi per status
DELIMITER //
CREATE FUNCTION hitung_aspirasi_status(p_status VARCHAR(20))
RETURNS INT
READS SQL DATA
BEGIN
    DECLARE jumlah INT;
    SELECT COUNT(*) INTO jumlah FROM aspirasi WHERE status = p_status;
    RETURN jumlah;
END //
DELIMITER ;

-- Panggil function:
-- SELECT hitung_aspirasi_status('Selesai') as total_selesai;

-- ============================================
-- 9. VIEW - UNTUK QUERY YANG SERING DIGUNAKAN
-- ============================================

-- View: Semua aspirasi dengan detail
CREATE VIEW v_aspirasi_detail AS
SELECT a.id_aspirasi, a.status, a.feedback, 
       ia.id_pelaporan, ia.nis, ia.lokasi, ia.ket,
       k.ket_kategori, s.kelas
FROM aspirasi a
LEFT JOIN input_aspirasi ia ON a.id_kategori = ia.id_kategori
LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
LEFT JOIN siswa s ON ia.nis = s.nis;

-- Gunakan view:
-- SELECT * FROM v_aspirasi_detail WHERE status = 'Menunggu';

-- View: Statistik aspirasi
CREATE VIEW v_statistik_aspirasi AS
SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
    SUM(CASE WHEN status = 'Proses' THEN 1 ELSE 0 END) as proses,
    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai
FROM aspirasi;

-- Gunakan view:
-- SELECT * FROM v_statistik_aspirasi;

-- ============================================
-- 10. INDEX - UNTUK OPTIMASI QUERY
-- ============================================

-- Create index pada field yang sering dicari
CREATE INDEX idx_aspirasi_status ON aspirasi(status);
CREATE INDEX idx_aspirasi_kategori ON aspirasi(id_kategori);
CREATE INDEX idx_input_aspirasi_nis ON input_aspirasi(nis);
CREATE INDEX idx_input_aspirasi_kategori ON input_aspirasi(id_kategori);

-- ============================================
-- CATATAN PENTING
-- ============================================
/*
1. Semua Query di atas sudah ditest dan berfungsi dengan baik
2. Gunakan prepared statements untuk security lebih baik
3. Untuk production, gunakan password hashing (password_hash)
4. Implementasi di aplikasi sudah menggunakan fungsi keamanan()
5. Procedure dan function opsional, bisa diganti dengan query di PHP
6. View berguna untuk query kompleks yang sering digunakan
7. Index penting untuk performa query yang lebih cepat
*/

-- ============================================
-- END OF FILE
-- ============================================
