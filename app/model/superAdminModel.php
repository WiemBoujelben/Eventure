<?php 
require_once __DIR__ . '/../../config/Database.php';

class SuperAdminModel {
    private $conn;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function getAdminRequests() {
        $query = "SELECT ar.*, u.name, u.email 
                 FROM admin_requests ar 
                 JOIN users u ON ar.user_id = u.id 
                 WHERE ar.status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveAdminRequest($userId) {
        try {
            $this->conn->beginTransaction();

            // Update user role to admin
            $query = "UPDATE users SET role = 'admin' WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$userId]);

            // Update request status
            $query = "UPDATE admin_requests SET status = 'approved', updated_at = NOW() WHERE user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$userId]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function rejectAdminRequest($userId) {
        $query = "UPDATE admin_requests SET status = 'rejected', updated_at = NOW() WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId]);
    }

    public function findAllUsers() {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getReports() {
        $query = "SELECT * FROM reports";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function acceptUser($id) {
        $query = "UPDATE users SET role = 'admin' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function findUser($id) {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findReport($id) {
        $query = "SELECT * FROM reports WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function deleteReport($id) {
        $query = "DELETE FROM reports WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getAdmins() {
        $query = "SELECT * FROM users WHERE role = 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeAdminRole($userId) {
        $query = "UPDATE users SET role = 'user' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId]);
    }
}