# Hướng dẫn khởi tạo và chạy dự án Quản Lý Tiệm Thuốc

## 0. Cài đặt môi trường cần thiết

### 1. Cài đặt XAMPP (hoặc tương đương)
- Tải XAMPP tại: https://www.apachefriends.org/
- Cài đặt bình thường, chọn các module: Apache, MySQL.
- Sau khi cài đặt, mở XAMPP Control Panel, **Start** cả Apache và MySQL.

### 2. Cài đặt PHP, MySQL (nếu không dùng XAMPP)
- PHP >= 7.4, MySQL >= 5.7
- Có thể dùng Laragon, MAMP, WAMP hoặc cài riêng từng thành phần.

### 3. Cấu hình database trên phpMyAdmin
- Truy cập: http://localhost/phpmyadmin
- Đăng nhập (thường user: `root`, không mật khẩu mặc định XAMPP)
- Chọn tab **Import** > Chọn file `backend/database/schema.sql` > Nhấn **Go** để import.
- Sau khi import, kiểm tra đã có database `nhom5_demo` và các bảng dữ liệu mẫu.

---

## 1. Khởi tạo Database

**Bước 1:** Mở MySQL (qua phpMyAdmin hoặc dòng lệnh).

**Bước 2:** Import file SQL duy nhất:

```sql
backend/database/schema.sql
```

- File này sẽ tự động tạo database `nhom5_demo`, các bảng, dữ liệu mẫu, 15 loại thuốc (cả lô thuốc), nhân viên, khách hàng...
- Đảm bảo không cần chạy bất kỳ file SQL nào khác.

**Bước 3:** Kiểm tra lại database đã có đủ dữ liệu mẫu:
- Bảng `THUOC` có 15 loại thuốc, đã có ảnh mẫu.
- Bảng `LO_THUOC` có lô thuốc tương ứng.

## 2. Ảnh sản phẩm
- Đảm bảo thư mục `public/img/` chứa đầy đủ các file ảnh mẫu (paracetamol.png, amoxicillin.jpg, silkron.jpg, ...).
- Nếu thiếu ảnh, hệ thống sẽ tự động dùng ảnh placeholder.

## 3. Cấu trúc thư mục
```
Nhom5_demo/
  backend/
    api/...
    config/...
    database/
      schema.sql   # <-- chỉ giữ file này
    models/...
    public/js/api.js
    ...
  public/
    img/...
    css/...
    js/...
  ...
```

## 4. Chạy dự án
- Đảm bảo đã import database như hướng dẫn trên.
- Chạy XAMPP hoặc môi trường PHP/MySQL tương tự.
- Truy cập các file HTML (home.html, warehouse.html, products.html, ...).

## 5. Lưu ý
- Không cần chạy bất kỳ file SQL nào khác ngoài `schema.sql`.
- Nếu muốn reset dữ liệu, chỉ cần xóa database và import lại file này.
- Nếu có lỗi về ảnh, kiểm tra lại thư mục `public/img/`.

---
**Mọi thắc mắc hoặc lỗi phát sinh, liên hệ nhóm phát triển để được hỗ trợ!** 