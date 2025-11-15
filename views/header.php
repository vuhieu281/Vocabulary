<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabulary - Học từ vựng tiếng Anh</title>
    <link rel="stylesheet" href="/Vocabulary/public/css/home.css">
    <style>
        .navbar {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center; /* center main nav */
            gap: 12px;
            background: #0d6efd; /* primary blue */
            padding: 14px 22px; /* larger header */
            border-radius: 0 0 14px 14px;
            box-shadow: 0 6px 20px rgba(13,110,253,0.08);
            min-height: 64px;
        }
        .navbar .nav-center { position: absolute; left: 50%; transform: translateX(-50%); display:flex; gap:12px; align-items:center; }
        .navbar .nav-right { position: absolute; right: 18px; display:flex; gap:12px; align-items:center; }
        .navbar .logo { position: absolute; left: 18px; display:flex; align-items:center; gap:10px; }
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
        .navbar .btn-login {
            background: rgba(255,255,255,0.12);
            color: #fff;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.06);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.98rem;
            cursor: pointer;
        }
        .navbar .btn-register {
            background: #ffffff;
            color: #0d6efd;
            font-weight: 700;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.98rem;
            cursor: pointer;
        }
        .navbar .admin-badge { background: rgba(255,255,255,0.16); color: #fff; padding: 6px 10px; border-radius: 8px; font-weight:700; }
        .navbar .logo a { color: #fff; font-weight: 800; font-size: 1.15rem; letter-spacing: 0.6px; text-decoration:none; }
        .navbar .logo .accent { color: rgba(255,255,255,0.9); background: rgba(255,255,255,0.12); padding: 4px 8px; border-radius: 8px; font-weight:800; }
        .navbar .icon { width: 18px; height: 18px; display:inline-block; vertical-align: middle; }
        /* Responsive collapse for small screens */
        @media (max-width: 900px) {
            .navbar { padding: 12px 14px; }
            .navbar .nav-center { display: none; }
            .navbar .logo { left: 12px; }git push origin main

        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <a href="/Vocabulary/public/index.php"><span class="accent">V</span>ocabulary</a>
            </div>
            <div class="nav-center">
                <a href="/Vocabulary/public/index.php">Home</a>
                <a href="/Vocabulary/public/topics.php">Topics</a>
                <a href="/Vocabulary/public/search.php">Search</a>
                <a href="/Vocabulary/public/saved.php">Saved</a>
                <a href="/Vocabulary/public/history.php">History</a>
                <a href="/Vocabulary/public/flashcards.php">Flashcard</a>
                <a href="/Vocabulary/public/quiz.php">Quiz</a>
            </div>
            <div class="nav-right">
                <a href="/Vocabulary/public/profile.php">Profile</a>
                <button class="btn-login">Log in</button>
                <button class="btn-register">Sign up</button>
                <a href="/Vocabulary/public/admin/" class="admin-badge">Admin</a>
            </div>
        </div>
    </header>
    <main>