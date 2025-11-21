<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Word.php';

class AuthController {

    public function loginPage() {
        include __DIR__ . '/../views/auth/login.php';
    }

    public function registerPage() {
        include __DIR__ . '/../views/auth/register.php';
    }

    public function register() {
        $name  = $_POST['name'];
        $email = $_POST['email'];
        $pass  = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $user = new User();

        if ($user->exists($email)) {
            $error = "Email đã tồn tại!";
            include __DIR__ . '/../views/auth/register.php';
            return;
        }

        $user->create($name, $email, $pass);

        header("Location: index.php?route=login");
        exit;
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            $error = "Vui lòng nhập email và mật khẩu!";
            include __DIR__.'/../views/auth/login.php';
            return;
        }
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $user = new User();
        $data = $user->getByEmail($email);

        if (!$data || !password_verify($password, $data['password'])) {
            $error = "Sai email hoặc mật khẩu!";
            include __DIR__.'/../views/auth/login.php';
            return;
        }

        $_SESSION['user_id'] = $data['id'];
        $_SESSION['user_name'] = $data['name'];
        $_SESSION['user_email'] = $data['email'];
        $_SESSION['user_role'] = $data['role'];
        
        // Chuyển hướng dựa trên role
        if ($data['role'] === 'admin') {
            header("Location: index.php?route=admin_dashboard");
        } else {
            header("Location: index.php?route=profile");
        }
        exit;
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php?route=login");
        exit;
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        $userModel = new User();
        $wordModel = new Word();

        $user = $userModel->getById($_SESSION['user_id']);
        // Saved words pagination (server-side)
        $savedLimit = isset($_GET['saved_limit']) ? max(1, (int)$_GET['saved_limit']) : 6;
        $savedPage = isset($_GET['saved_page']) ? max(1, (int)$_GET['saved_page']) : 1;
        $savedOffset = ($savedPage - 1) * $savedLimit;
        $savedTotal = $wordModel->countSavedWords($_SESSION['user_id']);
        $savedWords = $wordModel->getSavedWords($_SESSION['user_id'], $savedLimit, $savedOffset);
        $quizResults = $wordModel->getQuizResults($_SESSION['user_id']);

        include __DIR__ . '/../views/auth/profile.php';
    }

    public function changePassword() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        $old = $_POST['old_password'];
        $new = $_POST['new_password'];

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);

        if (!password_verify($old, $user['password'])) {
            $error = "Mật khẩu cũ không đúng!";
        } else {
            $userModel->updatePassword($_SESSION['user_id'], password_hash($new, PASSWORD_BCRYPT));
            $success = "Đổi mật khẩu thành công!";
        }

        include __DIR__ . '/../views/auth/profile.php';
    }

    public function logoutPreview() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php?route=home");
        exit;
    }
}
