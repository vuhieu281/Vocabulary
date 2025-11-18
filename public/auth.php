<?php
$route = $_GET['route'] ?? 'login';

switch ($route) {
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
}