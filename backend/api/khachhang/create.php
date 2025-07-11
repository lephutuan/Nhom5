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
// ... phần code còn lại ...
include_once '../../config/database.php';
include_once '../../models/KhachHang.php';

$database = new Database();
$db = $database->getConnection();

$khachhang = new KhachHang($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->ten_kh) && !empty($data->sdt)) {
    $khachhang->ten_kh = $data->ten_kh;
    $khachhang->sdt = $data->sdt;
    $khachhang->ngay_sinh = $data->ngay_sinh ?? null;
    $khachhang->dia_chi = $data->dia_chi ?? null;
    $khachhang->di_ung = $data->di_ung ?? null;

    if($khachhang->create()) {
        // Lấy mã khách hàng vừa tạo
        $ma_kh = $db->lastInsertId();
        http_response_code(201);
        echo json_encode(array("message" => "Khách hàng đã được tạo thành công.", "ma_kh" => $ma_kh));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Không thể tạo khách hàng."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Không thể tạo khách hàng. Vui lòng nhập tên và số điện thoại."));
}
?> 