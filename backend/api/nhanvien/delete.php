<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../models/NhanVien.php';

$database = new Database();
$db = $database->getConnection();

$nhanvien = new NhanVien($db);

$ma_nv = isset($_GET['id']) ? $_GET['id'] : die();

$nhanvien->ma_nv = $ma_nv;

if($nhanvien->delete()) {
    http_response_code(200);
    echo json_encode(array("message" => "Nhân viên đã được xóa thành công."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Không thể xóa nhân viên."));
}
?> 