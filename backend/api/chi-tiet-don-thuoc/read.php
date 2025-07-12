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

include_once '../../config/database.php';
include_once '../../models/ChiTietDonThuoc.php';

$database = new Database();
$db = $database->getConnection();

$chiTietDonThuoc = new ChiTietDonThuoc($db);

// Lấy mã đơn từ query parameter
$ma_don = isset($_GET['ma_don']) ? $_GET['ma_don'] : die();

$stmt = $chiTietDonThuoc->getByDonThuoc($ma_don);
$num = $stmt->rowCount();

if($num > 0) {
    $chi_tiet_arr = array();
    $chi_tiet_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $chi_tiet_item = array(
            "ma_don" => $ma_don,
            "ma_thuoc" => $ma_thuoc,
            "ten_thuoc" => $ten_thuoc,
            "lieu_luong" => $lieu_luong,
            "so_ngay" => $so_ngay,
            "so_luong" => $so_luong,
            "gia_ban" => $gia_ban,
            "don_vi" => $don_vi
        );

        array_push($chi_tiet_arr["records"], $chi_tiet_item);
    }

    http_response_code(200);
    echo json_encode($chi_tiet_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Không tìm thấy chi tiết đơn thuốc nào."));
}
?> 