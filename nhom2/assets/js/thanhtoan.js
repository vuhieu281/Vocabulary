document.addEventListener("DOMContentLoaded", function () {
  // Xử lý nút đặt hàng
  document
    .getElementById("place-order-btn")
    .addEventListener("click", function (e) {
      e.preventDefault();
      placeOrder();
    });
});

// Hàm xử lý việc đặt hàng
function placeOrder() {
  // Hiển thị trạng thái đang xử lý
  const orderBtn = document.getElementById("place-order-btn");
  orderBtn.textContent = "Đang xử lý...";
  orderBtn.disabled = true;

  // Lấy thông tin khách hàng từ form
  const firstName = document.getElementById("first-name").value.trim();
  const lastName = document.getElementById("last-name").value.trim();
  const email = document.getElementById("email").value.trim();
  const phone = document.getElementById("phone").value.trim();
  const address = document.getElementById("street-address").value.trim();
  const city = document.getElementById("town-city").value.trim();
  const province = document.getElementById("province").value.trim();
  const additionalInfo = document.getElementById("additional-info").value.trim();

  // Kiểm tra các trường bắt buộc
  if (
    !firstName ||
    !lastName ||
    !email ||
    !phone ||
    !address ||
    !city ||
    !province
  ) {
    alert("Vui lòng điền đầy đủ thông tin bắt buộc");
    resetButton();
    return;
  }

  // Kiểm tra email hợp lệ
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    alert("Email không hợp lệ");
    resetButton();
    return;
  }

  // Lấy phương thức thanh toán
  const paymentMethod = document.querySelector(
    'input[name="payment-method"]:checked'
  );
  if (!paymentMethod) {
    alert("Vui lòng chọn phương thức thanh toán");
    resetButton();
    return;
  }

  // Tạo đối tượng đơn hàng
  const order = {
    customer: {
      first_name: firstName,
      last_name: lastName,
      email: email,
      phone: phone,
      address: address,
      city: city,
      province: province,
    },
    payment_method: paymentMethod.value,
    additional_info: additionalInfo,
  };

  fetch("includes/process_order.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(order),
  })
    .then((response) => {
      if (!response.ok) {
        return response.json().then(data => {
          throw new Error(data.message || "Lỗi khi xử lý đơn hàng");
        });
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        // Chuyển hướng đến trang xác nhận
        window.location.href =
          "order_confirmation.php?order_id=" + data.order_id;
      } else {
        throw new Error(data.message || "Có lỗi xảy ra khi xử lý đơn hàng =((");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert(error.message);
      resetButton();
    });
    
  // Hàm để reset nút đặt hàng
  function resetButton() {
    orderBtn.textContent = "Đặt Hàng";
    orderBtn.disabled = false;
  }
}
