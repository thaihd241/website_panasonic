<?php
session_start();

// Đảm bảo người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Chưa xác thực
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
// Kiểm tra nếu có lỗi trong PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$email = $data['email'];
$password = $data['password'];

// Kiểm tra dữ liệu đầu vào
if (empty($username) || empty($email) || empty($password)) {
    http_response_code(400); // Yêu cầu không hợp lệ
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin.']);
    exit;
}

// Kết nối cơ sở dữ liệu
$host = 'localhost';
$dbname = 'web_panasonic';
$username_db = 'your_db_username';
$password_db = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cập nhật thông tin người dùng
    $stmt = $pdo->prepare('UPDATE user SET username = :username, email = :email, password = :password WHERE id = :id');
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR); // Lưu mật khẩu dưới dạng plain text
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thông tin tài khoản đã được cập nhật.']);
    } else {
        http_response_code(500); // Lỗi server
        echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thông tin tài khoản.']);
    }
} catch (PDOException $e) {
    http_response_code(500); // Lỗi server
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
