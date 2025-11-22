<?php
class Database {
    private $host = "localhost:3306";
    private $db_name = "vocabulary_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            // Cài đặt charset UTF-8
            $this->conn->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            echo "Kết nối thất bại: " . $e->getMessage();
        }
        return $this->conn;
    }

    // Backwards-compatible alias for older code that expects getConnection()
    public function getConnection() {
        return $this->connect();
    }
}
