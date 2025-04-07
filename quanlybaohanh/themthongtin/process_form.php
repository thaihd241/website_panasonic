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
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $product_name = $_POST['product-name'];
    $model = $_POST['model'];
    $serial = $_POST['serial'];
    $purchase_date = $_POST['purchase-date'];
    $store = $_POST['store'];
    $issue_description = $_POST['issue'];
    
    // Xử lý file upload
    $invoice_path = '';
    if (isset($_FILES['invoice-upload']) && $_FILES['invoice-upload']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
        }
        $target_file = $target_dir . basename($_FILES['invoice-upload']['name']);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Kiểm tra loại file
        $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['invoice-upload']['tmp_name'], $target_file)) {
                $invoice_path = $target_file;
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
    $stmt = $conn->prepare("INSERT INTO warranty_info (name, phone, address, email, product_name, model, serial, purchase_date, store, issue_description, invoice_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $name, $phone, $address, $email, $product_name, $model, $serial, $purchase_date, $store, $issue_description, $invoice_path);

    if ($stmt->execute()) {
        echo "<script>alert('Gửi thông tin thành công!');
        window.location.href = 'http://localhost/Web_panasonic/quanlybaohanh/index.html'; </script>";
        //window.location.href='success_page.php';
    exit(); // Dừng thực thi mã sau khi chuyển hướng
    } else {
        echo "<script>alert('Lỗi: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
