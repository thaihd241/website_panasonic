<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $serialNumber = $input['serialNumber'] ?? '';

    if (!$serialNumber) {
        echo json_encode(['error' => 'Mã sản phẩm không hợp lệ.']);
        exit;
    }

    // Kết nối đến cơ sở dữ liệu
    $dbHost = 'localhost';
    $dbName = 'web_panasonic';
    $dbUser = 'root';
    $dbPass = '';

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        echo json_encode(['error' => 'Không thể kết nối cơ sở dữ liệu.']);
        exit;
    }

    // Truy vấn thông tin bảo hành
    $stmt = $conn->prepare("SELECT product_name, purchase_date FROM warranty_info WHERE serial = ?");
    $stmt->bind_param('s', $serialNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $productName = $row['product_name'];
        $purchaseDate = $row['purchase_date'];

        $purchaseDateTime = new DateTime($purchaseDate);
        $now = new DateTime();
        $warrantyEndDate = clone $purchaseDateTime;
        $warrantyEndDate->modify("+12 months"); // Giả định bảo hành là 12 tháng

        $remainingWarranty = $now < $warrantyEndDate ? $warrantyEndDate->diff($now)->format('%y năm, %m tháng, %d ngày') : 'Hết hạn';

        echo json_encode([
            'product_name' => $productName,
            'purchase_date' => $purchaseDate,
            'remaining_warranty' => $remainingWarranty,
            'status' => $now < $warrantyEndDate ? 'Còn bảo hành' : 'Hết bảo hành',
            'contact' => '1800-1234 (Tổng đài hỗ trợ Panasonic)',
        ]);
    } else {
        echo json_encode(['error' => 'Không tìm thấy thông tin sản phẩm.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Phương thức không được hỗ trợ.']);
}
?>
