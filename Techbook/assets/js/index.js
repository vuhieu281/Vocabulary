const reviews = [
  {
    id: 1,
    title: "Review Macbook Pro M1",
    reviewer: "Nguyễn Văn A",
    date: "2025-04-06",
    stars: 5,
  },
  // Thêm dữ liệu mẫu khác...
];

document.addEventListener("DOMContentLoaded", function () {
  // Kiểm tra trạng thái đăng nhập
  const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
  const userMenu = document.querySelector(".user-menu");
  const loginBtn = document.querySelector(".login-btn");
  const dropdownMenu = document.querySelector(".dropdown-menu");

  // Xử lý hiển thị menu user
  function updateUserMenu() {
    if (isLoggedIn) {
      loginBtn.textContent = localStorage.getItem("username") || "TÀI KHOẢN";
      dropdownMenu.style.display = "block";
    } else {
      loginBtn.textContent = "ĐĂNG NHẬP";
      dropdownMenu.style.display = "none";
    }
  }

  // Toggle dropdown menu khi click vào nút đăng nhập
  loginBtn.addEventListener("click", function (e) {
    if (isLoggedIn) {
      e.preventDefault();
      dropdownMenu.classList.toggle("active");
    }
  });

  // Xử lý đăng xuất
  const logoutBtn = document.querySelector(".logout-btn");
  logoutBtn.addEventListener("click", function (e) {
    e.preventDefault();
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("username");
    window.location.href = "./dangnhap.html";
  });

  // Xử lý tìm kiếm và lọc
  const searchInput = document.querySelector(".search-input input");
  const filterSelect = document.querySelector(".search-filter select");
  const reviewsContainer = document.querySelector(".reviews");

  function filterReviews() {
    const searchTerm = searchInput.value.toLowerCase();
    const filterValue = filterSelect.value;
    const reviewCards = document.querySelectorAll(".review-card");

    reviewCards.forEach((card) => {
      const title = card.querySelector(".title").textContent.toLowerCase();
      const stars = card.querySelector(".stars").textContent.length;
      let show = title.includes(searchTerm);

      if (filterValue !== "Tất cả") {
        if (filterValue === "newest") {
          // Implement sort by date
        } else if (filterValue === "oldest") {
          // Implement sort by date
        } else {
          show = show && stars === parseInt(filterValue);
        }
      }

      card.style.display = show ? "block" : "none";
    });
  }

  // Thêm event listeners cho tìm kiếm và lọc
  searchInput.addEventListener("input", filterReviews);
  filterSelect.addEventListener("change", filterReviews);

  // Xử lý phân trang
  const paginationLinks = document.querySelectorAll(".pagination a");
  const itemsPerPage = 9;
  let currentPage = 1;

  function updatePagination() {
    const totalItems = document.querySelectorAll(
      '.review-card:not([style*="display: none"])'
    ).length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    // Hiển thị các review theo trang hiện tại
    const reviews = document.querySelectorAll(".review-card");
    reviews.forEach((review, index) => {
      const shouldShow =
        index >= (currentPage - 1) * itemsPerPage &&
        index < currentPage * itemsPerPage;
      review.style.display = shouldShow ? "block" : "none";
    });

    // Cập nhật UI phân trang
    paginationLinks.forEach((link) => {
      const pageNum = parseInt(link.textContent);
      if (!isNaN(pageNum)) {
        link.classList.toggle("active", pageNum === currentPage);
      }
    });
  }

  paginationLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const text = this.textContent;

      if (text === "Previous") {
        if (currentPage > 1) currentPage--;
      } else if (text === "Next") {
        const totalItems = document.querySelectorAll(".review-card").length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        if (currentPage < totalPages) currentPage++;
      } else {
        currentPage = parseInt(text);
      }

      updatePagination();
    });
  });

  // Khởi tạo ban đầu
  updateUserMenu();
  updatePagination();

  // Click outside to close dropdown
  document.addEventListener("click", function (e) {
    if (!userMenu.contains(e.target)) {
      dropdownMenu.classList.remove("active");
    }
  });
});
