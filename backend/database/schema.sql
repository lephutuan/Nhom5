-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS nhom5_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nhom5_demo;

-- Bảng THUOC
CREATE TABLE THUOC (
    ma_thuoc INT AUTO_INCREMENT PRIMARY KEY,
    ten_thuoc NVARCHAR(100) NOT NULL,
    don_vi VARCHAR(10) NOT NULL,
    gia_nhap FLOAT NOT NULL,
    gia_ban FLOAT NOT NULL,
    ngay_them DATE NOT NULL,
    sl_toi_thieu INT NOT NULL DEFAULT 10
);

-- Bảng LO_THUOC
CREATE TABLE LO_THUOC (
    ma_lo INT AUTO_INCREMENT PRIMARY KEY,
    ma_thuoc INT NOT NULL,
    so_luong INT NOT NULL,
    ngay_nhap DATE NOT NULL,
    han_su_dung DATE NOT NULL,
    trang_thai VARCHAR(20) NOT NULL DEFAULT 'Còn hạn',
    FOREIGN KEY (ma_thuoc) REFERENCES THUOC(ma_thuoc) ON DELETE CASCADE
);

-- Bảng NHAN_VIEN
CREATE TABLE NHAN_VIEN (
    ma_nv INT AUTO_INCREMENT PRIMARY KEY,
    ten_nv NVARCHAR(100) NOT NULL,
    chuc_vu VARCHAR(20) NOT NULL,
    sdt VARCHAR(10) NOT NULL,
    dia_chi VARCHAR(255) NOT NULL,
    trang_thai VARCHAR(20) NOT NULL DEFAULT 'Đang làm việc'
);

-- Bảng KHO_XUAT_NHAP
CREATE TABLE KHO_XUAT_NHAP (
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
CREATE TABLE KHACH_HANG (
    ma_kh INT AUTO_INCREMENT PRIMARY KEY,
    ten_kh NVARCHAR(100) NOT NULL,
    ngay_sinh DATE NOT NULL,
    sdt VARCHAR(10) NOT NULL,
    dia_chi NVARCHAR(255) NOT NULL,
    di_ung NVARCHAR(255) NOT NULL DEFAULT 'Không có'
);

-- Bảng DON_THUOC
CREATE TABLE DON_THUOC (
    ma_don INT AUTO_INCREMENT PRIMARY KEY,
    ma_kh INT NOT NULL,
    ma_nv INT NOT NULL,
    ngay_tao DATETIME NOT NULL,
    da_thanh_toan VARCHAR(20) NOT NULL DEFAULT 'Chưa thanh toán',
    FOREIGN KEY (ma_kh) REFERENCES KHACH_HANG(ma_kh) ON DELETE CASCADE,
    FOREIGN KEY (ma_nv) REFERENCES NHAN_VIEN(ma_nv) ON DELETE CASCADE
);

-- Bảng CHI_TIET_DON_THUOC
CREATE TABLE CHI_TIET_DON_THUOC (
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
CREATE TABLE HOA_DON (
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
CREATE TABLE BAO_CAO (
    ma_bc INT AUTO_INCREMENT PRIMARY KEY,
    ma_nv INT NOT NULL,
    loai VARCHAR(50) NOT NULL,
    thoi_gian DATETIME NOT NULL,
    dinh_dang VARCHAR(50) NOT NULL DEFAULT 'PDF',
    FOREIGN KEY (ma_nv) REFERENCES NHAN_VIEN(ma_nv) ON DELETE CASCADE
);

-- Bảng AI_CANH_BAO
CREATE TABLE AI_CANH_BAO (
    ma_cb INT AUTO_INCREMENT PRIMARY KEY,
    ma_thuoc INT NOT NULL,
    loai_canh_bao VARCHAR(50) NOT NULL,
    noi_dung NVARCHAR(255) NOT NULL,
    thoi_gian DATETIME NOT NULL,
    xu_ly VARCHAR(20) NOT NULL DEFAULT 'Chưa xử lý',
    FOREIGN KEY (ma_thuoc) REFERENCES THUOC(ma_thuoc) ON DELETE CASCADE
);

-- Thêm dữ liệu mẫu
INSERT INTO THUOC (ten_thuoc, don_vi, gia_nhap, gia_ban, ngay_them, sl_toi_thieu) VALUES
('Paracetamol 500mg', 'Viên', 2000, 3000, '2024-01-01', 50),
('Amoxicillin 500mg', 'Viên', 1500, 2500, '2024-01-01', 30),
('Efferalgan 500mg', 'Viên', 3000, 4500, '2024-01-01', 40),
('Vitamin C 1000mg', 'Viên', 1000, 1500, '2024-01-01', 60),
('Omeprazole 20mg', 'Viên', 5000, 7500, '2024-01-01', 25);

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
(5, 300, '2024-01-01', '2025-03-31', 'Còn hạn');

-- Tạo indexes để tối ưu hiệu suất
CREATE INDEX idx_thuoc_ten ON THUOC(ten_thuoc);
CREATE INDEX idx_nhanvien_ten ON NHAN_VIEN(ten_nv);
CREATE INDEX idx_khachhang_ten ON KHACH_HANG(ten_kh);
CREATE INDEX idx_khachhang_sdt ON KHACH_HANG(sdt);
CREATE INDEX idx_donthuoc_ngay ON DON_THUOC(ngay_tao);
CREATE INDEX idx_lothuoc_hansudung ON LO_THUOC(han_su_dung);
CREATE INDEX idx_canhbao_thoigian ON AI_CANH_BAO(thoi_gian); 