<?php

require_once __DIR__ . '/../config/database.php';

class Flashcard {
    private $db;
    private $table_saved_words = 'saved_words';
    private $table_local_words = 'local_words';

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = (new Database())->connect();
        }
    }

    public function getFlashcardsByUserId($userId) {
        $query = "
            SELECT 
                lw.id,
                lw.word,
                lw.ipa,
                lw.audio_link,
                lw.part_of_speech,
                lw.senses,
                lw.level,
                lw.oxford_url
            FROM " . $this->table_saved_words . " sw
            JOIN " . $this->table_local_words . " lw ON sw.local_word_id = lw.id
            WHERE sw.user_id = :user_id
            ORDER BY RAND()
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSavedWordsCount($userId) {
        $query = "
            SELECT COUNT(*) as total
            FROM " . $this->table_saved_words . "
            WHERE user_id = :user_id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getRecentFlashcard($userId) {
        $query = "
            SELECT 
                lw.id,
                lw.word,
                lw.ipa,
                lw.audio_link,
                lw.part_of_speech,
                lw.senses
            FROM " . $this->table_saved_words . " sw
            JOIN " . $this->table_local_words . " lw ON sw.local_word_id = lw.id
            WHERE sw.user_id = :user_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFlashcardsPaginated($userId, $limit = 10, $offset = 0) {
        $query = "
            SELECT 
                lw.id,
                lw.word,
                lw.ipa,
                lw.audio_link,
                lw.part_of_speech,
                lw.senses,
                lw.level
            FROM " . $this->table_saved_words . " sw
            JOIN " . $this->table_local_words . " lw ON sw.local_word_id = lw.id
            WHERE sw.user_id = :user_id
            ORDER BY RAND()
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFlashcardDetail($wordId) {
        $query = "
            SELECT *
            FROM " . $this->table_local_words . "
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $wordId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasSavedWords($userId) {
        $count = $this->getSavedWordsCount($userId);
        return $count > 0;
    }
}
