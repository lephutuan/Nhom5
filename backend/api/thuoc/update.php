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

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->ma_thuoc) && !empty($data->ten_thuoc) && !empty($data->don_vi) && !empty($data->gia_nhap) && !empty($data->gia_ban)) {
    $thuoc->ma_thuoc = $data->ma_thuoc;
    $thuoc->ten_thuoc = $data->ten_thuoc;
    $thuoc->don_vi = $data->don_vi;
    $thuoc->gia_nhap = $data->gia_nhap;
    $thuoc->gia_ban = $data->gia_ban;
    $thuoc->ngay_them = $data->ngay_them ?? date('Y-m-d');
    $thuoc->sl_toi_thieu = $data->sl_toi_thieu ?? 10;

    if($thuoc->update()) {
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