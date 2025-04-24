<?php
namespace App\Models;

class Event {
    private $conn;
    private $table = "events";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($name, $event_date, $location, $description, $fee, $image) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (name, event_date, location, description, fee, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssds", $name, $event_date, $location, $description, $fee, $image);
        return $stmt->execute();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $name, $event_date, $location, $description, $fee, $image) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET name=?, event_date=?, location=?, description=?, fee=?, image=? WHERE id=?");
        $stmt->bind_param("ssssdsi", $name, $event_date, $location, $description, $fee, $image, $id);
        return $stmt->execute();
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM $this->table ORDER BY id DESC");
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
