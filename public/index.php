<?php
// Router đơn giản theo tham số ?route=
$route = $_GET['route'] ?? 'home';

switch ($route) {

    // ----------------- AUTH (Đăng nhập, Đăng ký, Profile) ------------------
    case 'login':
        require_once "../controllers/AuthController.php";
        (new AuthController())->loginPage();
        break;

    case 'login_action':
        require_once "../controllers/AuthController.php";
        (new AuthController())->login();
        break;

    case 'register':
        require_once "../controllers/AuthController.php";
        (new AuthController())->registerPage();
        break;

    case 'register_action':
        require_once "../controllers/AuthController.php";
        (new AuthController())->register();
        break;

    case 'profile':
        require_once "../controllers/AuthController.php";
        (new AuthController())->profile();
        break;

    case 'change_password_action':
        require_once "../controllers/AuthController.php";
        (new AuthController())->changePassword();
        break;

    case 'logout':
        require_once "../controllers/AuthController.php";
        (new AuthController())->logout();
        break;


    // ----------------- HOME / SEARCH ------------------
    case 'home':
        include_once '../views/header.php';
        include_once '../views/home/home.php';
        include_once '../views/footer.php';
        break;

    case 'search':
        // Reuse the home search UI for the search route
        include_once '../views/header.php';
        include_once '../views/home/home.php';
        include_once '../views/footer.php';
        break;

    // ----------------- TOPICS ------------------
    case 'topics':
        require_once __DIR__ . '/../controllers/TopicController.php';
        (new TopicController())->index();
        break;

    case 'topic_detail':
        require_once __DIR__ . '/../controllers/TopicController.php';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        (new TopicController())->detail($id);
        break;

    default:
        include_once '../views/header.php';
        include_once '../views/home/home.php';
        include_once '../views/footer.php';
        break;
}

