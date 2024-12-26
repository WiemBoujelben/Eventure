<?php
require_once __DIR__ . '/../model/Event.php';
require_once __DIR__ . '/../model/eventModel.php';

class EventControler {
    private $event;
    private $model;

    public function __construct() {
        $this->event = new Event();
        $this->model = new EventModel();
    }

    public function index() {
        try {
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
        // Check if user is logged in and owns this event or is an admin
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "You must be logged in to delete an event.";
            header('Location: index.php?page=login');
            exit();
        }

        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        }

        try {
            $this->event->id = $_GET['id'];
            $result = $this->event->read_single();
            $event = $result->fetch(PDO::FETCH_ASSOC);

            if (!$event) {
                $_SESSION['error'] = "Event not found.";
                header('Location: index.php?page=events');
                exit();
            }

            if ($event['supervisor_id'] != $_SESSION['user']['id'] && $_SESSION['user']['role'] !== 'admin') {
                $_SESSION['error'] = "You don't have permission to delete this event.";
                header('Location: index.php?page=events');
                exit();
            }

            if ($this->event->delete()) {
                header('Location: index.php');
                exit();
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function details() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?page=event&action=list');
            exit();
        }

        $event = $this->model->getEventById($_GET['id']);
        if (!$event) {
            $_SESSION['error'] = "Event not found.";
            header('Location: index.php?page=event&action=list');
            exit();
        }

        // Get participation status if user is logged in
        $participationStatus = false;
        if (isset($_SESSION['user'])) {
            $participationStatus = $this->model->getParticipationStatus($_GET['id'], $_SESSION['user']['id']);
        }

        include __DIR__ . '/../view/event/eventDetails.php';
    }

    public function register() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Please login to register for events.";
            header('Location: index.php?page=login');
            exit();
        }

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=event&action=list');
            exit();
        }

        $eventId = $_GET['id'];
        $userId = $_SESSION['user']['id'];

        // Check if already registered
        if ($this->model->getParticipationStatus($eventId, $userId)) {
            $_SESSION['error'] = "You have already registered for this event.";
            header('Location: index.php?page=event&action=details&id=' . $eventId);
            exit();
        }

        if ($this->model->registerForEvent($eventId, $userId)) {
            $_SESSION['success'] = "Registration request submitted successfully. Waiting for approval.";
        } else {
            $_SESSION['error'] = "Failed to register for the event.";
        }

        header('Location: index.php?page=event&action=details&id=' . $eventId);
        exit();
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
}
?>