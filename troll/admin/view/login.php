<?php
    session_start();
    if (isset($_POST['login'])) {
        $username= $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $user=checkuser($username, $password);
        if(isset($user)&&(is_array($user))&&(count($user)>0)){
           extract($user);
        if ($roll==1) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $tb = "Tài khoản không có quyền truy cập";
        }
        
        }else{
            $tb = "Tài hoản này không tồn tại hoặc mật khẩu không đúng";
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập đơn giản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 32px 28px;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            width: 320px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 24px;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
            margin-top: 16px;
            font-size: 15px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px 8px;
            margin-bottom: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
        }
        .login-container button {
            width: 100%;
            padding: 10px 0;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <form class="login-container" autocomplete="off">
        <h2>Đăng nhập</h2>
        <label for="username">Tên đăng nhập</label>
        <input type="text" id="username" placeholder="Nhập tên đăng nhập" required>
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" placeholder="Nhập mật khẩu" required>
        <?php if (isset($tb)&&($tb!="")){
            echo "<h3 style='color:red' >".$tb."</h3>";
        }
        ?>
        <button type="submit" id="login" >Đăng nhập</button>
    </form>
</body>
</html>