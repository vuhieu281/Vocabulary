document.addEventListener("DOMContentLoaded", function () {
  const ratingContainer = document.querySelector(".rating");
  if (!ratingContainer) return;

  const starsOuter = ratingContainer.querySelector(".stars");
  const ratingText = ratingContainer.querySelector(".rating-text");

  const ratingTexts = {
    1: "Rất tệ",
    2: "Tệ",
    3: "Bình thường",
    4: "Tốt",
    5: "Rất tốt",
  };

  starsOuter.addEventListener("mouseover", function (e) {
    const label = e.target.closest("label");
    if (!label) return;
    const rating = label.htmlFor.replace("star", "");
    starsOuter.querySelectorAll("label").forEach((l, i) => {
      l.style.color = i < rating ? "#ffd700" : "#ddd";
    });
    ratingText.textContent = ratingTexts[rating] || "Chọn đánh giá của bạn";
  });

  starsOuter.addEventListener("mouseout", function () {
    const checked = starsOuter.querySelector("input:checked");
    starsOuter
      .querySelectorAll("label")
      .forEach((l) => (l.style.color = "#ddd"));
    if (checked) {
      const rating = checked.value;
      for (let i = 0; i < rating; i++) {
        starsOuter.querySelector(`label[for=star${rating}]`).style.color =
          "#ffd700";
      }
      ratingText.textContent = ratingTexts[rating];
    } else {
      ratingText.textContent = "Chọn đánh giá của bạn";
    }
  });

  starsOuter.addEventListener("click", function (e) {
    const label = e.target.closest("label");
    if (!label) return;
    const rating = label.htmlFor.replace("star", "");
    const input = document.querySelector(
      `input[name="rating"][value="${rating}"]`
    );
    input.checked = true;
    ratingText.textContent = ratingTexts[rating];
  });

  const fileButton = document.querySelector(".btn-file");
  const fileInput = document.querySelector('input[type="file"]');
  const fileInfo = document.querySelector(".file-info");

  fileButton.addEventListener("click", function () {
    fileInput.click();
  });

  fileInput.addEventListener("change", function () {
    fileInfo.textContent =
      this.files.length > 0
        ? this.files.length === 1
          ? this.files[0].name
          : `Đã chọn ${this.files.length} tệp`
        : "Không tệp nào được chọn";
  });

  // Xử lý nút xem thêm bình luận
  document.querySelectorAll(".btn-load-more").forEach((button) => {
    button.addEventListener("click", function () {
      const reviewId = this.dataset.reviewId;
      const offset = parseInt(this.dataset.offset);
      const total = parseInt(this.dataset.total);

      fetch(`load_comments.php?review_id=${reviewId}&offset=${offset}`)
        .then((response) => response.json())
        .then((data) => {
          if (data.comments) {
            const container = document.querySelector(`#comments-${reviewId}`);
            data.comments.forEach((comment) => {
              container.insertAdjacentHTML(
                "beforeend",
                `
                                <div class="comment">
                                    <div class="comment-header">
                                        <span class="comment-author">${comment.username}</span>
                                        <span class="comment-date">${comment.created_at}</span>
                                    </div>
                                    <p class="comment-text">${comment.content}</p>
                                </div>
                            `
              );
            });

            // Cập nhật offset cho lần load tiếp theo
            this.dataset.offset = offset + 3;

            // Ẩn nút nếu đã load hết
            if (offset + 3 >= total) {
              this.style.display = "none";
            }
          }
        });
    });
  });
});
