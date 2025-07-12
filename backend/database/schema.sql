-- Tạo cơ sở dữ liệu và các bảng, thêm dữ liệu mẫu, 10 thuốc mới, lô thuốc mới, cập nhật ảnh
CREATE DATABASE IF NOT EXISTS nhom5_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nhom5_demo;

-- Bảng THUOC
CREATE TABLE IF NOT EXISTS THUOC (
    ma_thuoc INT AUTO_INCREMENT PRIMARY KEY,
    ten_thuoc NVARCHAR(100) NOT NULL,
    don_vi VARCHAR(10) NOT NULL,
    gia_nhap FLOAT NOT NULL,
    gia_ban FLOAT NOT NULL,
    ngay_them DATE NOT NULL,
    sl_toi_thieu INT NOT NULL DEFAULT 10,
    anh VARCHAR(255) NULL
);

-- Bảng LO_THUOC
CREATE TABLE IF NOT EXISTS LO_THUOC (
    ma_lo INT AUTO_INCREMENT PRIMARY KEY,
    ma_thuoc INT NOT NULL,
    so_luong INT NOT NULL,
    ngay_nhap DATE NOT NULL,
    han_su_dung DATE NOT NULL,
    trang_thai VARCHAR(20) NOT NULL DEFAULT 'Còn hạn',
    FOREIGN KEY (ma_thuoc) REFERENCES THUOC(ma_thuoc) ON DELETE CASCADE
);

-- Bảng NHAN_VIEN
CREATE TABLE IF NOT EXISTS NHAN_VIEN (
    ma_nv INT AUTO_INCREMENT PRIMARY KEY,
    ten_nv NVARCHAR(100) NOT NULL,
    chuc_vu VARCHAR(20) NOT NULL,
    sdt VARCHAR(10) NOT NULL,
    dia_chi VARCHAR(255) NOT NULL,
    trang_thai VARCHAR(20) NOT NULL DEFAULT 'Đang làm việc'
);

-- Bảng KHO_XUAT_NHAP
CREATE TABLE IF NOT EXISTS KHO_XUAT_NHAP (
    ma_kho INT AUTO_INCREMENT PRIMARY KEY,
    ma_lo INT NOT NULL,
    ma_nv INT NOT NULL,
    loai VARCHAR(20) NOT NULL,
    so_luong INT NOT NULL,
    thoi_gian DATETIME NOT NULL,
    ghi_chu NVARCHAR(255) NOT NULL,
    FOREIGN KEY (ma_lo) REFERENCES LO_THUOC(ma_lo) ON DELETE CASCADE,
    FOREIGN KEY (ma_nv) REFERENCES NHAN_VIEN(ma_nv) ON DELETE CASCADE
);

-- Bảng KHACH_HANG
CREATE TABLE IF NOT EXISTS KHACH_HANG (
    ma_kh INT AUTO_INCREMENT PRIMARY KEY,
    ten_kh NVARCHAR(100) NOT NULL,
    ngay_sinh DATE NULL,
    sdt VARCHAR(10) NOT NULL,
    dia_chi NVARCHAR(255) NULL,
    di_ung NVARCHAR(255) NULL
);

-- Bảng DON_THUOC
CREATE TABLE IF NOT EXISTS DON_THUOC (
    ma_don INT AUTO_INCREMENT PRIMARY KEY,
    ma_kh INT NOT NULL,
    ma_nv INT NOT NULL,
    ngay_tao DATETIME NOT NULL,
    da_thanh_toan VARCHAR(20) NOT NULL DEFAULT 'Chưa thanh toán',
    FOREIGN KEY (ma_kh) REFERENCES KHACH_HANG(ma_kh) ON DELETE CASCADE,
    FOREIGN KEY (ma_nv) REFERENCES NHAN_VIEN(ma_nv) ON DELETE CASCADE
);

-- Bảng CHI_TIET_DON_THUOC
CREATE TABLE IF NOT EXISTS CHI_TIET_DON_THUOC (
    ma_don INT NOT NULL,
    ma_thuoc INT NOT NULL,
    lieu_luong NVARCHAR(100) NOT NULL,
    so_ngay INT NOT NULL,
    so_luong INT NOT NULL,
    PRIMARY KEY (ma_don, ma_thuoc),
    FOREIGN KEY (ma_don) REFERENCES DON_THUOC(ma_don) ON DELETE CASCADE,
    FOREIGN KEY (ma_thuoc) REFERENCES THUOC(ma_thuoc) ON DELETE CASCADE
);

-- Bảng HOA_DON
CREATE TABLE IF NOT EXISTS HOA_DON (
    ma_hd INT AUTO_INCREMENT PRIMARY KEY,
    don_thuoc_id INT NOT NULL,
    ma_nv INT NOT NULL,
    tong_tien FLOAT NOT NULL,
    thoi_gian DATETIME NOT NULL,
    phuong_thuc VARCHAR(20) NOT NULL DEFAULT 'Tiền mặt',
    FOREIGN KEY (don_thuoc_id) REFERENCES DON_THUOC(ma_don) ON DELETE CASCADE,
    FOREIGN KEY (ma_nv) REFERENCES NHAN_VIEN(ma_nv) ON DELETE CASCADE
);

-- Bảng BAO_CAO
CREATE TABLE IF NOT EXISTS BAO_CAO (
    ma_bc INT AUTO_INCREMENT PRIMARY KEY,
    ma_nv INT NOT NULL,
    loai VARCHAR(50) NOT NULL,
    thoi_gian DATETIME NOT NULL,
    dinh_dang VARCHAR(50) NOT NULL DEFAULT 'PDF',
    FOREIGN KEY (ma_nv) REFERENCES NHAN_VIEN(ma_nv) ON DELETE CASCADE
);

-- Bảng AI_CANH_BAO
CREATE TABLE IF NOT EXISTS AI_CANH_BAO (
    ma_cb INT AUTO_INCREMENT PRIMARY KEY,
    ma_thuoc INT NOT NULL,
    loai_canh_bao VARCHAR(50) NOT NULL,
    noi_dung NVARCHAR(255) NOT NULL,
    thoi_gian DATETIME NOT NULL,
    xu_ly VARCHAR(20) NOT NULL DEFAULT 'Chưa xử lý',
    FOREIGN KEY (ma_thuoc) REFERENCES THUOC(ma_thuoc) ON DELETE CASCADE
);

-- Thêm dữ liệu mẫu
INSERT INTO THUOC (ten_thuoc, don_vi, gia_nhap, gia_ban, ngay_them, sl_toi_thieu, anh) VALUES
('Paracetamol 500mg', 'Viên', 2000, 3000, '2024-01-01', 50, 'paracetamol.png'),
('Amoxicillin 500mg', 'Viên', 1500, 2500, '2024-01-01', 30, 'amoxicillin.jpg'),
('Efferalgan 500mg', 'Viên', 3000, 4500, '2024-01-01', 40, 'efferalgan.jpg'),
('Vitamin C 1000mg', 'Viên', 1000, 1500, '2024-01-01', 60, 'vitamin-c.jpjpg'),
('Omeprazole 20mg', 'Viên', 5000, 7500, '2024-01-01', 25, 'omeprazole.jpg'),
('Silkron Cream', 'Tuýp', 12000, 18000, '2024-06-01', 20, 'silkron.jpg'),
('Betadine 10%', 'Chai', 25000, 35000, '2024-06-01', 15, 'betadine.jpg'),
('Oresol', 'Gói', 2000, 3500, '2024-06-01', 30, 'oresol.jpg'),
('Smecta', 'Gói', 4000, 6000, '2024-06-01', 25, 'smecta.jpg'),
('Cotrimoxazole', 'Viên', 800, 1500, '2024-06-01', 40, 'cotrimoxazole.jpg'),
('Alpha Choay', 'Viên', 3500, 5000, '2024-06-01', 20, 'alpha_choay.jpg'),
('Hydrite', 'Gói', 2500, 4000, '2024-06-01', 30, 'hydrite.jpg'),
('Salonpas', 'Miếng', 5000, 8000, '2024-06-01', 50, 'salonpas.jpg'),
('Vitamin B1', 'Viên', 900, 1400, '2024-06-01', 60, 'vitamin_b1.jpg'),
('Omeprazole 40mg', 'Viên', 7000, 9500, '2024-06-01', 20, 'omeprazole_40mg.png');

INSERT INTO NHAN_VIEN (ten_nv, chuc_vu, sdt, dia_chi, trang_thai) VALUES
('Nguyễn Văn A', 'Quản lý', '0123456789', 'Hà Nội', 'Đang làm việc'),
('Trần Thị B', 'Bán hàng', '0987654321', 'TP.HCM', 'Đang làm việc'),
('Lê Văn C', 'Bán hàng', '0369852147', 'Đà Nẵng', 'Đang làm việc');

INSERT INTO KHACH_HANG (ten_kh, ngay_sinh, sdt, dia_chi, di_ung) VALUES
('Phạm Thị D', '1990-05-15', '0123456780', 'Hà Nội', 'Không có'),
('Hoàng Văn E', '1985-08-20', '0987654320', 'TP.HCM', 'Penicillin'),
('Vũ Thị F', '1995-12-10', '0369852140', 'Đà Nẵng', 'Không có');

INSERT INTO LO_THUOC (ma_thuoc, so_luong, ngay_nhap, han_su_dung, trang_thai) VALUES
(1, 1000, '2024-01-01', '2025-12-31', 'Còn hạn'),
(2, 500, '2024-01-01', '2025-06-30', 'Còn hạn'),
(3, 800, '2024-01-01', '2025-09-30', 'Còn hạn'),
(4, 1200, '2024-01-01', '2026-03-31', 'Còn hạn'),
(5, 300, '2024-01-01', '2025-03-31', 'Còn hạn'),
(6, 200, '2024-06-01', '2025-12-31', 'Còn hạn'),
(7, 100, '2024-06-01', '2025-10-31', 'Còn hạn'),
(8, 300, '2024-06-01', '2025-09-30', 'Còn hạn'),
(9, 250, '2024-06-01', '2025-08-31', 'Còn hạn'),
(10, 400, '2024-06-01', '2026-01-31', 'Còn hạn'),
(11, 150, '2024-06-01', '2025-11-30', 'Còn hạn'),
(12, 180, '2024-06-01', '2025-07-31', 'Còn hạn'),
(13, 350, '2024-06-01', '2026-03-31', 'Còn hạn'),
(14, 500, '2024-06-01', '2025-12-31', 'Còn hạn'),
(15, 120, '2024-06-01', '2025-06-30', 'Còn hạn');

-- Indexes
CREATE INDEX IF NOT EXISTS idx_thuoc_ten ON THUOC(ten_thuoc);
CREATE INDEX IF NOT EXISTS idx_nhanvien_ten ON NHAN_VIEN(ten_nv);
CREATE INDEX IF NOT EXISTS idx_khachhang_ten ON KHACH_HANG(ten_kh);
CREATE INDEX IF NOT EXISTS idx_khachhang_sdt ON KHACH_HANG(sdt);
CREATE INDEX IF NOT EXISTS idx_donthuoc_ngay ON DON_THUOC(ngay_tao);
CREATE INDEX IF NOT EXISTS idx_lothuoc_hansudung ON LO_THUOC(han_su_dung);
CREATE INDEX IF NOT EXISTS idx_canhbao_thoigian ON AI_CANH_BAO(thoi_gian); 
