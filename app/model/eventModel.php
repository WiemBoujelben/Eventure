<?php
require_once __DIR__ . '/../../config/Database.php';

class EventModel {
    private $conn;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function getAllEvents() {
        $query = "SELECT e.*, u.name as organizer_name 
                 FROM events e 
                 JOIN users u ON e.supervisor_id = u.id 
                 ORDER BY e.date_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEvent($event) {
        $query = "INSERT INTO events (title, description, date_time, city, category, photo, supervisor_id, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, 'active')";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $event['title'],
            $event['description'],
            $event['date_time'],
            $event['city'],
            $event['category'],
            isset($event['photo']) ? $event['photo'] : null,
            $event['supervisor_id']
        ]);
    }

    public function getEventById($id) {
        $query = "SELECT e.*, u.name as organizer_name 
                 FROM events e 
                 JOIN users u ON e.supervisor_id = u.id 
                 WHERE e.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getParticipationStatus($eventId, $userId) {
        $query = "SELECT status FROM event_participants WHERE event_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$eventId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['status'] : false;
    }

    public function registerForEvent($eventId, $userId) {
        $query = "INSERT INTO event_participants (event_id, user_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$eventId, $userId]);
    }

    public function getEventParticipants($eventId) {
        $query = "SELECT ep.*, u.name, u.email 
                 FROM event_participants ep 
                 JOIN users u ON ep.user_id = u.id 
                 WHERE ep.event_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateParticipantStatus($participantId, $status) {
        $query = "UPDATE event_participants SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $participantId]);
    }

    public function getEventsByOrganizer($organizerId) {
        $query = "SELECT * FROM events WHERE supervisor_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$organizerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
