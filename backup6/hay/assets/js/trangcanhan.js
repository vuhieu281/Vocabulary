document.addEventListener("DOMContentLoaded", function () {
  // Check if user is logged in
  const isLoggedIn = localStorage.getItem("isLoggedIn");
  const userEmail = localStorage.getItem("userEmail");

  if (!isLoggedIn) {
    window.location.href = "./dangnhap.html";
    return;
  }

  // Mock user data (replace with API call)
  const userData = {
    name: "Nguyễn Văn A",
    location: "Hà Nội, Việt Nam",
    reviewCount: 3,
    avatar: "avatar-placeholder.png",
    reviews: [
      {
        id: 1,
        storeName: "Cửa hàng Tech A",
        storeAddress: "123 Đường Láng, Hà Nội",
        rating: 4,
        text: "Cửa hàng này bán đồ điện tử cũ rất chất lượng, giá cả hợp lý.",
        date: "2025-06-04T22:49:00",
        image: "placeholder.jpg",
        averageRating: 4.0,
        commentCount: 4,
        comments: [
          {
            userName: "Người dùng 1",
            text: "Sản phẩm tốt!",
            rating: 5,
          },
          {
            userName: "Người dùng 2",
            text: "Giá hơi cao.",
            rating: 3,
          },
          {
            userName: "Người dùng 3",
            text: "Dịch vụ ok.",
            rating: 4,
          },
        ],
      },
    ],
  };

  // Update profile information
  function updateProfileInfo() {
    document.querySelector(".user-details h2").textContent = userData.name;
    document.querySelector(".user-details p:nth-child(2)").textContent =
      userData.location;
    document.querySelector(
      ".user-details p:nth-child(3)"
    ).textContent = `${userData.reviewCount} Reviews`;
    document.querySelector(".avatar").src = userData.avatar;
  }

  // Generate star rating HTML
  function generateStarRating(rating) {
    return `${'<i class="fas fa-star"></i>'.repeat(
      rating
    )}${'<i class="far fa-star"></i>'.repeat(5 - rating)}`;
  }

  // Format date
  function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString("vi-VN", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  }

  // Render reviews
  function renderReviews() {
    const reviewsContainer = document.querySelector(".reviews-section");
    const reviewsHTML = userData.reviews
      .map(
        (review) => `
            <div class="review-card" data-review-id="${review.id}" id="review-${
          review.id
        }">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="${userData.avatar}" alt="${
          userData.name
        }" class="avatar-small">
                        <div>
                            <h4>${userData.name}</h4>
                            <span class="review-date">${formatDate(
                              review.date
                            )}</span>
                        </div>
                    </div>
                </div>

                <div class="review-content">
                    <h4 class="store-name">${review.storeName}</h4>
                    <p class="store-address">${review.storeAddress}</p>

                    <div class="rating">
                        ${generateStarRating(review.rating)}
                    </div>

                    <p class="review-text">${review.text}</p>

                    <div class="review-image">
                        <img src="${review.image}" alt="Ảnh đính kèm">
                    </div>

                    <div class="review-stats">
                        <span>Đánh giá trung bình: ${
                          review.averageRating
                        }/5</span>
                        <span>Tổng số bình luận: ${review.commentCount}</span>
                    </div>

                    <div class="comments-section">
                        ${review.comments
                          .map(
                            (comment) => `
                            <div class="comment">
                                <h5>${comment.userName}</h5>
                                <p>${comment.text}</p>
                                <div class="rating">
                                    ${generateStarRating(comment.rating)}
                                </div>
                            </div>
                        `
                          )
                          .join("")}
                        <a href="#" class="view-more">Xem thêm bình luận</a>
                    </div>

                    <!-- Edit review form (hidden by default) -->
                    <div class="edit-review-form" id="edit-form-${
                      review.id
                    }" style="display:none;">
                        <textarea class="edit-review-text">${
                          review.text
                        }</textarea>
                        <button class="save-review-btn" data-review-id="${
                          review.id
                        }">Lưu đánh giá</button>
                        <button class="cancel-review-btn" data-review-id="${
                          review.id
                        }">Hủy</button>
                    </div>

                    <!-- Edit and delete buttons -->
                    <div class="review-actions">
                        <button class="edit-review-btn" data-review-id="${
                          review.id
                        }">Chỉnh sửa</button>
                        <button class="delete-review-btn" data-review-id="${
                          review.id
                        }">Xóa</button>
                    </div>
                </div>
            </div>
        `
      )
      .join("");

    reviewsContainer.innerHTML = `<h3>Previous Reviews</h3>${reviewsHTML}`;
  }

  // Handle "View more comments" clicks
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("view-more")) {
      e.preventDefault();
      // Implement loading more comments here
      alert("Đang tải thêm bình luận...");
    }
  });

  // Function to show edit form
  window.showEditForm = function (reviewId) {
    const editForm = document.getElementById(`edit-form-${reviewId}`);
    const reviewContent = document.querySelector(
      `#review-${reviewId} .review-text`
    );
    const ratingSection = document.querySelector(
      `#review-${reviewId} .rating-section`
    );

    if (editForm) {
      editForm.style.display = "block";
      if (reviewContent) reviewContent.style.display = "none";
      if (ratingSection) ratingSection.style.display = "none";
    }
  };

  // Function to hide edit form
  window.hideEditForm = function (reviewId) {
    const editForm = document.getElementById(`edit-form-${reviewId}`);
    const reviewContent = document.querySelector(
      `#review-${reviewId} .review-text`
    );
    const ratingSection = document.querySelector(
      `#review-${reviewId} .rating-section`
    );

    if (editForm) {
      editForm.style.display = "none";
      if (reviewContent) reviewContent.style.display = "block";
      if (ratingSection) ratingSection.style.display = "flex";
    }
  };

  // Handle edit and delete review button clicks
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("edit-review-btn")) {
      const reviewId = e.target.getAttribute("data-review-id");
      showEditForm(reviewId);
    } else if (e.target.classList.contains("delete-review-btn")) {
      const reviewId = e.target.getAttribute("data-review-id");
      // Implement delete review functionality here
      alert(`Đã xóa đánh giá ${reviewId}`);
    } else if (e.target.classList.contains("save-review-btn")) {
      const reviewId = e.target.getAttribute("data-review-id");
      const newText = document
        .getElementById(`edit-form-${reviewId}`)
        .querySelector(".edit-review-text").value;
      // Implement save review functionality here
      alert(`Đã lưu đánh giá: ${newText}`);
      hideEditForm(reviewId);
    } else if (e.target.classList.contains("cancel-review-btn")) {
      const reviewId = e.target.getAttribute("data-review-id");
      hideEditForm(reviewId);
    }
  });

  function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
  }

  // Add cancel button handler
  document.querySelectorAll(".btn-cancel").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault(); // Prevent form submission
      document.getElementById("editModal").style.display = "none";
      // Clear form fields
      document.getElementById("editReviewForm").reset();
    });
  });

  function openEditModal(data) {
    const modal = document.getElementById("editModal");

    // Fill the form with existing data
    document.getElementById("edit_review_id").value = data.review_id;
    document.getElementById("edit_store_name").value = data.store_name;
    document.getElementById("edit_address").value = data.address;
    document.getElementById("edit_content").value = data.content;

    // Set the rating
    const ratingInput = document.getElementById(`edit_star${data.rating}`);
    if (ratingInput) {
      ratingInput.checked = true;
    }

    // Show the modal
    modal.style.display = "block";
  }

  // Add event listeners for star rating hover effect
  document.querySelectorAll('.stars input[type="radio"]').forEach((input) => {
    input.addEventListener("change", function () {
      const ratingText = this.parentElement.nextElementSibling;
      const ratings = {
        5: "Rất tốt",
        4: "Tốt",
        3: "Bình thường",
        2: "Tệ",
        1: "Rất tệ",
      };
      ratingText.textContent = ratings[this.value];
    });
  });

  // Initialize page
  updateProfileInfo();
  renderReviews();
});
