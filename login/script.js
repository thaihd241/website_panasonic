// Lấy các phần tử cần thiết
const loginTab = document.getElementById("login-tab");
const registerTab = document.getElementById("register-tab");
const loginForm = document.getElementById("login-form");
const registerForm = document.getElementById("register-form");
const loginFormElement = document.getElementById("loginForm");
const registerFormElement = document.getElementById("registerForm");

// Thêm sự kiện click cho các tab
loginTab.addEventListener("click", () => {
  // Đổi trạng thái active của các tab
  loginTab.classList.add("active");
  registerTab.classList.remove("active");

  // Hiển thị form đăng nhập và ẩn form đăng ký
  loginForm.classList.add("active");
  registerForm.classList.remove("active");
});

registerTab.addEventListener("click", () => {
  // Đổi trạng thái active của các tab
  registerTab.classList.add("active");
  loginTab.classList.remove("active");

  // Hiển thị form đăng ký và ẩn form đăng nhập
  registerForm.classList.add("active");
  loginForm.classList.remove("active");
});
