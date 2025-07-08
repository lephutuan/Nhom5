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

if(!empty($data->ten_kh) && !empty($data->ngay_sinh) && !empty($data->sdt) && !empty($data->dia_chi)) {
    $khachhang->ten_kh = $data->ten_kh;
    $khachhang->ngay_sinh = $data->ngay_sinh;
    $khachhang->sdt = $data->sdt;
    $khachhang->dia_chi = $data->dia_chi;
    $khachhang->di_ung = $data->di_ung ?? 'Không có';

    if($khachhang->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Khách hàng đã được tạo thành công."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Không thể tạo khách hàng."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Không thể tạo khách hàng. Dữ liệu không đầy đủ."));
}
?> 