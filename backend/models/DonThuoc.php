<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ... phần code còn lại ...
class DonThuoc {
    private $conn;
    private $table_name = "DON_THUOC";

    public $ma_don;
    public $ma_kh;
    public $ma_nv;
    public $ngay_tao;
    public $da_thanh_toan;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đơn thuốc
    public function read() {
        $query = "SELECT dt.*, kh.ten_kh, nv.ten_nv 
                FROM " . $this->table_name . " dt
                LEFT JOIN KHACH_HANG kh ON dt.ma_kh = kh.ma_kh
                LEFT JOIN NHAN_VIEN nv ON dt.ma_nv = nv.ma_nv
                ORDER BY dt.ma_don DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy một đơn thuốc theo mã
    public function readOne() {
        $query = "SELECT dt.*, kh.ten_kh, nv.ten_nv 
                FROM " . $this->table_name . " dt
                LEFT JOIN KHACH_HANG kh ON dt.ma_kh = kh.ma_kh
                LEFT JOIN NHAN_VIEN nv ON dt.ma_nv = nv.ma_nv
                WHERE dt.ma_don = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_don);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->ma_kh = $row['ma_kh'];
            $this->ma_nv = $row['ma_nv'];
            $this->ngay_tao = $row['ngay_tao'];
            $this->da_thanh_toan = $row['da_thanh_toan'];
            return $row;
        }
        return false;
    }

    // Tạo đơn thuốc mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET ma_kh=:ma_kh, ma_nv=:ma_nv, ngay_tao=:ngay_tao, da_thanh_toan=:da_thanh_toan";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->ma_kh = htmlspecialchars(strip_tags($this->ma_kh));
        $this->ma_nv = htmlspecialchars(strip_tags($this->ma_nv));
        $this->ngay_tao = htmlspecialchars(strip_tags($this->ngay_tao));
        $this->da_thanh_toan = htmlspecialchars(strip_tags($this->da_thanh_toan));
        
        // Bind các giá trị
        $stmt->bindParam(":ma_kh", $this->ma_kh);
        $stmt->bindParam(":ma_nv", $this->ma_nv);
        $stmt->bindParam(":ngay_tao", $this->ngay_tao);
        $stmt->bindParam(":da_thanh_toan", $this->da_thanh_toan);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật đơn thuốc
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET ma_kh=:ma_kh, ma_nv=:ma_nv, ngay_tao=:ngay_tao, da_thanh_toan=:da_thanh_toan 
                WHERE ma_don=:ma_don";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->ma_kh = htmlspecialchars(strip_tags($this->ma_kh));
        $this->ma_nv = htmlspecialchars(strip_tags($this->ma_nv));
        $this->ngay_tao = htmlspecialchars(strip_tags($this->ngay_tao));
        $this->da_thanh_toan = htmlspecialchars(strip_tags($this->da_thanh_toan));
        $this->ma_don = htmlspecialchars(strip_tags($this->ma_don));
        
        // Bind các giá trị
        $stmt->bindParam(":ma_kh", $this->ma_kh);
        $stmt->bindParam(":ma_nv", $this->ma_nv);
        $stmt->bindParam(":ngay_tao", $this->ngay_tao);
        $stmt->bindParam(":da_thanh_toan", $this->da_thanh_toan);
        $stmt->bindParam(":ma_don", $this->ma_don);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa đơn thuốc
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE ma_don = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_don);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy đơn thuốc theo khách hàng
    public function getByCustomer($ma_kh) {
        $query = "SELECT dt.*, kh.ten_kh, nv.ten_nv 
                FROM " . $this->table_name . " dt
                LEFT JOIN KHACH_HANG kh ON dt.ma_kh = kh.ma_kh
                LEFT JOIN NHAN_VIEN nv ON dt.ma_nv = nv.ma_nv
                WHERE dt.ma_kh = ?
                ORDER BY dt.ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $ma_kh);
        $stmt->execute();
        return $stmt;
    }

    // Lấy đơn thuốc theo nhân viên
    public function getByEmployee($ma_nv) {
        $query = "SELECT dt.*, kh.ten_kh, nv.ten_nv 
                FROM " . $this->table_name . " dt
                LEFT JOIN KHACH_HANG kh ON dt.ma_kh = kh.ma_kh
                LEFT JOIN NHAN_VIEN nv ON dt.ma_nv = nv.ma_nv
                WHERE dt.ma_nv = ?
                ORDER BY dt.ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $ma_nv);
        $stmt->execute();
        return $stmt;
    }

    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus($ma_don, $status) {
        $query = "UPDATE " . $this->table_name . " SET da_thanh_toan = ? WHERE ma_don = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $ma_don);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 