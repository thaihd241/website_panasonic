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

// Lấy dữ liệu JSON từ request
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    $id = intval($data['id']);
    $username = $data['username'];
    $email = $data['email'];
    $phone = $data['phone'];
    $serial = $data['serial'];
    $type_problem = $data['type_problem'];
    $describe = $data['describe'];

    // Cập nhật dữ liệu bảo hành
    $sql = "UPDATE `request support` 
            SET username = ?, email = ?, phone = ?, serial = ?, type_problem = ?, `describe` = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $username, $email, $phone, $serial, $type_problem, $describe, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Cập nhật thành công"]);
    } else {
        error_log("Error executing query: " . $stmt->error); // Log lỗi
        echo json_encode(["success" => false, "message" => "Cập nhật thất bại"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ"]);
}

$conn->close();
?>
