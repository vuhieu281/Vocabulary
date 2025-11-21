<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Admin.php';
require_once __DIR__ . '/models/User.php';

session_start();
$_SESSION['user_id'] = 2;
$_SESSION['user_role'] = 'admin';

$admin = new Admin();
$users = $admin->getAllUsers(15, 0);

$userModel = new User();
foreach ($users as &$user) {
    $user['highest_score'] = $userModel->getHighestScore($user['id']);
    $user['quiz_attempts'] = $userModel->getQuizAttempts($user['id']);
    $user['average_score'] = $userModel->getAverageScore($user['id']);
}

echo "Users fetched: " . count($users) . "\n";
echo json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
