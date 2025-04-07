<?php
session_start();

// Kiểm tra người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Chưa xác thực
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để xóa tài khoản.']);
    exit;
}

// Kết nối đến cơ sở dữ liệu
$host = 'localhost';
$dbname = 'web_panasonic';
$username_db = 'your_db_username'; // Thay bằng tên người dùng DB của bạn
$password_db = 'your_db_password'; // Thay bằng mật khẩu DB của bạn

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lấy id người dùng từ session
    $user_id = $_SESSION['user_id'];

    // Xóa tài khoản người dùng khỏi cơ sở dữ liệu
    $stmt = $pdo->prepare('DELETE FROM user WHERE id = :id');
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Hủy session sau khi xóa tài khoản
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Tài khoản đã được xóa thành công.']);
    } else {
        http_response_code(500); // Lỗi server
        echo json_encode(['success' => false, 'message' => 'Không thể xóa tài khoản.']);
    }
} catch (PDOException $e) {
    http_response_code(500); // Lỗi cơ sở dữ liệu
    echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
}
?>
