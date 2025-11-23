<?php
require_once __DIR__ . '/../config/database.php';

class ChatModel {
    private $db;

    public function __construct($db = null) {
        if ($db) $this->db = $db;
        else $this->db = (new Database())->connect();
    }

    // Save a chat message 
    public function saveMessage($userId, $role, $message, $meta = null) {
        $sql = "INSERT INTO chat_history (user_id, role, message, meta, created_at) VALUES (:uid, :role, :msg, :meta, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':msg', $message);
        $stmt->bindParam(':meta', $meta);
        return $stmt->execute();
    }

    // Fetch history for a user, newest last 
    public function getHistory($userId, $limit = 50, $offset = 0) {
        $sql = "SELECT id, role, message, meta, created_at FROM chat_history WHERE user_id = :uid ORDER BY created_at ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countHistory($userId) {
        $sql = "SELECT COUNT(*) AS total FROM chat_history WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($r['total'] ?? 0);
    }
}
