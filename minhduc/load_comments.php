<?php
<?php
require_once('config/db.php');

$review_id = isset($_GET['review_id']) ? (int)$_GET['review_id'] : 0;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

$query = "SELECT c.*, u.username 
          FROM comments c 
          JOIN users u ON c.user_id = u.user_id 
          WHERE c.review_id = ? 
          ORDER BY c.created_at DESC 
          LIMIT 3 OFFSET ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $review_id, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Format dates
foreach ($comments as &$comment) {
    $comment['created_at'] = date('d/m/Y, H:i', strtotime($comment['created_at']));
}

header('Content-Type: application/json');
echo json_encode(['comments' => $comments]);