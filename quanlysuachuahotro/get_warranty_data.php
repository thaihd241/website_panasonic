<?php
// Cấu hình kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root"; // Tên người dùng MySQL
$password = ""; // Mật khẩu MySQL
$dbname = "web_panasonic"; // Tên cơ sở dữ liệu

// Kết nối tới cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn dữ liệu từ bảng bảo hành
$sql = "SELECT * FROM `request support`"; // Câu lệnh truy vấn
$result = $conn->query($sql);

// Kiểm tra kết quả truy vấn
if ($result->num_rows > 0) {
    // Dữ liệu trả về
    $warranty_data = [];
    while($row = $result->fetch_assoc()) {
        $warranty_data[] = $row;
    }
    echo json_encode($warranty_data); // Trả về dữ liệu dưới dạng JSON
} else {
    echo json_encode([]); // Nếu không có dữ liệu
}

// Đóng kết nối
$conn->close();
?>
