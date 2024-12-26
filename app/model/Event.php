<?php
require_once __DIR__ . '/../../config/Database.php';

class Event {
    private $conn;
    private $table = 'events';

    public $id;
    public $photo;
    public $title;
    public $category;
    public $city;
    public $date_time;
    public $description;
    public $supervisor_id;
    public $status;
    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . "
                (photo, title, category, city, date_time, description, supervisor_id, status)
                VALUES
                (:photo, :title, :category, :city, :date_time, :description, :supervisor_id, :status)";

        $stmt = $this->conn->prepare($query);

        $this->photo = htmlspecialchars(strip_tags($this->photo));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':date_time', $this->date_time);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':supervisor_id', $this->supervisor_id);
        $stmt->bindParam(':status', $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($category = null) {
        $query = "SELECT * FROM " . $this->table;
        
        if ($category) {
            $query .= " WHERE category = :category";
        }
        
        $query .= " ORDER BY date_time DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($category) {
            $stmt->bindParam(':category', $category);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function read_single() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table . "
                SET
                    photo = :photo,
                    title = :title,
                    category = :category,
                    city = :city,
                    date_time = :date_time,
                    description = :description,
                    status = :status
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        $this->photo = htmlspecialchars(strip_tags($this->photo));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':date_time', $this->date_time);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function subscribe($userId, $eventId) {
        $query = "INSERT INTO event_subscriptions (user_id, event_id, subscription_date) 
                  VALUES (:user_id, :event_id, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':event_id', $eventId);
        
        return $stmt->execute();
    }

    public function getUserEvents($userId) {
        $query = "SELECT e.* 
                  FROM " . $this->table . " e
                  INNER JOIN event_subscriptions es ON e.id = es.event_id
                  WHERE es.user_id = :user_id
                  ORDER BY e.date_time DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isUserSubscribed($userId, $eventId) {
        $query = "SELECT COUNT(*) FROM event_subscriptions 
                  WHERE user_id = :user_id AND event_id = :event_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':event_id', $eventId);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }
}
?>