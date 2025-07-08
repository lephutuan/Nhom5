<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ... phần code còn lại ...
include_once '../../config/database.php';
include_once '../../models/DonThuoc.php';

$database = new Database();
$db = $database->getConnection();

$donthuoc = new DonThuoc($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->ma_kh) && !empty($data->ma_nv)) {
    $donthuoc->ma_kh = $data->ma_kh;
    $donthuoc->ma_nv = $data->ma_nv;
    $donthuoc->ngay_tao = $data->ngay_tao ?? date('Y-m-d H:i:s');
    $donthuoc->da_thanh_toan = $data->da_thanh_toan ?? 'Chưa thanh toán';

    $ma_don = $donthuoc->create();
    
    if($ma_don) {
        http_response_code(201);
        echo json_encode(array(
            "message" => "Đơn thuốc đã được tạo thành công.",
            "ma_don" => $ma_don
        ));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Không thể tạo đơn thuốc."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Không thể tạo đơn thuốc. Dữ liệu không đầy đủ."));
}
?> 