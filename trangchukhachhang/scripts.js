document.addEventListener("DOMContentLoaded", function () {
  // Lấy tên đăng nhập từ localStorage
  // const username = localStorage.getItem("username");

  // if (username) {
  //   // Cập nhật phần chào
  //   const userGreeting = document.getElementById("user-greeting");
  //   userGreeting.textContent = `Chào bạn, ${username}`;
  // }

  // Xử lý nút Đăng xuất
  const logoutBtn = document.getElementById("logout-btn");
  logoutBtn.addEventListener("click", function () {
    // Xóa thông tin đăng nhập
    localStorage.removeItem("username");
    // Quay về trang đăng nhập
    window.location.replace(
      "http://localhost/Web_Panasonic/trangchu/index.html"
    );
  });
});
