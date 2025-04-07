<?php
// Kết nối với cơ sở dữ liệu 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_panasonic";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Lỗi kết nối cơ sở dữ liệu."]);
    exit();
}
?>
