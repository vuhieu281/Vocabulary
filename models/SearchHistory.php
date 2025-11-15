<?php
require_once __DIR__ . "/../config/database.php";

class SearchHistory {
    private $db;

    public function __construct($db = null) {
        if ($db === null) {
            $this->db = (new Database())->connect();
        } else {
            $this->db = $db;
        }
    }

    /**
     * Lưu lịch sử tìm kiếm
     */
    public function save($user_id, $local_word_id) {
        $stmt = $this->db->prepare("
            INSERT INTO search_history (user_id, local_word_id, searched_at) 
            VALUES (?, ?, NOW())
        ");
        return $stmt->execute([$user_id, $local_word_id]);
    }

    /**
     * Lấy lịch sử tìm kiếm của user (mới nhất trước)
     */
    public function getByUserId($user_id, $limit = 20, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT 
                sh.id,
                sh.searched_at,
                w.id as word_id,
                w.word,
                w.part_of_speech,
                w.ipa
            FROM search_history sh
            JOIN local_words w ON sh.local_word_id = w.id
            WHERE sh.user_id = ?
            ORDER BY sh.searched_at DESC
            LIMIT ? OFFSET ?
        ");
        $limit = (int)$limit;
        $offset = (int)$offset;
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->bindParam(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm tổng số lịch sử tìm kiếm của user
     */
    public function countByUserId($user_id) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM search_history 
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Xóa lịch sử tìm kiếm theo ID
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM search_history WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Xóa toàn bộ lịch sử tìm kiếm của user
     */
    public function deleteAll($user_id) {
        $stmt = $this->db->prepare("DELETE FROM search_history WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    /**
     * Lấy từ được tìm kiếm nhiều nhất
     */
    public function getMostSearched($user_id, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT 
                w.id as word_id,
                w.word,
                w.part_of_speech,
                COUNT(sh.id) as search_count
            FROM search_history sh
            JOIN local_words w ON sh.local_word_id = w.id
            WHERE sh.user_id = ?
            GROUP BY w.id
            ORDER BY search_count DESC
            LIMIT ?
        ");
        $limit = (int)$limit;
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
