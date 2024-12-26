<?php
require_once __DIR__ . '/../../config/Database.php';

class AdminRequest {
    private $conn;
    private $table = 'admin_requests';

    public $id;
    public $user_id;
    public $reason;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                (user_id, reason, status, created_at) 
                VALUES (:user_id, :reason, 'pending', NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':reason', $this->reason);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function checkExistingRequest($userId) {
        $query = "SELECT * FROM " . $this->table . " 
                WHERE user_id = :user_id 
                AND status = 'pending'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function getUserRequest($userId) {
        $query = "SELECT * FROM " . $this->table . " 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
