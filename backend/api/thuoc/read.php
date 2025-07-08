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
// ... phần code còn lại
include_once '../../config/database.php';
include_once '../../models/Thuoc.php';

$database = new Database();
$db = $database->getConnection();

$thuoc = new Thuoc($db);

$stmt = $thuoc->read();
$num = $stmt->rowCount();

if($num > 0) {
    $thuoc_arr = array();
    $thuoc_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $thuoc_item = array(
            "ma_thuoc" => $ma_thuoc,
            "ten_thuoc" => $ten_thuoc,
            "don_vi" => $don_vi,
            "gia_nhap" => $gia_nhap,
            "gia_ban" => $gia_ban,
            "ngay_them" => $ngay_them,
            "sl_toi_thieu" => $sl_toi_thieu
        );

        array_push($thuoc_arr["records"], $thuoc_item);
    }

    http_response_code(200);
    echo json_encode($thuoc_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Không tìm thấy thuốc nào."));
}
?> 