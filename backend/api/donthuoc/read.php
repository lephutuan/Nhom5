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
include_once '../../models/DonThuoc.php';

$database = new Database();
$db = $database->getConnection();

$donthuoc = new DonThuoc($db);

$stmt = $donthuoc->read();
$num = $stmt->rowCount();

if($num > 0) {
    $donthuoc_arr = array();
    $donthuoc_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $donthuoc_item = array(
            "ma_don" => $ma_don,
            "ma_kh" => $ma_kh,
            "ma_nv" => $ma_nv,
            "ten_kh" => $ten_kh,
            "ten_nv" => $ten_nv,
            "ngay_tao" => $ngay_tao,
            "da_thanh_toan" => $da_thanh_toan
        );

        array_push($donthuoc_arr["records"], $donthuoc_item);
    }

    http_response_code(200);
    echo json_encode($donthuoc_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Không tìm thấy đơn thuốc nào."));
}
?> 