document
  .getElementById("warrantyForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault();

    const serialNumber = document.getElementById("serialNumber").value;

    try {
      const response = await fetch("check-warranty.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ serialNumber }),
      });

      if (response.ok) {
        const data = await response.json();

        document.getElementById("product_name").innerText =
          data.product_name || "Không có thông tin";
        document.getElementById("purchase_date").innerText =
          data.purchase_date || "Không có thông tin";
        document.getElementById("thoigianconlai").innerText =
          data.remaining_warranty || "Không có thông tin";
        document.getElementById("tinhtrang").innerText =
          data.status || "Không có thông tin";
        document.getElementById("contactInfo").innerText =
          data.contact || "Không có thông tin";

        document.getElementById("result").style.display = "block";
      } else {
        alert(
          "Không tìm thấy thông tin bảo hành. Vui lòng kiểm tra lại mã sản phẩm."
        );
      }
    } catch (error) {
      console.error("Lỗi khi gửi yêu cầu:", error);
      alert("Đã xảy ra lỗi. Vui lòng thử lại sau.");
    }
  });
