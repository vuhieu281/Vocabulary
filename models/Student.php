<?php
class Student
{
    private $conn;
    private $table = "students";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function all()
    {
        $sql = "SELECT s.*, d.name AS department_name 
                FROM {$this->table} s 
                LEFT JOIN departments d ON s.department_id = d.id 
                ORDER BY s.created_at DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (student_code, full_name, email, major, department_id)
                                      VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['student_code'],
            $data['full_name'],
            $data['email'],
            $data['major'],
            $data['department_id'] ?: null
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table}
                                      SET student_code=?, full_name=?, email=?, major=?, department_id=?
                                      WHERE id=?");
        return $stmt->execute([
            $data['student_code'],
            $data['full_name'],
            $data['email'],
            $data['major'],
            $data['department_id'] ?: null,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}
