<?php
session_start();
require_once('config/db.php');

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Giới thiệu - TechBook</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <!-- Header -->
    <?php
    define('INCLUDED', true); 
    include 'includes/header.php'; ?>

    <!-- Body -->
    <div class="container animate-fade-in">
      <div class="card gioithieucontent" style="padding:2.5rem 2rem;">
        <!-- Về chúng tôi -->
        <section class="policy-section" id="about">
          <h2 class="policy-title" style="text-align:center; color:var(--primary-color);">VỀ CHÚNG TÔI</h2>
          <div class="policy-content">
            <p>
              <strong>TechBook</strong> là trang web chuyên review các cửa hàng
              bán đồ công nghệ secondhand uy tín tại Việt Nam. Với sứ mệnh giúp
              người dùng dễ dàng tiếp cận những sản phẩm công nghệ đã qua sử
              dụng chất lượng cao, giá thành hợp lý, TechBook cung cấp thông tin
              chi tiết và cập nhật về các cửa hàng đáng tin cậy trên toàn quốc.
            </p>
            <p>
              Tại TechBook, bạn sẽ tìm thấy các bài đánh giá khách quan về nhiều
              cửa hàng bán laptop, điện thoại, máy tính bảng, và phụ kiện công
              nghệ secondhand nổi bật. Mỗi bài review đều dựa trên trải nghiệm
              thực tế, chính sách bảo hành và mức độ uy tín của từng cửa hàng,
              giúp bạn an tâm khi lựa chọn mua sắm.
            </p>
            <p>
              Với thông tin minh bạch, liên tục cập nhật cùng giao diện thân
              thiện, TechBook cam kết mang đến cho người dùng trải nghiệm tìm
              kiếm, tham khảo và mua sắm đồ công nghệ secondhand một cách tiện
              lợi và tin cậy.
            </p>
          </div>
        </section>

        <!-- Chính sách bảo mật -->
        <section class="policy-section" id="privacy">
          <h2 class="policy-title" style="text-align:center; color:var(--primary-color);">CHÍNH SÁCH BẢO MẬT</h2>
          <div class="policy-content">
            <p>
              <strong>TechBook</strong> cam kết bảo vệ thông tin cá nhân của
              người dùng, tuân thủ nghiêm ngặt các quy định pháp luật hiện hành
              về an toàn dữ liệu.
            </p>
            <h3>1. Mục đích thu thập thông tin</h3>
            <p>
              Chúng tôi thu thập các thông tin cơ bản như: họ tên, email, số
              điện thoại… nhằm phục vụ việc liên hệ, gửi thông báo về các bài
              review mới, hoặc phản hồi các câu hỏi, góp ý từ người dùng.
            </p>
            <h3>2. Phạm vi sử dụng thông tin</h3>
            <p>
              Thông tin cá nhân chỉ được sử dụng nội bộ tại TechBook với mục
              đích nâng cao trải nghiệm người dùng và cung cấp thông tin hữu
              ích.
            </p>
            <p>
              <strong
                >Chúng tôi cam kết không chia sẻ, mua bán hay tiết lộ thông tin
                cá nhân của người dùng cho bên thứ ba</strong
              >
              vì mục đích thương mại nếu không có sự đồng ý từ người dùng.
            </p>
            <h3>3. Bảo mật thông tin</h3>
            <p>
              TechBook áp dụng các biện pháp kỹ thuật và tổ chức phù hợp để bảo
              vệ thông tin cá nhân khỏi truy cập trái phép. Dữ liệu người dùng
              được lưu trữ trên hệ thống bảo mật cao và chỉ nhân sự được ủy
              quyền mới có quyền truy cập.
            </p>
          </div>
        </section>
      </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>