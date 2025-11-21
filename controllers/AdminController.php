<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/User.php';

class AdminController {

    private $admin;

    public function __construct() {
        $this->admin = new Admin();
        $this->checkAdminAccess();
    }

    // Kiểm tra quyền admin
    private function checkAdminAccess() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?route=login");
            exit;
        }

        $user = new User();
        $userData = $user->getById($_SESSION['user_id']);

        if (!$userData || $userData['role'] !== 'admin') {
            http_response_code(403);
            die('Truy cập bị từ chối. Bạn không có quyền truy cập trang này.');
        }
    }

    // ========== DASHBOARD ==========

    public function dashboard() {
        $stats = $this->admin->getDashboardStats();
        $activities = $this->admin->getRecentActivities(10);
        $activityStats = $this->admin->getActivityStats();

        include __DIR__ . '/../views/admin/dashboard.php';
    }

    // ========== USER MANAGEMENT ==========

    public function users() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $users = $this->admin->getAllUsers($limit, $offset);
        $totalUsers = $this->admin->countUsers();
        $totalPages = ceil($totalUsers / $limit);

        // Tách admin và non-admin users
        $adminUsers = [];
        $regularUsers = [];
        
        $userModel = new User();
        foreach ($users as $user) {
            $user['highest_score'] = $userModel->getHighestScore($user['id']);
            $user['quiz_attempts'] = $userModel->getQuizAttempts($user['id']);
            $user['average_score'] = $userModel->getAverageScore($user['id']);
            
            if ($user['role'] === 'admin') {
                $adminUsers[] = $user;
            } else {
                $regularUsers[] = $user;
            }
        }

        $users = array_merge($adminUsers, $regularUsers);

        include __DIR__ . '/../views/admin/users.php';
    }

    public function editUser() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=admin_users");
            exit;
        }

        $user = $this->admin->getUserById($id);
        if (!$user) {
            header("Location: index.php?route=admin_users");
            exit;
        }

        include __DIR__ . '/../views/admin/edit-user.php';
    }

    // ========== WORD MANAGEMENT ==========

    public function words() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $words = $this->admin->getAllWords($limit, $offset);
        $totalWords = $this->admin->countWords();
        $totalPages = ceil($totalWords / $limit);

        include __DIR__ . '/../views/admin/words.php';
    }

    public function addWord() {
        include __DIR__ . '/../views/admin/add-word.php';
    }

    public function saveWord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=admin_words");
            exit;
        }

        $word = trim($_POST['word'] ?? '');
        $part_of_speech = trim($_POST['part_of_speech'] ?? '');
        $ipa = trim($_POST['ipa'] ?? '');
        $audio_link = trim($_POST['audio_link'] ?? '');
        $senses = trim($_POST['senses'] ?? '');
        $level = trim($_POST['level'] ?? '');
        $oxford_url = trim($_POST['oxford_url'] ?? '');

        if (empty($word)) {
            header("Location: index.php?route=admin_add_word&error=Từ vựng không được để trống");
            exit;
        }

        try {
            $result = $this->admin->createWord($word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url);
            if ($result) {
                header("Location: index.php?route=admin_words&success=Thêm từ vựng thành công");
            } else {
                header("Location: index.php?route=admin_add_word&error=Thêm từ vựng thất bại");
            }
        } catch (Exception $e) {
            header("Location: index.php?route=admin_add_word&error=" . urlencode($e->getMessage()));
        }
        exit;
    }

    public function editWord() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=admin_words");
            exit;
        }

        $word = $this->admin->getWordById($id);
        if (!$word) {
            header("Location: index.php?route=admin_words");
            exit;
        }

        include __DIR__ . '/../views/admin/edit-word.php';
    }

    public function updateWord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=admin_words");
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=admin_words");
            exit;
        }

        $word = trim($_POST['word'] ?? '');
        $part_of_speech = trim($_POST['part_of_speech'] ?? '');
        $ipa = trim($_POST['ipa'] ?? '');
        $audio_link = trim($_POST['audio_link'] ?? '');
        $senses = trim($_POST['senses'] ?? '');
        $level = trim($_POST['level'] ?? '');
        $oxford_url = trim($_POST['oxford_url'] ?? '');

        if (empty($word)) {
            header("Location: index.php?route=admin_edit_word&id=$id&error=Từ vựng không được để trống");
            exit;
        }

        try {
            $result = $this->admin->updateWord($id, $word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url);
            if ($result) {
                header("Location: index.php?route=admin_words&success=Cập nhật từ vựng thành công");
            } else {
                header("Location: index.php?route=admin_edit_word&id=$id&error=Cập nhật thất bại");
            }
        } catch (Exception $e) {
            header("Location: index.php?route=admin_edit_word&id=$id&error=" . urlencode($e->getMessage()));
        }
        exit;
    }

    // ========== TOPIC MANAGEMENT ==========

    public function topics() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $topics = $this->admin->getAllTopics($limit, $offset);
        $totalTopics = $this->admin->countTopics();
        $totalPages = ceil($totalTopics / $limit);

        include __DIR__ . '/../views/admin/topics.php';
    }

    public function addTopic() {
        include __DIR__ . '/../views/admin/add-topic.php';
    }

    public function saveTopic() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=admin_topics");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image = null;

        if (empty($name)) {
            header("Location: index.php?route=admin_add_topic&error=Tên chủ đề không được để trống");
            exit;
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                header("Location: index.php?route=admin_add_topic&error=Định dạng ảnh không hỗ trợ");
                exit;
            }

            if ($_FILES['image']['size'] > 2 * 1024 * 1024) { // 2MB
                header("Location: index.php?route=admin_add_topic&error=Kích thước ảnh quá lớn");
                exit;
            }

            $uploads_dir = __DIR__ . '/../uploads/topics';
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0755, true);
            }

            $new_filename = uniqid() . '.' . $ext;
            $upload_path = $uploads_dir . '/' . $new_filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = 'uploads/topics/' . $new_filename;
            }
        }

        try {
            $topicId = $this->admin->createTopic($name, $description, $image);
            
            error_log("CreateTopic result: " . var_export($topicId, true));
            
            if ($topicId) {
                // Xử lý các từ vựng
                $words = $_POST['words'] ?? [];
                $words = array_filter($words, 'strlen'); // Loại bỏ các trường trống
                $words = array_slice($words, 0, 10); // Giới hạn 10 từ
                
                if (!empty($words)) {
                    $this->admin->assignWordsToTopic($topicId, $words);
                }
                
                error_log("Topic created successfully: ID=$topicId, Name=$name");
                header("Location: index.php?route=admin_topics&success=Thêm chủ đề thành công");
            } else {
                error_log("CreateTopic failed for: $name");
                header("Location: index.php?route=admin_add_topic&error=Thêm chủ đề thất bại");
            }
        } catch (Exception $e) {
            error_log("CreateTopic exception: " . $e->getMessage());
            header("Location: index.php?route=admin_add_topic&error=" . urlencode($e->getMessage()));
        }
        exit;
    }

    public function editTopic() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=admin_topics");
            exit;
        }

        $topic = $this->admin->getTopicById($id);
        if (!$topic) {
            header("Location: index.php?route=admin_topics");
            exit;
        }

        $topicWords = $this->admin->getTopicWords($id);

        include __DIR__ . '/../views/admin/edit-topic.php';
    }

    public function updateTopic() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=admin_topics");
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=admin_topics");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image = null;

        if (empty($name)) {
            header("Location: index.php?route=admin_edit_topic&id=$id&error=Tên chủ đề không được để trống");
            exit;
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                header("Location: index.php?route=admin_edit_topic&id=$id&error=Định dạng ảnh không hỗ trợ");
                exit;
            }

            if ($_FILES['image']['size'] > 2 * 1024 * 1024) { // 2MB
                header("Location: index.php?route=admin_edit_topic&id=$id&error=Kích thước ảnh quá lớn");
                exit;
            }

            $uploads_dir = __DIR__ . '/../uploads/topics';
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0755, true);
            }

            $new_filename = uniqid() . '.' . $ext;
            $upload_path = $uploads_dir . '/' . $new_filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = 'uploads/topics/' . $new_filename;
            }
        }

        try {
            $result = $this->admin->updateTopic($id, $name, $description, $image);
            if ($result) {
                // Danh sách từ cũ cần giữ lại (được gửi từ form qua hidden inputs)
                $existingWords = $_POST['existing_words'] ?? [];
                $existingWords = array_filter($existingWords, 'is_numeric');
                
                // Danh sách từ mới (từ input fields)
                $newWords = $_POST['words'] ?? [];
                $newWords = array_filter($newWords, 'strlen');
                $newWords = array_slice($newWords, 0, 10);
                
                // Nếu có từ mới, xoá tất cả từ cũ và thêm từ mới
                if (!empty($newWords)) {
                    $this->admin->removeAllWordsFromTopic($id);
                    $this->admin->assignWordsToTopic($id, $newWords);
                } else if (!empty($existingWords)) {
                    // Nếu không có từ mới nhưng còn từ cũ, chỉ xoá những từ không nằm trong danh sách existing_words
                    $allCurrentWords = $this->admin->getTopicWords($id);
                    foreach ($allCurrentWords as $word) {
                        if (!in_array($word['id'], $existingWords)) {
                            $this->admin->removeWordFromTopic($word['id'], $id);
                        }
                    }
                } else {
                    // Nếu không có từ mới và không có từ cũ, xoá tất cả
                    $this->admin->removeAllWordsFromTopic($id);
                }
                
                header("Location: index.php?route=admin_topics&success=Cập nhật chủ đề thành công");
            } else {
                header("Location: index.php?route=admin_edit_topic&id=$id&error=Cập nhật chủ đề thất bại");
            }
        } catch (Exception $e) {
            header("Location: index.php?route=admin_edit_topic&id=$id&error=" . urlencode($e->getMessage()));
        }
        exit;
    }

    // ========== ACTIVITY LOG ==========

    public function activities() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $activities = $this->admin->getRecentActivities($limit);
        $activityStats = $this->admin->getActivityStats();

        include __DIR__ . '/../views/admin/activities.php';
    }

    public function userActivities() {
        $user_id = $_GET['user_id'] ?? null;
        if (!$user_id) {
            header("Location: index.php?route=admin_dashboard");
            exit;
        }

        $user = new User();
        $userData = $user->getById($user_id);
        if (!$userData) {
            header("Location: index.php?route=admin_users");
            exit;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 30;
        $offset = ($page - 1) * $limit;

        $activities = $this->admin->getUserActivityHistory($user_id, $limit, $offset);
        $totalActivities = $this->admin->countUserActivities($user_id);
        $totalPages = ceil($totalActivities / $limit);

        include __DIR__ . '/../views/admin/user-activities.php';
    }

    // ========== DELETE METHODS ==========

    public function deleteTopic() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=admin_topics");
            exit;
        }

        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=admin_topics&error=ID không hợp lệ");
            exit;
        }

        try {
            $result = $this->admin->deleteTopic($id);
            if ($result) {
                header("Location: index.php?route=admin_topics&success=Xóa chủ đề thành công");
            } else {
                header("Location: index.php?route=admin_topics&error=Xóa thất bại");
            }
        } catch (Exception $e) {
            header("Location: index.php?route=admin_topics&error=" . urlencode($e->getMessage()));
        }
        exit;
    }

    public function deleteWord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=admin_words");
            exit;
        }

        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=admin_words&error=ID không hợp lệ");
            exit;
        }

        try {
            $result = $this->admin->deleteWord($id);
            if ($result) {
                header("Location: index.php?route=admin_words&success=Xóa từ thành công");
            } else {
                header("Location: index.php?route=admin_words&error=Xóa thất bại");
            }
        } catch (Exception $e) {
            header("Location: index.php?route=admin_words&error=" . urlencode($e->getMessage()));
        }
        exit;
    }

    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=admin_users");
            exit;
        }

        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=admin_users&error=ID không hợp lệ");
            exit;
        }

        // Không cho phép xóa chính mình
        if ($id === $_SESSION['user_id']) {
            header("Location: index.php?route=admin_users&error=Không thể xóa chính mình");
            exit;
        }

        try {
            $result = $this->admin->deleteUser($id);
            if ($result) {
                header("Location: index.php?route=admin_users&success=Xóa người dùng thành công");
            } else {
                header("Location: index.php?route=admin_users&error=Xóa thất bại");
            }
        } catch (Exception $e) {
            header("Location: index.php?route=admin_users&error=" . urlencode($e->getMessage()));
        }
        exit;
    }
}
?>
