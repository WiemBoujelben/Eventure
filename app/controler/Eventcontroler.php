<?php
require_once __DIR__ . '/../model/Event.php';
require_once __DIR__ . '/../model/eventModel.php';

class EventControler {
    private $event;
    private $model;

    public function __construct() {
        $this->event = new Event();
        $this->model = new eventModel();
    }

    public function index() {
        try {
            error_log("Index method called with GET params: " . print_r($_GET, true));
            
            $category = isset($_GET['category']) ? $_GET['category'] : null;
            
            $result = $this->event->read($category);
            $events = $result->fetchAll(PDO::FETCH_ASSOC);
            
            $categories = [];
            $categoryQuery = $this->event->read();
            while ($row = $categoryQuery->fetch(PDO::FETCH_ASSOC)) {
                if (!in_array($row['category'], $categories)) {
                    $categories[] = $row['category'];
                }
            }
            
            include __DIR__ . '/../view/event/EventList.php';
        } catch (PDOException $e) {
            error_log("Error in index method: " . $e->getMessage());
            $_SESSION['error'] = "Error: " . $e->getMessage();
            include __DIR__ . '/../view/event/EventList.php';
        }
    }

    public function Create() {
        // Check if user is admin or superadmin
        if (!isset($_SESSION['user']) || 
            ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
            $_SESSION['error'] = "Access denied. Admin privileges required to create events.";
            header('Location: index.php?page=event&action=list');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'date_time' => $_POST['date_time'],
                'city' => $_POST['city'],
                'category' => $_POST['category'],
                'supervisor_id' => $_SESSION['user']['id']
            ];

            if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
                $targetDir = __DIR__ . '/../../public/images/';
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $fileName = basename($_FILES["photo"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                    $event['photo'] = 'public/images/' . $fileName;
                }
            }

            if ($this->model->createEvent($event)) {
                $_SESSION['success'] = "Event created successfully!";
                header('Location: index.php?page=events');
                exit();
            } else {
                $_SESSION['error'] = "Failed to create event.";
            }
        }
        
        include __DIR__ . '/../view/event/CreateEvent.php';
    }

    public function Edit() {
        // Check if user is logged in and owns this event or is an admin
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "You must be logged in to edit an event.";
            header('Location: index.php?page=login');
            exit();
        }

        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "No event specified for editing.";
            header('Location: index.php?page=events');
            exit();
        }

        $this->event->id = $_GET['id'];
        $result = $this->event->read_single();
        $event = $result->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            $_SESSION['error'] = "Event not found.";
            header('Location: index.php?page=events');
            exit();
        }

        if ($event['supervisor_id'] != $_SESSION['user']['id'] && $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = "You don't have permission to edit this event.";
            header('Location: index.php?page=events');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->event->title = $_POST['title'];
                $this->event->category = $_POST['category'];
                $this->event->city = $_POST['city'];
                $this->event->date_time = $_POST['date_time'];
                $this->event->description = $_POST['description'];
                $this->event->status = $_POST['status'];

                if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
                    $targetDir = __DIR__ . '/../../public/images/';
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    
                    $fileName = basename($_FILES["photo"]["name"]);
                    $targetFilePath = $targetDir . $fileName;
                    
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                        $this->event->photo = 'public/images/' . $fileName;
                    }
                } else {
                    $this->event->photo = $event['photo']; 
                }

                if ($this->event->update()) {
                    $_SESSION['success'] = "Event updated successfully!";
                    header('Location: index.php?page=events');
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
        }

        include __DIR__ . '/../view/event/EditEvent.php';
    }

    public function Delete() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "You must be logged in to delete an event.";
            header('Location: index.php?page=login');
            exit();
        }
    
        // Check if ID is provided
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "No event specified for deletion.";
            header('Location: index.php?page=events');
            exit();
        }
    
        try {
            // Get the event
            $this->event->id = $_GET['id'];
            $result = $this->event->read_single();
            $event = $result->fetch(PDO::FETCH_ASSOC);
    
            // Check if event exists
            if (!$event) {
                $_SESSION['error'] = "Event not found.";
                header('Location: index.php?page=events');
                exit();
            }
    
            // Check permissions (admin who created the event)
            if (!($_SESSION['user']['role'] === 'admin' && 
                  $_SESSION['user']['id'] == $event['supervisor_id'])) {
                $_SESSION['error'] = "You don't have permission to delete this event.";
                header('Location: index.php?page=events');
                exit();
            }
    
            // Attempt to delete
            if ($this->event->delete()) {
                $_SESSION['success'] = "Event deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete event.";
            }
            
        } catch (Exception $e) {
            error_log("Error deleting event: " . $e->getMessage());
            $_SESSION['error'] = "An error occurred while deleting the event.";
        }
        
        header('Location: index.php?page=events');
        exit();
    }

    public function details() {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "No event specified.";
            header('Location: index.php?page=event&action=list');
            exit();
        }

        try {
            // Set the event ID
            $this->event->id = $_GET['id'];
            
            // Get event details
            $result = $this->event->read_single();
            $event = $result->fetch(PDO::FETCH_ASSOC);

            if (!$event) {
                $_SESSION['error'] = "Event not found.";
                header('Location: index.php?page=event&action=list');
                exit();
            }

            // Revert to using approvedParticipants
            $approvedParticipants = $this->event->getApprovedParticipants($event['id']);

            // Include the view file
            $viewFile = __DIR__ . '/../view/event/eventDetails.php';
            if (!file_exists($viewFile)) {
                throw new Exception("View file not found: " . $viewFile);
            }
            include $viewFile;
            return;
        } catch (Exception $e) {
            error_log("Error in details method: " . $e->getMessage());
            $_SESSION['error'] = "An error occurred while viewing the event.";
            header('Location: index.php?page=event&action=list');
            exit();
        }
    }

    public function register() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "You must be logged in to register for an event.";
            header('Location: index.php?page=event&action=list');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
            $eventId = $_POST['event_id'];
            $userId = $_SESSION['user']['id'];

            try {
                // Check if already requested or registered
                $status = $this->model->getParticipationStatus($eventId, $userId);
                if ($status) {
                    $_SESSION['error'] = "You have already registered or requested participation for this event.";
                    header('Location: index.php?page=event&action=details&id=' . $eventId);
                    exit();
                }

                // Add request to participant_requests
                $this->model->addParticipantRequest($eventId, $userId);
                $_SESSION['success'] = "Your request to join the event has been sent.";
            } catch (PDOException $e) {
                error_log("Error registering for event: " . $e->getMessage());
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }

            header('Location: index.php?page=event&action=details&id=' . $eventId);
            exit();
        }
    }

    public function manageParticipants() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
            $_SESSION['error'] = "Access denied.";
            header('Location: index.php?page=event&action=list');
            exit();
        }

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=admin&action=dashboard');
            exit();
        }

        $eventId = $_GET['id'];
        $event = $this->model->getEventById($eventId);
        
        // Check if user is the event organizer
        if ($event['supervisor_id'] !== $_SESSION['user']['id'] && $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "You can only manage participants for your own events.";
            header('Location: index.php?page=admin&action=dashboard');
            exit();
        }

        $participants = $this->model->getEventParticipants($eventId);
        include __DIR__ . '/../view/event/manageParticipants.php';
    }

    public function updateParticipantStatus() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
            $_SESSION['error'] = "Access denied.";
            header('Location: index.php?page=event&action=list');
            exit();
        }

        if (!isset($_POST['participant_id']) || !isset($_POST['status'])) {
            header('Location: index.php?page=admin&action=dashboard');
            exit();
        }

        $participantId = $_POST['participant_id'];
        $status = $_POST['status'];
        $eventId = $_POST['event_id'];

        if ($this->model->updateParticipantStatus($participantId, $status)) {
            $_SESSION['success'] = "Participant status updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update participant status.";
        }

        header('Location: index.php?page=event&action=manageParticipants&id=' . $eventId);
        exit();
    }

    public function submitRating() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "You must be logged in to rate participants.";
            header('Location: index.php?page=event&action=list');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $_POST['event_id'];
            $participantId = $_POST['participant_id'];
            $raterId = $_SESSION['user']['id'];
            $rating = $_POST['rating'];
    
            try {
                // Log the rating details
                error_log("Submitting rating: event_id=$eventId, participant_id=$participantId, rater_id=$raterId, rating=$rating");
    
                // Store the rating in the database
                $this->event->addRating($eventId, $participantId, $raterId, $rating);
                $_SESSION['success'] = "Rating submitted successfully.";
    
                // Update the participant's average rating in the users table
                $this->updateParticipantRating($participantId);
            } catch (PDOException $e) {
                error_log("Error submitting rating: " . $e->getMessage());
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
    
            header('Location: index.php?page=event&action=details&id=' . $eventId);
            exit();
        }
    }

    public function updateParticipantRating($participantId) {
        try {
            // Calculate the average rating for the participant
            $query = "SELECT AVG(rating) as avg_rating FROM ratings WHERE participant_id = :participant_id";
            $stmt = $this->event->getConnection()->prepare($query); // Use the getConnection method from Event model
            $stmt->bindParam(':participant_id', $participantId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                $avgRating = $result['avg_rating'];
    
                // Update the user's rating in the users table
                $updateQuery = "UPDATE users SET rating = :rating WHERE id = :participant_id";
                $updateStmt = $this->event->getConnection()->prepare($updateQuery); // Use the getConnection method from Event model
                $updateStmt->bindParam(':rating', $avgRating);
                $updateStmt->bindParam(':participant_id', $participantId);
                $updateStmt->execute();
            }
        } catch (PDOException $e) {
            error_log("Error updating participant rating: " . $e->getMessage());
        }
    }

    public function getParticipantRatings($eventId) {
        $query = "SELECT p.user_id, p.name, IFNULL(AVG(r.rating), 0) as average_rating
                  FROM event_participants ep
                  JOIN users p ON ep.user_id = p.id
                  LEFT JOIN ratings r ON p.id = r.participant_id
                  WHERE ep.event_id = :event_id
                  GROUP BY p.user_id, p.name";

        $stmt = $this->event->getConnection()->prepare($query);
        $stmt->bindParam(':event_id', $eventId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>