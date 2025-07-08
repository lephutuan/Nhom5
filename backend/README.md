# Backend API - Hệ thống Quản lý Nhà thuốc

## Mô tả
Backend API được xây dựng bằng PHP và MySQL cho hệ thống quản lý nhà thuốc Nhom5_demo.

## Cấu trúc thư mục
```
backend/
├── config/
│   └── database.php          # Cấu hình kết nối database
├── models/
│   ├── Thuoc.php            # Model cho bảng THUOC
│   ├── NhanVien.php         # Model cho bảng NHAN_VIEN
│   ├── KhachHang.php        # Model cho bảng KHACH_HANG
│   └── DonThuoc.php         # Model cho bảng DON_THUOC
├── api/
│   ├── thuoc/
│   │   ├── read.php         # API đọc danh sách thuốc
│   │   └── create.php       # API tạo thuốc mới
│   ├── nhanvien/
│   │   └── read.php         # API đọc danh sách nhân viên
│   ├── khachhang/
│   │   └── read.php         # API đọc danh sách khách hàng
│   └── donthuoc/
│       └── read.php         # API đọc danh sách đơn thuốc
├── utils/
│   └── helpers.php          # Các hàm tiện ích
├── database/
│   └── schema.sql           # Schema cơ sở dữ liệu
├── public/
│   └── js/
│       └── api.js           # JavaScript API client
└── README.md                # File hướng dẫn này
```

## Cài đặt

### 1. Yêu cầu hệ thống
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Web server (Apache/Nginx)

### 2. Cấu hình database
1. Tạo database MySQL:
```sql
CREATE DATABASE nhom5_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import schema:
```bash
mysql -u root -p nhom5_demo < backend/database/schema.sql
```

3. Cập nhật thông tin kết nối trong `config/database.php`:
```php
private $host = "localhost";
private $db_name = "nhom5_demo";
private $username = "root";
private $password = "your_password";
```

### 3. Cấu hình web server
Đảm bảo thư mục `backend` có thể truy cập qua web server.

## API Endpoints

### Thuốc (THUOC)
- `GET /api/thuoc/read.php` - Lấy danh sách thuốc
- `POST /api/thuoc/create.php` - Tạo thuốc mới
- `PUT /api/thuoc/update.php?id={id}` - Cập nhật thuốc
- `DELETE /api/thuoc/delete.php?id={id}` - Xóa thuốc

### Nhân viên (NHAN_VIEN)
- `GET /api/nhanvien/read.php` - Lấy danh sách nhân viên
- `POST /api/nhanvien/create.php` - Tạo nhân viên mới
- `PUT /api/nhanvien/update.php?id={id}` - Cập nhật nhân viên
- `DELETE /api/nhanvien/delete.php?id={id}` - Xóa nhân viên

### Khách hàng (KHACH_HANG)
- `GET /api/khachhang/read.php` - Lấy danh sách khách hàng
- `POST /api/khachhang/create.php` - Tạo khách hàng mới
- `PUT /api/khachhang/update.php?id={id}` - Cập nhật khách hàng
- `DELETE /api/khachhang/delete.php?id={id}` - Xóa khách hàng

### Đơn thuốc (DON_THUOC)
- `GET /api/donthuoc/read.php` - Lấy danh sách đơn thuốc
- `POST /api/donthuoc/create.php` - Tạo đơn thuốc mới
- `PUT /api/donthuoc/update.php?id={id}` - Cập nhật đơn thuốc
- `DELETE /api/donthuoc/delete.php?id={id}` - Xóa đơn thuốc

## Sử dụng JavaScript API Client

### 1. Include file API
```html
<script src="backend/public/js/api.js"></script>
```

### 2. Gọi API
```javascript
// Lấy danh sách thuốc
async function loadThuoc() {
    try {
        const data = await API.getThuoc();
        console.log(data.records);
    } catch (error) {
        console.error('Lỗi:', error);
    }
}

// Tạo thuốc mới
async function createThuoc() {
    const thuocData = {
        ten_thuoc: 'Paracetamol 500mg',
        don_vi: 'Viên',
        gia_nhap: 2000,
        gia_ban: 3000,
        sl_toi_thieu: 50
    };
    
    try {
        await API.createThuoc(thuocData);
        UI.showMessage('Thêm thuốc thành công', 'success');
    } catch (error) {
        UI.showMessage('Lỗi: ' + error.message, 'error');
    }
}
```

## Cấu trúc Database

### Bảng chính:
1. **THUOC** - Thông tin thuốc
2. **LO_THUOC** - Lô thuốc
3. **NHAN_VIEN** - Nhân viên
4. **KHACH_HANG** - Khách hàng
5. **DON_THUOC** - Đơn thuốc
6. **CHI_TIET_DON_THUOC** - Chi tiết đơn thuốc
7. **HOA_DON** - Hóa đơn
8. **KHO_XUAT_NHAP** - Xuất nhập kho
9. **BAO_CAO** - Báo cáo
10. **AI_CANH_BAO** - Cảnh báo AI

## Bảo mật

### 1. Input Validation
- Tất cả input được sanitize và validate
- Sử dụng prepared statements để tránh SQL injection

### 2. CORS
- API hỗ trợ CORS cho frontend
- Có thể cấu hình domain cụ thể

### 3. Error Handling
- Xử lý lỗi chi tiết
- Log hoạt động hệ thống

## Mở rộng

### Thêm API mới:
1. Tạo model trong thư mục `models/`
2. Tạo endpoint trong thư mục `api/`
3. Thêm method trong `api.js`

### Thêm validation:
1. Sử dụng các hàm trong `utils/helpers.php`
2. Thêm validation rules trong model

## Troubleshooting

### Lỗi kết nối database:
- Kiểm tra thông tin kết nối trong `config/database.php`
- Đảm bảo MySQL service đang chạy
- Kiểm tra quyền truy cập database

### Lỗi CORS:
- Kiểm tra cấu hình web server
- Đảm bảo header CORS được set đúng

### Lỗi 404:
- Kiểm tra đường dẫn API
- Đảm bảo file PHP tồn tại
- Kiểm tra cấu hình web server

## Liên hệ
Nếu có vấn đề hoặc cần hỗ trợ, vui lòng liên hệ team phát triển. 