<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ... phần code còn lại ...
// Hàm tạo response JSON
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Hàm validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hàm validate số điện thoại
function validatePhone($phone) {
    return preg_match('/^[0-9]{10,11}$/', $phone);
}

// Hàm format tiền tệ
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

// Hàm format ngày tháng
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

// Hàm tạo mã tự động
function generateCode($prefix, $table, $column, $db) {
    $query = "SELECT MAX(CAST(SUBSTRING($column, " . (strlen($prefix) + 1) . ") AS UNSIGNED)) as max_id FROM $table WHERE $column LIKE '$prefix%'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $next_id = ($row['max_id'] ?? 0) + 1;
    return $prefix . str_pad($next_id, 4, '0', STR_PAD_LEFT);
}

// Hàm kiểm tra quyền truy cập
function checkPermission($requiredRole) {
    // Implement your permission logic here
    return true; // Placeholder
}

// Hàm log hoạt động
function logActivity($action, $details, $user_id = null) {
    // Implement your logging logic here
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => $action,
        'details' => $details,
        'user_id' => $user_id
    ];
    
    // You can save to database or file
    error_log(json_encode($log_entry) . "\n", 3, '../logs/activity.log');
}

// Hàm sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Hàm validate required fields
function validateRequired($data, $required_fields) {
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $errors[] = "Trường $field là bắt buộc";
        }
    }
    return $errors;
}

// Hàm tạo token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Hàm hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Hàm verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?> 