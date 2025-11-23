<?php
// Footer
?>
    </main>

<footer class="app-footer">
    <style>
        .app-footer {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: #fff;
            padding: 56px 0 28px;
            margin-top: 0;
            box-shadow: 0 -6px 20px rgba(13,110,253,0.06);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }

        /* central wrapper keeps left/right padding consistent with main site */
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .footer-container {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr 1fr;
            gap: 32px;
            align-items: start;
            margin-bottom: 22px;
        }

        .footer-section h3 {
            font-size: 1.1rem;
            margin-bottom: 20px;
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
            margin-bottom: 12px;
            background: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
            color: rgba(255,255,255,0.85) !important;
            font-weight: 400 !important;
        }

        .footer-section ul li a {
            color: rgba(255,255,255,0.88);
            text-decoration: none;
            font-size: 0.95rem;
            transition: transform 0.18s ease, color 0.18s ease;
            display: inline-block;
        }

        .footer-section ul li a:hover { transform: translateX(6px); color: #fff; }

        .footer-section.about p {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }

        .footer-divider { border-top: 1px solid rgba(255,255,255,0.12); margin: 18px 0; }

        .footer-bottom {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.75);
            font-size: 0.9rem;
            padding: 6px 0 6px;
            flex-wrap: wrap;
            text-align: center;
        }

        .footer-bottom p {
            margin: 0;
        }

        .footer-links { display: flex; gap: 20px; flex-wrap: wrap; align-items: center; }
        .footer-links a { color: rgba(255,255,255,0.75); text-decoration: none; transition: color 0.18s ease; }
        .footer-links a:hover { color: #fff; }

        /* Small helper for CTA-like link in footer */
        .btn-small { padding: 8px 12px; border-radius: 8px; font-weight: 700; }

        @media (max-width: 980px) {
            .footer-container { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .footer-container { grid-template-columns: 1fr; gap: 20px; }
            .footer-bottom { justify-content: center; text-align: center; }
            .footer-inner { padding: 0 16px; }
        }
    </style>

    <div class="footer-inner">
    <div class="footer-container">
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
        <p class="copyright">&copy; 2025 Vocabulary. Tất cả quyền được bảo lưu.</p>
    </div>
    </div>
</footer>

</body>
</html>