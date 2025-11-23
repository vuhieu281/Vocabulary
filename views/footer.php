<?php
// Footer
?>
    </main>

<footer class="app-footer">
    <style>
        .app-footer {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: #fff;
            padding: 50px 20px 25px;
            margin-top: 0; /* footer positioned by flex layout in body */
            box-shadow: 0 -6px 20px rgba(13,110,253,0.1);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 30px;
        }

        .footer-section h3 {
            font-size: 1.05rem;
            margin-bottom: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: #fff;
            padding-left: 4px;
        }

        .footer-section.about p {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }

        .footer-divider {
            border-top: 1px solid rgba(255,255,255,0.15);
            margin: 30px 0;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            color: rgba(255,255,255,0.85);
            font-size: 0.95rem;
            padding: 6px 12px 18px;
        }

        .footer-bottom p {
            margin: 0;
            text-align: center;
            width: 100%;
            font-size: 0.95rem;
            color: rgba(255,255,255,0.95);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .app-footer {
                padding: 35px 15px 20px;
                margin-top: 60px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-links {
                justify-content: center;
            }
        }
    </style>

    <div class="footer-container">
        <!-- About Section -->
        <div class="footer-section about">
            <h3>Vocabulary</h3>
            <p>Nền tảng học từ vựng tiếng Anh hiện đại, cung cấp các công cụ và tài liệu học tập toàn diện cho người dùng ở mọi trình độ.</p>
        </div>

        <!-- Quick Links -->
        <div class="footer-section">
            <h3>Liên Kết Nhanh</h3>
            <ul>
                <li><a href="/Vocabulary/public/index.php?route=home">Trang Chủ</a></li>
                <li><a href="/Vocabulary/public/index.php?route=search">Tìm Kiếm Từ</a></li>
                <li><a href="/Vocabulary/public/index.php?route=flashcard">Flashcard</a></li>
                <li><a href="/Vocabulary/public/index.php?route=quiz">Quiz</a></li>
                <li><a href="/Vocabulary/public/index.php?route=topics">Chủ Đề</a></li>
            </ul>
        </div>

        <!-- Resources -->
        <div class="footer-section">
            <h3>Tài Nguyên</h3>
            <ul>
                <li><a href="#blog">Blog Học Tập</a></li>
                <li><a href="#tips">Mẹo & Thủ Thuật</a></li>
                <li><a href="#guide">Hướng Dẫn Sử Dụng</a></li>
                <li><a href="#videos">Video Hướng Dẫn</a></li>
                <li><a href="#api">API Documentation</a></li>
            </ul>
        </div>

        <!-- Support -->
        <div class="footer-section">
            <h3>Hỗ Trợ & Pháp Lý</h3>
            <ul>
                <li><a href="#help">Trợ Giúp</a></li>
                <li><a href="#contact">Liên Hệ</a></li>
                <li><a href="#terms">Điều Khoản</a></li>
                <li><a href="#privacy">Bảo Mật</a></li>
                <li><a href="#cookies">Cookies</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-divider"></div>

    <div class="footer-bottom">
        <p>&copy; 2025 Vocabulary. Tất cả quyền được bảo lưu.</p>
    </div>
</footer>

</body>
</html>