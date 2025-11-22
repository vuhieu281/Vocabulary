<?php
if (session_status() === PHP_SESSION_NONE) session_start();

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabulary - Học từ vựng tiếng Anh</title>
    <link rel="stylesheet" href="../public/css/home.css">
    <link rel="stylesheet" href="../public/css/flashcard.css">
    <link rel="stylesheet" href="../public/css/quiz.css">

    <style>
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body { 
            margin: 0; 
            padding: 0;
            padding-top: 48px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main { flex: 1 0 auto; }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: #0d6efd;
            padding: 8px 22px;
            border-radius: 0 0 14px 14px;
            box-shadow: 0 6px 20px rgba(13,110,253,0.08);
            min-height: 48px;
        }
        .nav-center { 
            position: absolute; left: 50%; transform: translateX(-50%);
            display:flex; gap:12px; align-items:center;
        }
        .nav-right { 
            position: absolute; right: 18px; 
            display:flex; 
            gap:8px; 
            align-items:center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .logo { 
            position:absolute; 
            left:18px; 
            display:flex; 
            align-items:center; 
            gap:10px;
            z-index: 1001;
        }
        .logo a { color: #fff; text-decoration: none; font-weight: 700; font-size: 1.1rem; }
        .logo .accent { font-weight: 900; font-size: 1.3em; }

        .navbar a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.02rem;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background 0.15s, transform 120ms ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar a:hover { background: rgba(255,255,255,0.08); transform: translateY(-1px); }

        .btn-login {
            background: rgba(255,255,255,0.12);
            color: #fff;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.06);
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-register {
            background: #ffffff;
            color: #0d6efd;
            font-weight: 700;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .nav-right a { display:inline-flex; align-items:center; }
        .navbar { z-index: 50; }

        /* Ensure button text colors override the generic .navbar a rule */
        .navbar a.btn-register { color: #0d6efd !important; }
        .navbar a.btn-login { color: #ffffff !important; }

        .admin-badge { 
            background: rgba(255,255,255,0.16); 
            color: #fff; 
            padding: 6px 10px; 
            border-radius: 8px; 
            font-weight:700; 
        }

        @media (max-width: 900px) {
            .nav-center { display:none; }
        }
    </style>
</head>

<body>
<header>
    <div class="navbar">

        <!-- LOGO -->
        <div class="logo">
            <a href="/Vocabulary/public/index.php?route=home">
                <span class="accent">V</span>ocabulary
            </a>
        </div>

        <!-- NAV TRUNG TÂM -->
        <div class="nav-center">
            <a href="/Vocabulary/public/index.php?route=home">Home</a>
            <a href="/Vocabulary/public/index.php?route=topics">Topics</a>
            <a href="/Vocabulary/public/index.php?route=flashcard">Flashcard</a>
            <a href="/Vocabulary/public/index.php?route=quiz">Quiz</a>
            <a href="/Vocabulary/public/index.php?route=chat">Chatbot</a>

        </div>

        <!-- NAV PHẢI -->
        <div class="nav-right">

            <?php if (!isset($_SESSION['user_id'])): ?>
                <!-- Chưa đăng nhập -->
                <a href="/Vocabulary/public/index.php?route=login" class="btn-login">Log in</a>
                <a href="/Vocabulary/public/index.php?route=register" class="btn-register">Sign up</a>

            <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <!-- Admin user -->
                <span style="color: #fff; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-user-shield" style="font-size: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                    <span>Administrator</span>
                </span>
                <a href="/Vocabulary/public/index.php?route=profile" class="btn-register" style="background:#fff;color:#0066cc;">👤 Profile</a>
                <a href="/Vocabulary/public/index.php?route=logout" class="btn-register" style="background:#fff;color:#d10000;">Logout</a>

            <?php else: ?>
                <!-- Normal user -->
                <a href="/Vocabulary/public/index.php?route=profile">👤 Profile</a>
                <a href="/Vocabulary/public/index.php?route=logout" class="btn-register" style="background:#fff;color:#d10000;">Logout</a>

            <?php endif; ?>
        </div>

    </div>
</header>

<main>
