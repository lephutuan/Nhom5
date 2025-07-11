<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
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

// Ưu tiên nhận từ FormData (POST), nếu không thì nhận từ JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $thuoc->ma_thuoc = $data['ma_thuoc'] ?? null;
    $thuoc->ten_thuoc = $data['ten_thuoc'] ?? null;
    $thuoc->don_vi = $data['don_vi'] ?? null;
    $thuoc->gia_nhap = $data['gia_nhap'] ?? null;
    $thuoc->gia_ban = $data['gia_ban'] ?? null;
    $thuoc->ngay_them = $data['ngay_them'] ?? date('Y-m-d');
    $thuoc->sl_toi_thieu = $data['sl_toi_thieu'] ?? 10;
    // Xử lý ảnh nếu có
    if (isset($_FILES['anh']) && $_FILES['anh']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../../../public/img/';
        $ext = pathinfo($_FILES['anh']['name'], PATHINFO_EXTENSION);
        $anhFileName = uniqid('med_') . '.' . $ext;
        move_uploaded_file($_FILES['anh']['tmp_name'], $uploadDir . $anhFileName);
        $thuoc->anh = $anhFileName;
    } else if (isset($data['anh'])) {
        $thuoc->anh = $data['anh'];
    } else {
        // Giữ nguyên ảnh cũ nếu không upload mới
        // Cần truy vấn lấy ảnh cũ
        $query = "SELECT anh FROM THUOC WHERE ma_thuoc = ? LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $thuoc->ma_thuoc);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $thuoc->anh = $row ? $row['anh'] : 'placeholder.png';
    }
} else {
    $data = json_decode(file_get_contents("php://input"));
    $thuoc->ma_thuoc = $data->ma_thuoc ?? null;
    $thuoc->ten_thuoc = $data->ten_thuoc ?? null;
    $thuoc->don_vi = $data->don_vi ?? null;
    $thuoc->gia_nhap = $data->gia_nhap ?? null;
    $thuoc->gia_ban = $data->gia_ban ?? null;
    $thuoc->ngay_them = $data->ngay_them ?? date('Y-m-d');
    $thuoc->sl_toi_thieu = $data->sl_toi_thieu ?? 10;
    $thuoc->anh = $data->anh ?? null;
}

if(!empty($thuoc->ma_thuoc) && !empty($thuoc->ten_thuoc) && !empty($thuoc->don_vi) && !empty($thuoc->gia_nhap) && !empty($thuoc->gia_ban)) {
    $success = $thuoc->update();
    // Nếu có thông tin lô thuốc thì cập nhật bảng LO_THUOC
    $han_su_dung = $data['han_su_dung'] ?? null;
    $ma_lo = $data['ma_lo'] ?? null;
    $ngay_nhap = $data['ngay_nhap'] ?? null;
    $so_luong = $data['so_luong'] ?? null;
    if ($han_su_dung || $ma_lo || $ngay_nhap || $so_luong) {
        // Cập nhật lô mới nhất của thuốc này
        $updateFields = [];
        if ($han_su_dung !== null) $updateFields[] = 'han_su_dung=:han_su_dung';
        if ($ngay_nhap !== null) $updateFields[] = 'ngay_nhap=:ngay_nhap';
        if ($so_luong !== null) $updateFields[] = 'so_luong=:so_luong';
        if ($ma_lo !== null) $updateFields[] = 'ma_lo=:ma_lo';
        if (count($updateFields) > 0) {
            $query = "UPDATE LO_THUOC SET ".implode(',', $updateFields)." WHERE ma_thuoc=:ma_thuoc ORDER BY ma_lo DESC LIMIT 1";
            $stmt = $db->prepare($query);
            if ($han_su_dung !== null) $stmt->bindParam(':han_su_dung', $han_su_dung);
            if ($ngay_nhap !== null) $stmt->bindParam(':ngay_nhap', $ngay_nhap);
            if ($so_luong !== null) $stmt->bindParam(':so_luong', $so_luong);
            if ($ma_lo !== null) $stmt->bindParam(':ma_lo', $ma_lo);
            $stmt->bindParam(':ma_thuoc', $thuoc->ma_thuoc);
            $stmt->execute();
        }
    }
    if($success) {
        http_response_code(200);
        echo json_encode(array("message" => "Thuốc đã được cập nhật thành công."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Không thể cập nhật thuốc."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Không thể cập nhật thuốc. Dữ liệu không đầy đủ."));
}
?> 