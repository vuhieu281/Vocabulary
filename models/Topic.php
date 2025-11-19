<?php
require_once __DIR__ . '/../config/database.php';

class Topic {
    private $db;

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = (new Database())->connect();
        }
    }

    /**
     * Lấy tất cả topics
     */
    public function getAll() {
        // Do not select `image` here because older DB schemas may not have that column.
        $query = "SELECT id, name, description FROM topics ORDER BY id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy topic theo ID
     */
    public function getById($id) {
        $query = "SELECT id, name, description FROM topics WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm số từ trong topic
     */
    public function countWords($topicId) {
        $query = "SELECT COUNT(*) AS total FROM topic_words WHERE topic_id = :tid";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tid', $topicId, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($res['total'] ?? 0);
    }

    /**
     * Lấy danh sách từ của topic
     */
    public function getWords($topicId) {
        $query = "SELECT lw.id, lw.word, lw.part_of_speech, lw.ipa, lw.audio_link, lw.senses 
                  FROM topic_words tw 
                  JOIN local_words lw ON tw.local_word_id = lw.id 
                  WHERE tw.topic_id = :tid 
                  ORDER BY lw.word ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tid', $topicId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả topics với đếm từ
     */
    public function getAllWithCounts() {
        $topics = $this->getAll();
        foreach ($topics as &$t) {
            $t['count'] = $this->countWords($t['id']);
        }
        return $topics;
    }

    /**
     * Tạo topic mới (cho admin sau này)
     */
    public function create($name, $description) {
        $query = "INSERT INTO topics (name, description) VALUES (:name, :description)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return (int)$this->db->lastInsertId();
        }
        return null;
    }

    /**
     * Cập nhật topic (cho admin sau này)
     */
    public function update($id, $name, $description) {
        $query = "UPDATE topics SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    /**
     * Xóa topic (cho admin sau này)
     */
    public function delete($id) {
        $query = "DELETE FROM topics WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Thêm từ vào topic (cho admin sau này)
     */
    public function addWord($topicId, $localWordId) {
        $query = "INSERT INTO topic_words (topic_id, local_word_id) VALUES (:tid, :wid)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tid', $topicId, PDO::PARAM_INT);
        $stmt->bindParam(':wid', $localWordId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Xóa từ khỏi topic (cho admin sau này)
     */
    public function removeWord($topicId, $localWordId) {
        $query = "DELETE FROM topic_words WHERE topic_id = :tid AND local_word_id = :wid";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tid', $topicId, PDO::PARAM_INT);
        $stmt->bindParam(':wid', $localWordId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
