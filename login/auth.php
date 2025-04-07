<?php
session_start();  // Bắt đầu session

$servername = "localhost"; // Hoặc địa chỉ IP của server
$username = "root"; // Tên người dùng MySQL của bạn
$password = ""; // Mật khẩu MySQL của bạn
$dbname = "web_panasonic"; // Tên cơ sở dữ liệu của bạn

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Hàm kiểm tra tên đăng nhập hợp lệ
function isValidUsername($username) {
    return preg_match('/^[a-zA-Z0-9]{5,20}$/', $username);
}
// Hàm kiểm tra email hợp lệ
function isValidEmail($email) {
    $regex = '/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}$/';
    //return filter_var($email, FILTER_VALIDATE_EMAIL);
    return preg_match($regex, $email);
}
// Hàm kiểm tra mật khẩu hợp lệ
function isValidPassword($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/', $password);
}
//-----------------------------------------------------------------------------
// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['login-username'];
    $password = $_POST['login-password'];

    // Truy vấn để lấy thông tin người dùng từ cơ sở dữ liệu
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Kiểm tra mật khẩu
    if ($user && $user['password'] == $password) { // So sánh mật khẩu dưới dạng văn bản thuần túy
        // Lưu thông tin người dùng vào session
        $_SESSION['user_id'] = $user['id'];  // Giả sử bạn có trường 'id' trong bảng user
        $_SESSION['role'] = $user['role'];    // Lưu role vào session
        // Kiểm tra quyền của người dùng
        if ($user['role'] == 'admin') {
            echo "<script>
                alert('Đăng nhập thành công với quyền admin!');
                window.location.href = 'http://localhost/Web_panasonic/trangchuadmin/index.html';
            </script>";
            // header("Location: http://localhost/Web_panasonic/trangchuadmin/index.html");
            exit;  // Dừng thực thi tiếp theo để không bị lỗi
        } else if ($user['role'] == 'customer') {
            echo "<script>
                alert('Đăng nhập thành công!');
                window.location.href = 'http://localhost/Web_panasonic/trangchukhachhang/index.html';
            </script>";
            // header("Location: http://localhost/Web_panasonic/trangchukhachhang/index.html");
            exit;  // Dừng thực thi tiếp theo
        }
    } else {
        echo "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}

// Xử lý đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['register-username'];
    $email = $_POST['register-email'];
    $password = $_POST['register-password'];
    $role = "customer";
    // $role = $_POST['register-role'];

    // Kiểm tra tên đăng nhập hợp lệ
    if (!isValidUsername($username)) {
        echo "<script>
                alert('Tên đăng nhập không hợp lệ! Chỉ chứa chữ cái và số, dài từ 5-20 ký tự, không khoảng trắng!');
                window.location.href = 'http://localhost/Web_Panasonic/login/index.html';
            </script>";
        exit;
    }

    // Kiểm tra email hợp lệ
    if (!isValidEmail($email)) {
        echo "<script>
                alert('Địa chỉ email không hợp lệ!');
                window.location.href = 'http://localhost/Web_Panasonic/login/index.html';
            </script>";
        exit;
    }
     // Kiểm tra mật khẩu hợp lệ
     if (!isValidPassword($password)) {
        echo "<script>
                alert('Mật khẩu phải có từ 8 đến 20 ký tự, bao gồm ít nhất một chữ hoa, một chữ thường, một số và một ký tự đặc biệt!');
                window.location.href = 'http://localhost/Web_Panasonic/login/index.html';
            </script>";
        exit;
    }
    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<script>
                alert('Tên đăng nhập đã tồn tại!');
                window.location.href = 'http://localhost/Web_Panasonic/login/index.html';
            </script>";
        exit;
    } 

    // Kiểm tra xem email đã tồn tại chưa
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Email đã tồn tại!');
                window.location.href = 'http://localhost/Web_Panasonic/login/index.html';
            </script>";
        exit;
    }


    // Thêm người dùng vào cơ sở dữ liệu (mật khẩu dưới dạng văn bản thuần túy)
    $sql = "INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $password, $role);
    if ($stmt->execute()) {
        echo "<script>
            alert('Đăng ký thành công!');
            window.location.href = 'http://localhost/Web_Panasonic/login/index.html';
        </script>";
        exit; // Ngăn không cho thực thi thêm mã PHP sau đó
    } else {
        echo "<script>alert('Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại.');</script>";
    }
}
?>
