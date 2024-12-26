<?php
require_once __DIR__ . '/../../config/Database.php';

class AdminModel {
    private $conn;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function getAdminEvents($adminId) {
        $query = "SELECT e.*, COUNT(ep.id) as participant_count 
                 FROM events e 
                 LEFT JOIN event_participants ep ON e.id = ep.event_id 
                 WHERE e.supervisor_id = ? 
                 GROUP BY e.id 
                 ORDER BY e.date_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$adminId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventParticipants($eventId) {
        $query = "SELECT ep.*, u.name, u.email 
                 FROM event_participants ep 
                 JOIN users u ON ep.user_id = u.id 
                 WHERE ep.event_id = ? 
                 ORDER BY ep.registration_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateParticipantStatus($participantId, $status) {
        $query = "UPDATE event_participants SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $participantId]);
    }

    public function getEventById($eventId) {
        $query = "SELECT e.*, u.name as organizer_name 
                 FROM events e 
                 JOIN users u ON e.supervisor_id = u.id 
                 WHERE e.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$eventId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPendingParticipantsCount($eventId) {
        $query = "SELECT COUNT(*) as count 
                 FROM event_participants 
                 WHERE event_id = ? AND status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$eventId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}