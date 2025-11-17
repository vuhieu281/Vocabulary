document.addEventListener("DOMContentLoaded", function () {
  // Xử lý sự kiện nút tăng số lượng
  document
    .querySelectorAll(".quantity-btn.increase")
    .forEach(function (button) {
      button.addEventListener("click", function () {
        const input = this.parentElement.querySelector(".quantity-input");
        const cartId = input.getAttribute("data-cart-id");
        let value = parseInt(input.value, 10);
        value++;
        updateCartItem(cartId, value);
      });
    });

  // Xử lý sự kiện nút giảm số lượng
  document
    .querySelectorAll(".quantity-btn.decrease")
    .forEach(function (button) {
      button.addEventListener("click", function () {
        const input = this.parentElement.querySelector(".quantity-input");
        const cartId = input.getAttribute("data-cart-id");
        let value = parseInt(input.value, 10);
        if (value > 1) {
          value--;
          updateCartItem(cartId, value);
        }
      });
    });

  // Xử lý sự kiện thay đổi số lượng trực tiếp
  document.querySelectorAll(".quantity-input").forEach(function (input) {
    input.addEventListener("change", function () {
      const cartId = this.getAttribute("data-cart-id");
      let value = parseInt(this.value, 10);
      if (isNaN(value) || value < 1) {
        value = 1;
        this.value = 1;
      }
      updateCartItem(cartId, value);
    });
  });

  // Xử lý sự kiện nút xóa - SỬA ĐỔI Ở ĐÂY
  document.querySelectorAll(".remove-btn").forEach(function (button) {
    button.addEventListener("click", function (event) {
      // Ngăn hành động mặc định của nút
      event.preventDefault();
      event.stopPropagation();
      
      const cartId = this.getAttribute("data-cart-id");
      // Chỉ gọi removeCartItem mà không dùng confirm - sẽ xử lý trong hàm
      removeCartItem(cartId);
    });
  });
});

// Hàm cập nhật số lượng sản phẩm
function updateCartItem(cartId, quantity) {
  const formData = new FormData();
  formData.append("action", "update");
  formData.append("cart_id", cartId);
  formData.append("quantity", quantity);

  fetch('includes/cart_actions.php', {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Cập nhật giao diện
        updateCartUI(data);
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Đã xảy ra lỗi khi cập nhật giỏ hàng");
    });
}

// Hàm xóa sản phẩm khỏi giỏ hàng - THÊM CONFIRM TẠI ĐÂY
function removeCartItem(cartId) {
  // Thêm confirm chỉ ở đây để tránh hiện 2 thông báo
  if (!confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?")) {
    return;
  }

  const formData = new FormData();
  formData.append("action", "remove");
  formData.append("cart_id", cartId);

  fetch('includes/cart_actions.php', {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Xóa dòng sản phẩm khỏi bảng
        const row = document.querySelector(
          `.product-row[data-cart-id="${cartId}"]`
        );
        if (row) {
          row.remove();
        }

        // Cập nhật tổng giỏ hàng
        document.querySelector(".subtotal-amount").textContent =
          data.cart_total + " VNĐ";
        document.querySelector(".total-amount").textContent =
          data.cart_total + " VNĐ";
        document.getElementById("cart-count").textContent = data.cart_count;

        // Nếu giỏ hàng trống, hiển thị thông báo
        if (data.cart_count === 0) {
          const tbody = document.getElementById("cart-items-container");
          tbody.innerHTML =
            '<tr><td colspan="5" style="text-align: center; padding: 20px;">Giỏ hàng trống. <a href="danhmucsp2.php">Tiếp tục mua sắm</a></td></tr>';

          // Vô hiệu hóa nút thanh toán
          document.querySelector(".checkout-btn").disabled = true;
          document.querySelector(".checkout-btn").style.backgroundColor =
            "#ccc";
        }
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Đã xảy ra lỗi khi xóa sản phẩm khỏi giỏ hàng");
    });
}

// Hàm cập nhật giao diện giỏ hàng
function updateCartUI(data) {
  // Cập nhật số lượng cho tất cả các sản phẩm
  document.getElementById("cart-count").textContent = data.cart_count;

  // Cập nhật tổng tiền
  document.querySelector(".subtotal-amount").textContent =
    data.cart_total + " VNĐ";
  document.querySelector(".total-amount").textContent =
    data.cart_total + " VNĐ";

  // Cập nhật thành tiền cho từng sản phẩm
  data.cart_items.forEach((item) => {
    const row = document.querySelector(
      `.product-row[data-cart-id="${item.id}"]`
    );
    if (row) {
      const subtotalElement = row.querySelector(".subtotal-col");
      if (subtotalElement) {
        subtotalElement.textContent =
          new Intl.NumberFormat("vi-VN").format(item.subtotal) + " VNĐ";
      }

      const quantityInput = row.querySelector(".quantity-input");
      if (quantityInput && parseInt(quantityInput.value) !== item.quantity) {
        quantityInput.value = item.quantity;
      }
    }
  });
}
