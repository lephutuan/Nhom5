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

include_once '../../config/database.php';
include_once '../../models/DonThuoc.php';

$database = new Database();
$db = $database->getConnection();

$donthuoc = new DonThuoc($db);

$ma_don = isset($_GET['id']) ? $_GET['id'] : die();

$data = json_decode(file_get_contents("php://input"));

$donthuoc->ma_don = $ma_don;
$donthuoc->ma_kh = $data->ma_kh;
$donthuoc->ma_nv = $data->ma_nv;
$donthuoc->ngay_tao = $data->ngay_tao;
$donthuoc->da_thanh_toan = $data->da_thanh_toan;

if($donthuoc->update()) {
    http_response_code(200);
    echo json_encode(array("message" => "Đơn thuốc đã được cập nhật thành công."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Không thể cập nhật đơn thuốc."));
}
?> 