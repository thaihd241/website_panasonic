function fetchAccountData() {
  // Gửi yêu cầu đến API
  fetch("get_user_account.php", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
    credentials: "include", // Để gửi cookie/session
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch account data");
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        // Điền thông tin vào các trường input
        document.getElementById("username").value = data.data.username;
        document.getElementById("email").value = data.data.email;
        document.getElementById("password").value = data.data.password;
      } else {
        alert(data.message || "Không thể tải dữ liệu tài khoản.");
      }
    })
    .catch((error) => {
      console.error("Lỗi:", error);
      alert("Đã xảy ra lỗi khi tải dữ liệu tài khoản.");
    });
}

// Gọi hàm này khi trang được tải hoặc khi người dùng mở chức năng Quản Lý Tài Khoản
document.addEventListener("DOMContentLoaded", fetchAccountData);
