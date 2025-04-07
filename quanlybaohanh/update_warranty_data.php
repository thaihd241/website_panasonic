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
    $name = $data['name'];
    $phone = $data['phone'];
    $address = $data['address'];
    $email = $data['email'];
    $product_name = $data['product_name'];
    $model = $data['model'];
    $serial = $data['serial'];
    $purchase_date = $data['purchase_date'];
    $store = $data['store'];
    $issue_description = $data['issue_description'];

    // Cập nhật dữ liệu bảo hành
    $sql = "UPDATE warranty_info 
            SET name = ?, phone = ?, address = ?, email = ?, product_name = ?, model = ?, serial = ?, purchase_date = ?, store = ?, issue_description = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $name, $phone, $address, $email, $product_name, $model, $serial, $purchase_date, $store, $issue_description, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Cập nhật thành công"]);
    } else {
        echo json_encode(["success" => false, "message" => "Cập nhật thất bại"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ"]);
}

$conn->close();
?>
