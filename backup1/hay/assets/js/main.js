// Lazy loading cho hình ảnh
document.addEventListener("DOMContentLoaded", function () {
  const images = document.querySelectorAll("img[data-src]");
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src;
        img.removeAttribute("data-src");
        observer.unobserve(img);
      }
    });
  });

  images.forEach((img) => imageObserver.observe(img));
});

// Xử lý form validation
function validateForm(form) {
  const inputs = form.querySelectorAll(
    "input[required], select[required], textarea[required]"
  );
  let isValid = true;

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      isValid = false;
      input.classList.add("is-invalid");
    } else {
      input.classList.remove("is-invalid");
    }
  });

  return isValid;
}

// Xử lý hiệu ứng loading
function showLoading() {
  const loader = document.createElement("div");
  loader.className = "loader";
  document.body.appendChild(loader);
}

function hideLoading() {
  const loader = document.querySelector(".loader");
  if (loader) {
    loader.remove();
  }
}

// Xử lý thông báo
function showNotification(message, type = "success") {
  const notification = document.createElement("div");
  notification.className = `notification notification-${type}`;
  notification.textContent = message;
  document.body.appendChild(notification);

  setTimeout(() => {
    notification.remove();
  }, 3000);
}

// Xử lý responsive menu
const menuToggle = document.querySelector(".menu-toggle");
const navMenu = document.querySelector(".nav-menu");

if (menuToggle && navMenu) {
  menuToggle.addEventListener("click", () => {
    navMenu.classList.toggle("active");
    menuToggle.classList.toggle("active");
  });
}

// Xử lý scroll to top
const scrollTopBtn = document.querySelector(".scroll-top");

if (scrollTopBtn) {
  window.addEventListener("scroll", () => {
    if (window.pageYOffset > 100) {
      scrollTopBtn.classList.add("show");
    } else {
      scrollTopBtn.classList.remove("show");
    }
  });

  scrollTopBtn.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
}

// Xử lý search với debounce
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
  searchInput.addEventListener(
    "input",
    debounce((e) => {
      const form = e.target.closest("form");
      if (form) {
        form.submit();
      }
    }, 500)
  );
}
