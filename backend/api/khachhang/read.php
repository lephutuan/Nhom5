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
include_once '../../models/KhachHang.php';

$database = new Database();
$db = $database->getConnection();

$khachhang = new KhachHang($db);

$stmt = $khachhang->read();
$num = $stmt->rowCount();

if($num > 0) {
    $khachhang_arr = array();
    $khachhang_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $khachhang_item = array(
            "ma_kh" => $ma_kh,
            "ten_kh" => $ten_kh,
            "ngay_sinh" => $ngay_sinh,
            "sdt" => $sdt,
            "dia_chi" => $dia_chi,
            "di_ung" => $di_ung
        );

        array_push($khachhang_arr["records"], $khachhang_item);
    }

    http_response_code(200);
    echo json_encode($khachhang_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Không tìm thấy khách hàng nào."));
}
?> 