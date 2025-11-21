<?php
// Test endpoint để debug lỗi quiz submit

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Log thông tin request
$log = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'post_keys' => array_keys($_POST),
    'session_user_id' => $_SESSION['user_id'] ?? 'NOT SET',
];

// Cố gắng parse dữ liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = json_decode($_POST['answers'] ?? '{}', true);
    $quizData = json_decode($_POST['quiz'] ?? '[]', true);
    
    $log['answers_count'] = count($answers);
    $log['quiz_count'] = count($quizData);
    
    if (!empty($quizData)) {
        $log['first_quiz_item'] = $quizData[0] ?? null;
    }
}

echo json_encode($log, JSON_PRETTY_PRINT);
exit;
?>
