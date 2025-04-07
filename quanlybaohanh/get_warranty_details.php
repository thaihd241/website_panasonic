<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_panasonic";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra ID từ request
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Truy vấn thông tin bảo hành theo ID
    $sql = "SELECT * FROM warranty_info WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Không tìm thấy thông tin bảo hành"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Thiếu ID"]);
}

$conn->close();
?>
