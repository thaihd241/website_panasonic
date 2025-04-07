<?php
header('Content-Type: application/json');

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_panasonic";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối cơ sở dữ liệu thất bại.']);
    exit;
}

// Lấy ID từ query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Xóa dữ liệu khỏi cơ sở dữ liệu
    $sql = "DELETE FROM warranty_info WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Xóa dữ liệu thất bại.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ.']);
}

$conn->close();
?>
