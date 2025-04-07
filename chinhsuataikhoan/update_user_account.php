<?php
session_start();

// Đảm bảo người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Chưa xác thực
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Kết nối cơ sở dữ liệu
$host = 'localhost';
$dbname = 'web_panasonic';
$username = 'your_db_username';
$password = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Truy vấn thông tin người dùng
    $stmt = $pdo->prepare('SELECT username, email FROM user WHERE id = :id');
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Trả về dữ liệu dạng JSON
        echo json_encode(['success' => true, 'data' => $user]);
    } else {
        http_response_code(404); // Không tìm thấy người dùng
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} catch (PDOException $e) {
    http_response_code(500); // Lỗi server
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
