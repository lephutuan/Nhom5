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
// ... phần code còn lại ...
include_once '../../config/database.php';
include_once '../../models/Thuoc.php';

$database = new Database();
$db = $database->getConnection();

$thuoc = new Thuoc($db);

// Nhận ma_thuoc từ POST (JSON hoặc form)
$input = json_decode(file_get_contents('php://input'), true);
if (isset($input['ma_thuoc'])) {
    $ma_thuoc = $input['ma_thuoc'];
} elseif (isset($_POST['ma_thuoc'])) {
    $ma_thuoc = $_POST['ma_thuoc'];
} elseif (isset($_GET['id'])) {
    $ma_thuoc = $_GET['id'];
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Thiếu mã thuốc để xóa."));
    exit();
}
$thuoc->ma_thuoc = $ma_thuoc;

if($thuoc->delete()) {
    http_response_code(200);
    echo json_encode(array("message" => "Thuốc đã được xóa thành công."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Không thể xóa thuốc."));
}
?> 