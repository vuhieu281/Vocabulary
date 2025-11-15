<?php
// models/Word.php - Model để quản lý dữ liệu từ vựng

class Word {
    private $db;
    private $table = 'local_words';

    public function __construct($db) {
        $this->db = $db;
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
     * Tìm kiếm từ vựng theo từ khóa (bắt đầu với keyword)
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
     * Tìm kiếm từ chính xác
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
     * Đếm tổng số từ vựng theo từ khóa
     */
    public function countSearch($keyword) {
        $keyword = '%' . trim($keyword) . '%';
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE word LIKE :keyword OR senses LIKE :keyword";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Gợi ý tìm kiếm (Autocomplete) - tìm từ bắt đầu bằng term
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
}
?>
