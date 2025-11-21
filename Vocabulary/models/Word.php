<?php
// models/Word.php - Model để quản lý dữ liệu từ vựng

require_once __DIR__ . '/../config/database.php';

class Word {
    private $db;
    private $table = 'local_words';

    // Accept an optional PDO instance for flexibility (works with both patterns)
    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = (new Database())->connect();
        }
    }

    /**
     * Lấy tất cả từ vựng
     */
    public function getAll($limit = 10, $offset = 0) {
        $query = "SELECT * FROM " . $this->table . " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm kiếm từ vựng theo từ khóa
     */
    public function search($keyword, $limit = 10, $offset = 0) {
        $keyword = trim($keyword) . '%';
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE word LIKE :keyword 
                  ORDER BY word ASC 
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy từ chính xác
     */
    public function searchExact($word) {
        $query = "SELECT * FROM " . $this->table . " WHERE word = :word";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':word', $word, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy từ vựng theo ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy từ vựng theo level
     */
    public function getByLevel($level, $limit = 20, $offset = 0) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE level = :level 
                  ORDER BY word ASC 
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':level', $level, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm số từ tìm được
     */
    public function countSearch($keyword) {
        $keyword = '%' . trim($keyword) . '%';
        $query = "SELECT COUNT(*) AS total FROM " . $this->table . " 
                  WHERE word LIKE :keyword OR senses LIKE :keyword";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Gợi ý autocomplete
     */
    public function autocomplete($term, $limit = 10) {
        $term = trim($term) . '%';
        $query = "SELECT id, word FROM " . $this->table . " 
                  WHERE word LIKE :term 
                  ORDER BY word ASC 
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':term', $term, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy từ đã lưu của user
     */
    public function getSavedWords($userId) {
        // Backwards-compatible: if only $userId passed, return all saved words.
        $args = func_get_args();
        if (count($args) === 1) {
            $sql = "
                SELECT lw.id, lw.word, lw.part_of_speech
                FROM saved_words sw
                JOIN local_words lw ON lw.id = sw.local_word_id
                WHERE sw.user_id = ?
                ORDER BY sw.saved_at DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // If limit/offset provided, use pagination
        $limit = isset($args[1]) ? (int)$args[1] : 20;
        $offset = isset($args[2]) ? (int)$args[2] : 0;

        $sql = "
            SELECT lw.id, lw.word, lw.part_of_speech
            FROM saved_words sw
            JOIN local_words lw ON lw.id = sw.local_word_id
            WHERE sw.user_id = :uid
            ORDER BY sw.saved_at DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Đếm số lượng từ đã lưu của user
     */
    public function countSavedWords($userId) {
        $sql = "SELECT COUNT(*) AS total FROM saved_words WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($res['total'] ?? 0);
    }

    /**
     * Lấy kết quả quiz của user
     */
    public function getQuizResults($userId) {
        $sql = "
            SELECT * 
            FROM quiz_results
            WHERE user_id = ?
            ORDER BY created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
