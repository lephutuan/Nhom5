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

include_once '../../config/database.php';
include_once '../../models/ChiTietDonThuoc.php';

$database = new Database();
$db = $database->getConnection();

$chiTietDonThuoc = new ChiTietDonThuoc($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->ma_don) && !empty($data->chi_tiet_list) && is_array($data->chi_tiet_list)) {
    
    // Chuẩn bị dữ liệu cho việc tạo nhiều chi tiết
    $chiTietList = [];
    foreach ($data->chi_tiet_list as $item) {
        if (!empty($item->ma_thuoc) && !empty($item->lieu_luong) && !empty($item->so_ngay) && !empty($item->so_luong)) {
            $chiTietList[] = [
                'ma_don' => $data->ma_don,
                'ma_thuoc' => $item->ma_thuoc,
                'lieu_luong' => $item->lieu_luong,
                'so_ngay' => $item->so_ngay,
                'so_luong' => $item->so_luong
            ];
        }
    }
    
    if (!empty($chiTietList)) {
        $result = $chiTietDonThuoc->createMultiple($chiTietList);
        
        if($result) {
            http_response_code(201);
            echo json_encode(array(
                "message" => "Chi tiết đơn thuốc đã được tạo thành công.",
                "so_luong_chi_tiet" => count($chiTietList)
            ));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Không thể tạo chi tiết đơn thuốc."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Dữ liệu chi tiết đơn thuốc không hợp lệ."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Không thể tạo chi tiết đơn thuốc. Dữ liệu không đầy đủ."));
}
?> 