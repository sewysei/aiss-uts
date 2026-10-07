CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS nilai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    mata_kuliah VARCHAR(100) NOT NULL,
    nilai VARCHAR(5) NOT NULL,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

-- Catatan: password disimpan plaintext dengan sengaja untuk kebutuhan demo
-- brute force & SQL Injection pada mata kuliah AISS. Untuk pengembangan nyata,
-- password WAJIB disimpan dalam bentuk hash (bcrypt/password_hash()).
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'student'
);

INSERT INTO mahasiswa (nim, nama) VALUES
('211101001', 'Ahmad Fauzi'),
('211101002', 'Siti Nurhaliza'),
('211101003', 'Budi Santoso'),
('211101004', 'Dewi Lestari'),
('211101005', 'Rizky Pratama');

INSERT INTO nilai (mahasiswa_id, mata_kuliah, nilai) VALUES
(1, 'Advanced Information Security System', 'A'),
(1, 'Jaringan Komputer', 'B+'),
(2, 'Advanced Information Security System', 'A-'),
(3, 'Advanced Information Security System', 'B'),
(4, 'Advanced Information Security System', 'A'),
(5, 'Advanced Information Security System', 'B-');

-- Password sengaja lemah ('admin123') untuk mendemonstrasikan brute force login.
-- 'mahasiswa1' dipakai untuk mendemonstrasikan bypass otorisasi ke admin panel.
INSERT INTO admin (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('mahasiswa1', 'password1', 'student');
