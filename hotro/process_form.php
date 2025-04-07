<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root"; // Thay bằng tên người dùng MySQL của bạn
$password = ""; // Thay bằng mật khẩu MySQL của bạn
$dbname = "web_panasonic";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý form khi người dùng submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $username = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $serial = $_POST['serialNumber'];
    $type_problem = $_POST['issueType'];
    $describe = $_POST['details'];
    
    
    // Xử lý file upload
    $file_upload = '';
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
        }
        $target_file = $target_dir . basename($_FILES['fileUpload']['name']);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Kiểm tra loại file
        $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $target_file)) {
                $file_upload = $target_file;
            } else {
                echo "Lỗi khi tải tệp.";
                exit;
            }
        } else {
            echo "Định dạng tệp không hợp lệ.";
            exit;
        }
    }

    // Lưu thông tin vào cơ sở dữ liệu
$query = "INSERT INTO `request support` (username, email, phone, serial, type_problem, `describe`, file_upload) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Lỗi chuẩn bị câu lệnh SQL: " . $conn->error);
}

$stmt->bind_param("sssssss", $username, $email, $phone, $serial, $type_problem, $describe, $file_upload);

if ($stmt->execute()) {
    echo "<script>
        alert('Gửi thông tin thành công!');
        window.location.href = 'http://localhost/Web_panasonic/trangchukhachhang/index.html';
    </script>";
    
} else {
    echo "<script>alert('Lỗi: " . $stmt->error . "');</script>";
    error_log("SQL Error: " . $stmt->error);
}

$stmt->close();
$conn->close();
}

$conn->close();
?>
