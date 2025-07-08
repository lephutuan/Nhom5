<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ... phần code còn lại ...
include_once '../../config/database.php';
include_once '../../models/NhanVien.php';

$database = new Database();
$db = $database->getConnection();

$nhanvien = new NhanVien($db);

$stmt = $nhanvien->read();
$num = $stmt->rowCount();

if($num > 0) {
    $nhanvien_arr = array();
    $nhanvien_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $nhanvien_item = array(
            "ma_nv" => $ma_nv,
            "ten_nv" => $ten_nv,
            "chuc_vu" => $chuc_vu,
            "sdt" => $sdt,
            "dia_chi" => $dia_chi,
            "trang_thai" => $trang_thai
        );

        array_push($nhanvien_arr["records"], $nhanvien_item);
    }

    http_response_code(200);
    echo json_encode($nhanvien_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Không tìm thấy nhân viên nào."));
}
?> 