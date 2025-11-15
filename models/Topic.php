<?php
require_once __DIR__ . '/../config/database.php';

class Topic {
    private $db;
    private $table = 'topics';

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = (new Database())->connect();
        }
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countWords($topicId) {
        $query = "SELECT COUNT(*) AS total FROM topic_words WHERE topic_id = :tid";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tid', $topicId, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($res['total'] ?? 0);
    }

    public function getWords($topicId) {
        $query = "SELECT lw.* FROM topic_words tw JOIN local_words lw ON tw.local_word_id = lw.id WHERE tw.topic_id = :tid ORDER BY lw.word ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tid', $topicId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
