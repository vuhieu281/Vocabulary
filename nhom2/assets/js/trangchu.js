document.addEventListener("DOMContentLoaded", function () {
  // Xử lý nút Trước và Sau cho tất cả slider
  document.querySelectorAll(".slider-wrapper").forEach((wrapper) => {
    const slider = wrapper.querySelector(".product-slider");
    const prevBtn = wrapper.querySelector(".slider-prev");
    const nextBtn = wrapper.querySelector(".slider-next");

    prevBtn.addEventListener("click", () => {
      slider.scrollBy({ left: -250, behavior: "smooth" });
    });

    nextBtn.addEventListener("click", () => {
      slider.scrollBy({ left: 250, behavior: "smooth" });
    });
  });
});
