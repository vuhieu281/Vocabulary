<?php
require_once __DIR__ . "/../config/database.php";

class User {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function create($name, $email, $password) {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $email, $password]);
    }

    public function exists($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($id, $newPassword) {
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$newPassword, $id]);
    }

    /**
     * Lấy điểm cao nhất của user
     */
    public function getHighestScore($userId) {
        $stmt = $this->db->prepare("SELECT MAX(score) as highest_score FROM quiz_results WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['highest_score'] ?? 0;
    }

    /**
     * Lấy số lần user làm quiz
     */
    public function getQuizAttempts($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as attempts FROM quiz_results WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['attempts'] ?? 0;
    }

    /**
     * Lấy điểm trung bình của user
     */
    public function getAverageScore($userId) {
        $stmt = $this->db->prepare("SELECT AVG(score) as avg_score FROM quiz_results WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return round($result['avg_score'] ?? 0, 1);
    }
}
