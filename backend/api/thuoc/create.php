<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ... phần code còn lại 
include_once '../../config/database.php';
include_once '../../models/Thuoc.php';

$database = new Database();
$db = $database->getConnection();

$thuoc = new Thuoc($db);

$data = $_POST;

// Xử lý ảnh upload
$anhFileName = 'placeholder.png';
if (isset($_FILES['anh']) && $_FILES['anh']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = '../../../public/img/';
    $ext = pathinfo($_FILES['anh']['name'], PATHINFO_EXTENSION);
    $anhFileName = uniqid('med_') . '.' . $ext;
    move_uploaded_file($_FILES['anh']['tmp_name'], $uploadDir . $anhFileName);
}

if(!empty($data['ten_thuoc']) && !empty($data['don_vi']) && !empty($data['gia_nhap']) && !empty($data['gia_ban'])) {
    $thuoc->ten_thuoc = $data['ten_thuoc'];
    $thuoc->don_vi = $data['don_vi'];
    $thuoc->gia_nhap = $data['gia_nhap'];
    $thuoc->gia_ban = $data['gia_ban'];
    $thuoc->ngay_them = date('Y-m-d');
    $thuoc->sl_toi_thieu = $data['sl_toi_thieu'] ?? 10;
    $thuoc->anh = $anhFileName;

    if($thuoc->create()) {
        $ma_thuoc = $db->lastInsertId();
        // Thêm lô thuốc nếu có dữ liệu
        $so_luong = $data['so_luong'] ?? null;
        $ngay_nhap = $data['ngay_nhap'] ?? null;
        $han_su_dung = $data['han_su_dung'] ?? null;
        $ma_lo = $data['ma_lo'] ?? null;
        $trang_thai = $data['trang_thai'] ?? 'Còn hạn';
        if ($so_luong || $ngay_nhap || $han_su_dung || $ma_lo) {
            $query = "INSERT INTO LO_THUOC (ma_thuoc, so_luong, ngay_nhap, han_su_dung, trang_thai) VALUES (:ma_thuoc, :so_luong, :ngay_nhap, :han_su_dung, :trang_thai)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':ma_thuoc', $ma_thuoc);
            $stmt->bindParam(':so_luong', $so_luong);
            $stmt->bindParam(':ngay_nhap', $ngay_nhap);
            $stmt->bindParam(':han_su_dung', $han_su_dung);
            $stmt->bindParam(':trang_thai', $trang_thai);
            $stmt->execute();
        }
        http_response_code(201);
        echo json_encode(array("message" => "Thuốc đã được tạo thành công."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Không thể tạo thuốc."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Không thể tạo thuốc. Dữ liệu không đầy đủ."));
}
?> 