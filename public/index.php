<?php
// Router đơn giản theo tham số ?route=

// Middleware: Nếu là admin, redirect sang admin dashboard (trừ logout, api routes)
if (session_status() === PHP_SESSION_NONE) session_start();

$route = $_GET['route'] ?? 'home';

// Routes cho phép admin access
$adminAllowedRoutes = ['logout'];

// Nếu là admin và route không phải admin route hay logout, redirect về admin dashboard
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' && 
    !in_array($route, $adminAllowedRoutes) && 
    strpos($route, 'admin_') === false) {
    header("Location: index.php?route=admin_dashboard");
    exit;
}

// Routes bình thường
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

    // ----------------- FLASHCARD ------------------
    case 'flashcard':
        require_once __DIR__ . '/../controllers/FlashcardController.php';
        $action = $_GET['action'] ?? 'index';
        $controller = new FlashcardController();
        
        if ($action === 'learn') {
            $controller->learn();
        } else {
            $controller->index();
        }
        break;

    // ----------------- QUIZ ------------------
    case 'quiz':
        require_once __DIR__ . '/../controllers/QuizController.php';
        $action = $_GET['action'] ?? 'index';
        $controller = new QuizController();
        
        switch ($action) {
            case 'start':
                $controller->start();
                break;
            case 'submit':
                $controller->submit();
                break;
            case 'result':
                $controller->result();
                break;
            default:
                $controller->index();
        }
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

    // ========== ADMIN PANEL ==========
    case 'admin_dashboard':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->dashboard();
        break;

    case 'admin_users':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->users();
        break;

    case 'admin_edit_user':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->editUser();
        break;

    case 'admin_words':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->words();
        break;

    case 'admin_add_word':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->addWord();
        break;

    case 'admin_save_word':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->saveWord();
        break;

    case 'admin_edit_word':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->editWord();
        break;

    case 'admin_topics':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->topics();
        break;

    case 'admin_add_topic':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->addTopic();
        break;

    case 'admin_save_topic':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->saveTopic();
        break;

    case 'admin_edit_topic':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->editTopic();
        break;

    case 'admin_update_topic':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->updateTopic();
        break;

    case 'admin_activities':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->activities();
        break;

    case 'admin_user_activities':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->userActivities();
        break;

    case 'admin_delete_topic':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->deleteTopic();
        break;

    case 'admin_delete_word':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->deleteWord();
        break;

    case 'admin_delete_user':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->deleteUser();
    // ----------------- CHATBOT ------------------
    case 'chat':
        require_once __DIR__ . '/../controllers/ChatbotController.php';
        (new ChatbotController())->index();
        break;

    default:
        include_once '../views/header.php';
        include_once '../views/home/home.php';
        include_once '../views/footer.php';
        break;
}

