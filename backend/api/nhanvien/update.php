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
include_once '../../models/NhanVien.php';

$database = new Database();
$db = $database->getConnection();

$nhanvien = new NhanVien($db);

$ma_nv = isset($_GET['id']) ? $_GET['id'] : die();

$data = json_decode(file_get_contents("php://input"));

$nhanvien->ma_nv = $ma_nv;
$nhanvien->ten_nv = $data->ten_nv;
$nhanvien->chuc_vu = $data->chuc_vu;
$nhanvien->sdt = $data->sdt;
$nhanvien->dia_chi = $data->dia_chi;
$nhanvien->trang_thai = $data->trang_thai;

if($nhanvien->update()) {
    http_response_code(200);
    echo json_encode(array("message" => "Nhân viên đã được cập nhật thành công."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Không thể cập nhật nhân viên."));
}
?> 