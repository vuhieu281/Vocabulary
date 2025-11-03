<?php
class Department
{
    private $conn;
    private $table = "departments";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function all()
    {
        $stmt = $this->conn->query("SELECT * FROM {$this->table} ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function update($id, $name)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET name=? WHERE id=?");
        return $stmt->execute([$name, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}
