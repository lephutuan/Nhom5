<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ... phần code còn lại ...
class NhanVien {
    private $conn;
    private $table_name = "NHAN_VIEN";

    public $ma_nv;
    public $ten_nv;
    public $chuc_vu;
    public $sdt;
    public $dia_chi;
    public $trang_thai;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả nhân viên
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY ma_nv DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy một nhân viên theo mã
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ma_nv = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_nv);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->ten_nv = $row['ten_nv'];
            $this->chuc_vu = $row['chuc_vu'];
            $this->sdt = $row['sdt'];
            $this->dia_chi = $row['dia_chi'];
            $this->trang_thai = $row['trang_thai'];
            return true;
        }
        return false;
    }

    // Tạo nhân viên mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET ten_nv=:ten_nv, chuc_vu=:chuc_vu, sdt=:sdt, 
                    dia_chi=:dia_chi, trang_thai=:trang_thai";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->ten_nv = htmlspecialchars(strip_tags($this->ten_nv));
        $this->chuc_vu = htmlspecialchars(strip_tags($this->chuc_vu));
        $this->sdt = htmlspecialchars(strip_tags($this->sdt));
        $this->dia_chi = htmlspecialchars(strip_tags($this->dia_chi));
        $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
        
        // Bind các giá trị
        $stmt->bindParam(":ten_nv", $this->ten_nv);
        $stmt->bindParam(":chuc_vu", $this->chuc_vu);
        $stmt->bindParam(":sdt", $this->sdt);
        $stmt->bindParam(":dia_chi", $this->dia_chi);
        $stmt->bindParam(":trang_thai", $this->trang_thai);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật nhân viên
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET ten_nv=:ten_nv, chuc_vu=:chuc_vu, sdt=:sdt, 
                    dia_chi=:dia_chi, trang_thai=:trang_thai 
                WHERE ma_nv=:ma_nv";
        
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $this->ten_nv = htmlspecialchars(strip_tags($this->ten_nv));
        $this->chuc_vu = htmlspecialchars(strip_tags($this->chuc_vu));
        $this->sdt = htmlspecialchars(strip_tags($this->sdt));
        $this->dia_chi = htmlspecialchars(strip_tags($this->dia_chi));
        $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
        $this->ma_nv = htmlspecialchars(strip_tags($this->ma_nv));
        
        // Bind các giá trị
        $stmt->bindParam(":ten_nv", $this->ten_nv);
        $stmt->bindParam(":chuc_vu", $this->chuc_vu);
        $stmt->bindParam(":sdt", $this->sdt);
        $stmt->bindParam(":dia_chi", $this->dia_chi);
        $stmt->bindParam(":trang_thai", $this->trang_thai);
        $stmt->bindParam(":ma_nv", $this->ma_nv);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa nhân viên
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE ma_nv = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->ma_nv);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tìm kiếm nhân viên
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE ten_nv LIKE ? OR chuc_vu LIKE ? OR sdt LIKE ? 
                ORDER BY ma_nv DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        
        $stmt->execute();
        return $stmt;
    }

    // Lấy nhân viên theo chức vụ
    public function getByChucVu($chuc_vu) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE chuc_vu = ? AND trang_thai = 'Đang làm việc'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $chuc_vu);
        $stmt->execute();
        return $stmt;
    }
}
?> 