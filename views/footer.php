<?php
// Footer
?>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- About Section -->
                <div class="footer-section">
                    <h4>Về Vocabulary</h4>
                    <p>Nền tảng học từ vựng tiếng Anh hiện đại, giúp bạn khám phá và luyện tập từng ngày với giao diện thân thiện.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook" title="Facebook">f</a>
                        <a href="#" aria-label="Twitter" title="Twitter">𝕏</a>
                        <a href="#" aria-label="LinkedIn" title="LinkedIn">in</a>
                    </div>
                </div>

                <!-- Features Section -->
                <div class="footer-section">
                    <h4>Chức năng</h4>
                    <ul>
                        <li><a href="/Vocabulary/public/search.php">Tìm kiếm</a></li>
                        <li><a href="/Vocabulary/public/flashcards.php">Flashcards</a></li>
                        <li><a href="/Vocabulary/public/quiz.php">Quiz</a></li>
                        <li><a href="/Vocabulary/public/topics.php">Topics</a></li>
                    </ul>
                </div>

                <!-- Resources Section -->
                <div class="footer-section">
                    <h4>Tài nguyên</h4>
                    <ul>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Hướng dẫn</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>

                <!-- Legal Section -->
                <div class="footer-section">
                    <h4>Pháp lý</h4>
                    <ul>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Chính sách riêng tư</a></li>
                        <li><a href="#">Cookie</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>&copy; 2025 Vocabulary. Tất cả quyền được bảo lưu.</p>
                <p>Được xây dựng với <span class="heart">♥</span> cho những người yêu thích tiếng Anh.</p>
            </div>
        </div>
    </footer>

    <style>
        /* Footer Styles */
        .site-footer {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: #fff;
            padding: 48px 24px 24px 24px;
            margin-top: 80px;
            border-radius: 20px 20px 0 0;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 32px;
            margin-bottom: 32px;
        }

        .footer-section h4 {
            font-size: 1.1em;
            font-weight: 700;
            margin: 0 0 16px 0;
            color: #fff;
            letter-spacing: 0.3px;
        }

        .footer-section p {
            font-size: 0.95em;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.85);
            margin: 0 0 16px 0;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.95em;
            transition: color 200ms ease, transform 120ms ease;
            display: inline-block;
        }

        .footer-section a:hover {
            color: #fff;
            transform: translateX(4px);
        }

        .social-links {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .social-links a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: background 180ms ease, border-color 180ms ease, transform 140ms ease;
        }

        .social-links a:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 18px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9em;
        }

        .footer-bottom p {
            margin: 6px 0;
        }

        .heart {
            color: #ff6b6b;
            font-size: 1.1em;
        }

        /* Responsive Footer */
        @media (max-width: 768px) {
            .site-footer {
                padding: 32px 18px 18px 18px;
                margin-top: 60px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .footer-section h4 {
                font-size: 1.02em;
            }

            .footer-section a {
                transform: none;
            }

            .footer-section a:hover {
                transform: none;
            }
        }
    </style>
</body>
</html>