<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ... phần code còn lại ...
class KhachHang {
    private $conn;
    private $table_name = "KHACH_HANG";

    public $ma_kh;
    public $ten_kh;
    public $ngay_sinh;
    public $sdt;
    public $dia_chi;
    public $di_ung;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả khách hàng
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY ma_kh DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy một khách hàng theo mã
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ma_kh = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_kh);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->ten_kh = $row['ten_kh'];
            $this->ngay_sinh = $row['ngay_sinh'];
            $this->sdt = $row['sdt'];
            $this->dia_chi = $row['dia_chi'];
            $this->di_ung = $row['di_ung'];
            return true;
        }
        return false;
    }

    // Tạo khách hàng mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET ten_kh=:ten_kh, ngay_sinh=:ngay_sinh, sdt=:sdt, 
                    dia_chi=:dia_chi, di_ung=:di_ung";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->ten_kh = htmlspecialchars(strip_tags($this->ten_kh));
        $this->ngay_sinh = htmlspecialchars(strip_tags($this->ngay_sinh));
        $this->sdt = htmlspecialchars(strip_tags($this->sdt));
        $this->dia_chi = htmlspecialchars(strip_tags($this->dia_chi));
        $this->di_ung = htmlspecialchars(strip_tags($this->di_ung));
        
        // Bind các giá trị
        $stmt->bindParam(":ten_kh", $this->ten_kh);
        $stmt->bindParam(":ngay_sinh", $this->ngay_sinh);
        $stmt->bindParam(":sdt", $this->sdt);
        $stmt->bindParam(":dia_chi", $this->dia_chi);
        $stmt->bindParam(":di_ung", $this->di_ung);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật khách hàng
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET ten_kh=:ten_kh, ngay_sinh=:ngay_sinh, sdt=:sdt, 
                    dia_chi=:dia_chi, di_ung=:di_ung 
                WHERE ma_kh=:ma_kh";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->ten_kh = htmlspecialchars(strip_tags($this->ten_kh));
        $this->ngay_sinh = htmlspecialchars(strip_tags($this->ngay_sinh));
        $this->sdt = htmlspecialchars(strip_tags($this->sdt));
        $this->dia_chi = htmlspecialchars(strip_tags($this->dia_chi));
        $this->di_ung = htmlspecialchars(strip_tags($this->di_ung));
        $this->ma_kh = htmlspecialchars(strip_tags($this->ma_kh));
        
        // Bind các giá trị
        $stmt->bindParam(":ten_kh", $this->ten_kh);
        $stmt->bindParam(":ngay_sinh", $this->ngay_sinh);
        $stmt->bindParam(":sdt", $this->sdt);
        $stmt->bindParam(":dia_chi", $this->dia_chi);
        $stmt->bindParam(":di_ung", $this->di_ung);
        $stmt->bindParam(":ma_kh", $this->ma_kh);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa khách hàng
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE ma_kh = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_kh);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tìm kiếm khách hàng
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE ten_kh LIKE ? OR sdt LIKE ? OR dia_chi LIKE ? 
                ORDER BY ma_kh DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        
        $stmt->execute();
        return $stmt;
    }

    // Kiểm tra khách hàng theo số điện thoại
    public function checkByPhone($sdt) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE sdt = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $sdt);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?> 