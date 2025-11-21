<?php
// Debug quiz submission

session_start();

// Simulate a quiz submission
$_POST['answers'] = json_encode([0, 1, 2]);
$_POST['quiz'] = json_encode([
    ['question' => 'Test Q1', 'correct_answer' => 'A', 'word_id' => 1],
    ['question' => 'Test Q2', 'correct_answer' => 'B', 'word_id' => 2],
    ['question' => 'Test Q3', 'correct_answer' => 'C', 'word_id' => 3],
]);

// Simulate user logged in
$_SESSION['user_id'] = 1;

echo "Test data parsed:\n";
$answers = json_decode($_POST['answers'], true);
$quizData = json_decode($_POST['quiz'], true);

echo "Answers: " . print_r($answers, true) . "\n";
echo "Quiz data: " . print_r($quizData, true) . "\n";

// Test the submit logic
require_once __DIR__ . '/../models/Quiz.php';

try {
    $quiz = new Quiz();
    $quizResultId = $quiz->saveQuizResult(1, 2, 3);
    echo "Quiz result ID: " . $quizResultId . "\n";
    
    echo json_encode([
        'success' => true,
        'quiz_result_id' => $quizResultId
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
