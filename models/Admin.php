<?php
require_once __DIR__ . "/../config/database.php";

class Admin {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // ========== DASHBOARD STATS ==========

    public function getDashboardStats() {
        $stats = [];

        // Tổng số người dùng
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Tổng số từ vựng
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM local_words");
        $stmt->execute();
        $stats['total_words'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Tổng số chủ đề
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM topics");
        $stmt->execute();
        $stats['total_topics'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Tổng số lượt tìm kiếm
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM search_history");
        $stmt->execute();
        $stats['total_searches'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Người dùng mới trong 7 ngày
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
        $stmt->execute();
        $stats['new_users_7days'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Lượt tìm kiếm trong 7 ngày
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM search_history 
            WHERE searched_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
        $stmt->execute();
        $stats['searches_7days'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        return $stats;
    }

    // ========== USER MANAGEMENT ==========

    public function getAllUsers($limit = 20, $offset = 0) {
        $query = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUsers() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $name, $email, $role) {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $role, $id]);
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ========== WORD MANAGEMENT ==========

    public function getAllWords($limit = 20, $offset = 0) {
        $query = "SELECT * FROM local_words ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countWords() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM local_words");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getWordById($id) {
        $stmt = $this->db->prepare("SELECT * FROM local_words WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createWord($word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url) {
        $stmt = $this->db->prepare("
            INSERT INTO local_words (word, part_of_speech, ipa, audio_link, senses, level, oxford_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url]);
    }

    public function updateWord($id, $word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url) {
        $stmt = $this->db->prepare("
            UPDATE local_words 
            SET word = ?, part_of_speech = ?, ipa = ?, audio_link = ?, senses = ?, level = ?, oxford_url = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$word, $part_of_speech, $ipa, $audio_link, $senses, $level, $oxford_url, $id]);
    }

    public function deleteWord($id) {
        $stmt = $this->db->prepare("DELETE FROM local_words WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ========== TOPIC MANAGEMENT ==========

    public function getAllTopics($limit = 20, $offset = 0) {
        $query = "SELECT * FROM topics ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countTopics() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM topics");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTopicById($id) {
        $stmt = $this->db->prepare("SELECT * FROM topics WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTopic($name, $description, $image = null) {
        $stmt = $this->db->prepare("
            INSERT INTO topics (name, description, image) 
            VALUES (?, ?, ?)
        ");
        $result = $stmt->execute([$name, $description, $image]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function updateTopic($id, $name, $description, $image = null) {
        if ($image) {
            $stmt = $this->db->prepare("
                UPDATE topics 
                SET name = ?, description = ?, image = ? 
                WHERE id = ?
            ");
            return $stmt->execute([$name, $description, $image, $id]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE topics 
                SET name = ?, description = ? 
                WHERE id = ?
            ");
            return $stmt->execute([$name, $description, $id]);
        }
    }

    public function deleteTopic($id) {
        $stmt = $this->db->prepare("DELETE FROM topics WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ========== ACTIVITY LOG ==========

    public function getRecentActivities($limit = 50) {
        $query = "
            SELECT 
                'search' as activity_type,
                u.id as user_id,
                u.name as user_name,
                lw.word as target_name,
                sh.searched_at as activity_date
            FROM search_history sh
            JOIN users u ON sh.user_id = u.id
            JOIN local_words lw ON sh.local_word_id = lw.id
            
            UNION ALL
            
            SELECT 
                'saved_word' as activity_type,
                u.id as user_id,
                u.name as user_name,
                lw.word as target_name,
                sw.saved_at as activity_date
            FROM saved_words sw
            JOIN users u ON sw.user_id = u.id
            JOIN local_words lw ON sw.local_word_id = lw.id
            
            ORDER BY activity_date DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserActivityHistory($user_id, $limit = 50, $offset = 0) {
        $query = "
            SELECT 
                'search' as activity_type,
                lw.word as target_name,
                sh.searched_at as activity_date
            FROM search_history sh
            JOIN local_words lw ON sh.local_word_id = lw.id
            WHERE sh.user_id = :user_id
            
            UNION ALL
            
            SELECT 
                'saved_word' as activity_type,
                lw.word as target_name,
                sw.saved_at as activity_date
            FROM saved_words sw
            JOIN local_words lw ON sw.local_word_id = lw.id
            WHERE sw.user_id = :user_id
            
            ORDER BY activity_date DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUserActivities($user_id) {
        $query = "
            SELECT COUNT(*) as total FROM (
                SELECT id FROM search_history WHERE user_id = ?
                UNION ALL
                SELECT id FROM saved_words WHERE user_id = ?
            ) as activities
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id, $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getActivityStats() {
        $stats = [];

        // Lượt tìm kiếm theo ngày trong 7 ngày gần nhất
        $stmt = $this->db->prepare("
            SELECT DATE(searched_at) as date, COUNT(*) as count
            FROM search_history
            WHERE searched_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(searched_at)
            ORDER BY date DESC
        ");
        $stmt->execute();
        $stats['searches_by_date'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Top 10 từ được tìm kiếm nhiều nhất
        $stmt = $this->db->prepare("
            SELECT lw.word, COUNT(sh.id) as search_count
            FROM search_history sh
            JOIN local_words lw ON sh.local_word_id = lw.id
            GROUP BY sh.local_word_id
            ORDER BY search_count DESC
            LIMIT 10
        ");
        $stmt->execute();
        $stats['top_searched_words'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    // ========== TOPIC-WORD MAPPING ==========

    public function getLastTopicId() {
        $stmt = $this->db->prepare("SELECT id FROM topics ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    public function getTopicWords($topicId) {
        $stmt = $this->db->prepare("
            SELECT lw.id, lw.word 
            FROM local_words lw
            INNER JOIN word_topic_mapping wtm ON lw.id = wtm.word_id
            WHERE wtm.topic_id = ?
            ORDER BY lw.word ASC
        ");
        $stmt->execute([$topicId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignWordsToTopic($topicId, $words) {
        if (empty($words)) {
            return true;
        }

        try {
            $this->db->beginTransaction();
            
            foreach ($words as $word) {
                $word = trim($word);
                if (empty($word)) {
                    continue;
                }

                // Tìm từ vựng theo tên
                $stmt = $this->db->prepare("SELECT id FROM local_words WHERE word = ? LIMIT 1");
                $stmt->execute([$word]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $wordId = $result['id'];
                    
                    // Thêm vào word_topic_mapping
                    $stmt = $this->db->prepare("
                        INSERT INTO word_topic_mapping (word_id, topic_id, created_at) 
                        VALUES (?, ?, NOW())
                        ON DUPLICATE KEY UPDATE created_at = NOW()
                    ");
                    $stmt->execute([$wordId, $topicId]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function removeWordFromTopic($wordId, $topicId) {
        $stmt = $this->db->prepare("
            DELETE FROM word_topic_mapping 
            WHERE word_id = ? AND topic_id = ?
        ");
        return $stmt->execute([$wordId, $topicId]);
    }

    public function searchWords($search = '', $limit = 50) {
        if (empty($search)) {
            $stmt = $this->db->prepare("
                SELECT id, word 
                FROM local_words 
                ORDER BY word ASC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
        } else {
            $searchTerm = '%' . $search . '%';
            $stmt = $this->db->prepare("
                SELECT id, word 
                FROM local_words 
                WHERE word LIKE ? 
                ORDER BY word ASC 
                LIMIT ?
            ");
            $stmt->execute([$searchTerm, $limit]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
