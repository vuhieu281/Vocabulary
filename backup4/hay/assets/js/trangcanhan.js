document.addEventListener("DOMContentLoaded", function () {
  // Optional: Check if user is logged in if this is a client-side check
  // const isLoggedIn = localStorage.getItem("isLoggedIn");
  // if (!isLoggedIn) {
  //   window.location.href = "./dangnhap.html";
  //   return;
  // }

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

  // Function to show the edit form for a specific review
  window.showEditForm = function (reviewId) {
    const reviewItem = document.getElementById(`review-${reviewId}`);
    if (!reviewItem) {
      console.error(`Review item with ID review-${reviewId} not found.`);
      return;
    }

    const editForm = reviewItem.querySelector(".edit-form");
    const reviewContent = reviewItem.querySelector(".review-text");
    const ratingSection = reviewItem.querySelector(".rating-section");

    if (editForm) {
      editForm.style.display = "block";
      if (reviewContent) reviewContent.style.display = "none";
      if (ratingSection) ratingSection.style.display = "none";

      // Initialize stars for the opened form's rating container
      const ratingInput = editForm.querySelector('input[name="rating"]');
      const currentRating = parseInt(ratingInput.value) || 0;
      const stars = editForm.querySelectorAll(".rating-star");
      updateStars(stars, currentRating);
    }
  };

  // Function to hide the edit form for a specific review
  window.hideEditForm = function (reviewId) {
    const reviewItem = document.getElementById(`review-${reviewId}`);
    if (!reviewItem) {
      console.error(`Review item with ID review-${reviewId} not found.`);
      return;
    }

    const editForm = reviewItem.querySelector(".edit-form");
    const reviewContent = reviewItem.querySelector(".review-text");
    const ratingSection = reviewItem.querySelector(".rating-section");

    if (editForm) {
      editForm.style.display = "none";
      if (reviewContent) reviewContent.style.display = "block";
      if (ratingSection) ratingSection.style.display = "flex"; // Assuming it's a flex container
    }
  };

  // Helper function to update star visuals (active/inactive state)
  function updateStars(stars, rating) {
    stars.forEach((star) => {
      const starValue = parseInt(star.getAttribute("data-value"));
      // Apply 'active' class if starValue is less than or equal to current rating
      star.classList.toggle("active", starValue <= rating);
    });
  }

  // Event delegation for clicks on various buttons within the document
  document.addEventListener("click", function (e) {
    // Handle Edit button click
    if (
      e.target.classList.contains("edit-btn") ||
      e.target.closest(".edit-btn")
    ) {
      e.preventDefault(); // Prevent default button behavior (e.g., form submission if it's type="submit")
      const button = e.target.closest(".edit-btn");
      const reviewId = button.getAttribute("data-review-id");
      if (reviewId) {
        showEditForm(reviewId);
      }
    }
    // Handle Save button click
    else if (
      e.target.classList.contains("btn-save") ||
      e.target.closest(".btn-save")
    ) {
      e.preventDefault(); // Prevent default form submission
      const button = e.target.closest(".btn-save");
      const editForm = button.closest(".edit-form");
      const reviewIdInput = editForm
        ? editForm.querySelector('input[name="review_id"]')
        : null;

      if (!reviewIdInput) {
        console.error("review_id input not found in the form.");
        return;
      }
      const reviewId = reviewIdInput.value;
      const form = document.getElementById(`editReviewForm-${reviewId}`);

      if (form) {
        // Check if rating is selected (value must be greater than 0)
        const selectedRatingInput = form.querySelector('input[name="rating"]');
        const selectedRating = parseInt(selectedRatingInput.value) || 0;
        if (selectedRating === 0) {
          alert("Vui lòng chọn số sao đánh giá");
          return;
        }

        const formData = new FormData(form);
        fetch("update_review.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => {
            // update_review.php performs a redirect, so we don't parse JSON directly.
            // Check if the response status is successful (e.g., 200-299).
            if (response.ok) {
              alert("Đã lưu đánh giá!");
              // The server-side redirect in update_review.php will handle the page reload.
              // If for some reason the redirect doesn't happen, or fetch doesn't follow, we can force a reload.
              window.location.reload();
            } else {
              // Handle cases where the server returns an error status (e.g., 400, 500).
              // It's good practice to log or show error details if available from the server.
              console.error(
                "Server error:",
                response.status,
                response.statusText
              );
              return response.text().then((text) => {
                throw new Error(
                  text ||
                    "Lỗi không xác định từ máy chủ (status: " +
                      response.status +
                      ")."
                );
              });
            }
          })
          .catch((error) => {
            console.error("Fetch error:", error);
            alert("Có lỗi xảy ra khi cập nhật: " + error.message);
          });
      }
    }
    // Handle Cancel button click
    else if (
      e.target.classList.contains("btn-cancel") ||
      e.target.closest(".btn-cancel")
    ) {
      e.preventDefault(); // Prevent default button behavior
      const button = e.target.closest(".btn-cancel");
      const editForm = button.closest(".edit-form");
      const reviewIdInput = editForm
        ? editForm.querySelector('input[name="review_id"]')
        : null;

      if (!reviewIdInput) {
        console.error("review_id input not found in the form.");
        return;
      }
      const reviewId = reviewIdInput.value;
      hideEditForm(reviewId);
    }
    // Handle Delete button click (if applicable)
    else if (e.target.classList.contains("delete-review-btn")) {
      e.preventDefault(); // Prevent default button behavior
      const reviewId = e.target.getAttribute("data-review-id");
      if (confirm("Bạn có chắc chắn muốn xóa đánh giá này không?")) {
        // Implement delete review functionality here, e.g., via fetch to a delete_review.php
        alert(`Đã xóa đánh giá ${reviewId}`);
        // You might want to reload page or remove the item from DOM after successful deletion
        // window.location.reload();
      }
    }
  });

  // Initialize star rating functionality for all review items
  // This ensures the star rating works for dynamically loaded/hidden forms
  document.querySelectorAll(".review-item").forEach((reviewItem) => {
    const reviewId = reviewItem.id.replace("review-", ""); // Get review ID from the item itself
    const ratingContainer = reviewItem.querySelector(".rating-container");

    if (ratingContainer) {
      const stars = ratingContainer.querySelectorAll(".rating-star");
      const ratingInput = document.getElementById(`rating-input-${reviewId}`);

      // Attach event listeners to stars for click, mouseover, mouseout
      stars.forEach((star) => {
        star.addEventListener("click", function () {
          const clickedValue = parseInt(this.getAttribute("data-value"));
          ratingInput.value = clickedValue;
          updateStars(stars, clickedValue);
        });

        star.addEventListener("mouseover", function () {
          const hoverValue = parseInt(this.getAttribute("data-value"));
          stars.forEach((s) => {
            const starValue = parseInt(s.getAttribute("data-value"));
            if (starValue <= hoverValue) {
              s.style.color = "#ffd700"; // Highlight hovered and preceding stars
            } else {
              s.style.color = "#ddd"; // Dim other stars
            }
          });
        });

        star.addEventListener("mouseout", function () {
          const currentValue = parseInt(ratingInput.value) || 0;
          updateStars(stars, currentValue); // Revert to current rating on mouse out
        });
      });
    }
  });

  // Initialize page components that use mock data (if applicable).
  // Since PHP is rendering reviews, these might not be necessary.
  // updateProfileInfo();
  // renderReviews();
});
