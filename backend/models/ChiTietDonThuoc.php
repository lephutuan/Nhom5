<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

class ChiTietDonThuoc {
    private $conn;
    private $table_name = "CHI_TIET_DON_THUOC";

    public $ma_don;
    public $ma_thuoc;
    public $lieu_luong;
    public $so_ngay;
    public $so_luong;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy chi tiết đơn thuốc theo mã đơn
    public function getByDonThuoc($ma_don) {
        $query = "SELECT ctdt.*, t.ten_thuoc, t.gia_ban, t.don_vi 
                FROM " . $this->table_name . " ctdt
                LEFT JOIN THUOC t ON ctdt.ma_thuoc = t.ma_thuoc
                WHERE ctdt.ma_don = ?
                ORDER BY ctdt.ma_thuoc";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $ma_don);
        $stmt->execute();
        return $stmt;
    }

    // Tạo chi tiết đơn thuốc mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET ma_don=:ma_don, ma_thuoc=:ma_thuoc, lieu_luong=:lieu_luong, 
                    so_ngay=:so_ngay, so_luong=:so_luong";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->ma_don = htmlspecialchars(strip_tags($this->ma_don));
        $this->ma_thuoc = htmlspecialchars(strip_tags($this->ma_thuoc));
        $this->lieu_luong = htmlspecialchars(strip_tags($this->lieu_luong));
        $this->so_ngay = htmlspecialchars(strip_tags($this->so_ngay));
        $this->so_luong = htmlspecialchars(strip_tags($this->so_luong));
        
        // Bind các giá trị
        $stmt->bindParam(":ma_don", $this->ma_don);
        $stmt->bindParam(":ma_thuoc", $this->ma_thuoc);
        $stmt->bindParam(":lieu_luong", $this->lieu_luong);
        $stmt->bindParam(":so_ngay", $this->so_ngay);
        $stmt->bindParam(":so_luong", $this->so_luong);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tạo nhiều chi tiết đơn thuốc cùng lúc
    public function createMultiple($chiTietList) {
        $this->conn->beginTransaction();
        
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    SET ma_don=:ma_don, ma_thuoc=:ma_thuoc, lieu_luong=:lieu_luong, 
                        so_ngay=:so_ngay, so_luong=:so_luong";
            
            $stmt = $this->conn->prepare($query);
            
            foreach ($chiTietList as $chiTiet) {
                $stmt->bindParam(":ma_don", $chiTiet['ma_don']);
                $stmt->bindParam(":ma_thuoc", $chiTiet['ma_thuoc']);
                $stmt->bindParam(":lieu_luong", $chiTiet['lieu_luong']);
                $stmt->bindParam(":so_ngay", $chiTiet['so_ngay']);
                $stmt->bindParam(":so_luong", $chiTiet['so_luong']);
                
                if (!$stmt->execute()) {
                    throw new Exception("Lỗi khi tạo chi tiết đơn thuốc");
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Xóa chi tiết đơn thuốc theo mã đơn
    public function deleteByDonThuoc($ma_don) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ma_don = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $ma_don);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật chi tiết đơn thuốc
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET lieu_luong=:lieu_luong, so_ngay=:so_ngay, so_luong=:so_luong 
                WHERE ma_don=:ma_don AND ma_thuoc=:ma_thuoc";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->lieu_luong = htmlspecialchars(strip_tags($this->lieu_luong));
        $this->so_ngay = htmlspecialchars(strip_tags($this->so_ngay));
        $this->so_luong = htmlspecialchars(strip_tags($this->so_luong));
        $this->ma_don = htmlspecialchars(strip_tags($this->ma_don));
        $this->ma_thuoc = htmlspecialchars(strip_tags($this->ma_thuoc));
        
        // Bind các giá trị
        $stmt->bindParam(":lieu_luong", $this->lieu_luong);
        $stmt->bindParam(":so_ngay", $this->so_ngay);
        $stmt->bindParam(":so_luong", $this->so_luong);
        $stmt->bindParam(":ma_don", $this->ma_don);
        $stmt->bindParam(":ma_thuoc", $this->ma_thuoc);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 