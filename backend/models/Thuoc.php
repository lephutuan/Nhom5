<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ... phần code còn lại ...
class Thuoc {
    private $conn;
    private $table_name = "THUOC";

    public $ma_thuoc;
    public $ten_thuoc;
    public $don_vi;
    public $gia_nhap;
    public $gia_ban;
    public $ngay_them;
    public $sl_toi_thieu;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả thuốc
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY ma_thuoc DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy một thuốc theo mã
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ma_thuoc = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_thuoc);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->ten_thuoc = $row['ten_thuoc'];
            $this->don_vi = $row['don_vi'];
            $this->gia_nhap = $row['gia_nhap'];
            $this->gia_ban = $row['gia_ban'];
            $this->ngay_them = $row['ngay_them'];
            $this->sl_toi_thieu = $row['sl_toi_thieu'];
            return true;
        }
        return false;
    }

    // Tạo thuốc mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET ten_thuoc=:ten_thuoc, don_vi=:don_vi, gia_nhap=:gia_nhap, 
                    gia_ban=:gia_ban, ngay_them=:ngay_them, sl_toi_thieu=:sl_toi_thieu";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->ten_thuoc = htmlspecialchars(strip_tags($this->ten_thuoc));
        $this->don_vi = htmlspecialchars(strip_tags($this->don_vi));
        $this->gia_nhap = htmlspecialchars(strip_tags($this->gia_nhap));
        $this->gia_ban = htmlspecialchars(strip_tags($this->gia_ban));
        $this->ngay_them = htmlspecialchars(strip_tags($this->ngay_them));
        $this->sl_toi_thieu = htmlspecialchars(strip_tags($this->sl_toi_thieu));
        
        // Bind các giá trị
        $stmt->bindParam(":ten_thuoc", $this->ten_thuoc);
        $stmt->bindParam(":don_vi", $this->don_vi);
        $stmt->bindParam(":gia_nhap", $this->gia_nhap);
        $stmt->bindParam(":gia_ban", $this->gia_ban);
        $stmt->bindParam(":ngay_them", $this->ngay_them);
        $stmt->bindParam(":sl_toi_thieu", $this->sl_toi_thieu);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thuốc
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET ten_thuoc=:ten_thuoc, don_vi=:don_vi, gia_nhap=:gia_nhap, 
                    gia_ban=:gia_ban, ngay_them=:ngay_them, sl_toi_thieu=:sl_toi_thieu 
                WHERE ma_thuoc=:ma_thuoc";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->ten_thuoc = htmlspecialchars(strip_tags($this->ten_thuoc));
        $this->don_vi = htmlspecialchars(strip_tags($this->don_vi));
        $this->gia_nhap = htmlspecialchars(strip_tags($this->gia_nhap));
        $this->gia_ban = htmlspecialchars(strip_tags($this->gia_ban));
        $this->ngay_them = htmlspecialchars(strip_tags($this->ngay_them));
        $this->sl_toi_thieu = htmlspecialchars(strip_tags($this->sl_toi_thieu));
        $this->ma_thuoc = htmlspecialchars(strip_tags($this->ma_thuoc));
        
        // Bind các giá trị
        $stmt->bindParam(":ten_thuoc", $this->ten_thuoc);
        $stmt->bindParam(":don_vi", $this->don_vi);
        $stmt->bindParam(":gia_nhap", $this->gia_nhap);
        $stmt->bindParam(":gia_ban", $this->gia_ban);
        $stmt->bindParam(":ngay_them", $this->ngay_them);
        $stmt->bindParam(":sl_toi_thieu", $this->sl_toi_thieu);
        $stmt->bindParam(":ma_thuoc", $this->ma_thuoc);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa thuốc
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE ma_thuoc = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_thuoc);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tìm kiếm thuốc
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE ten_thuoc LIKE ? OR don_vi LIKE ? 
                ORDER BY ma_thuoc DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        
        $stmt->execute();
        return $stmt;
    }
}
?> 