<?php
require_once __DIR__ . '/../model/adminModel.php';

class AdminControler {
    private $model;

    public function __construct() {
        $this->model = new AdminModel();
    }

    public function dashboard() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user']) || 
            ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
            $_SESSION['error'] = "Access denied. Admin privileges required.";
            header('Location: index.php');
            exit();
        }

        // Fetch admin's events
        $events = $this->model->getAdminEvents($_SESSION['user']['id']);

        // Get pending participants count for each event
        foreach ($events as &$event) {
            $event['pending_count'] = $this->model->getPendingParticipantsCount($event['id'] ?? 0);
        }

        // Include the dashboard view
        include __DIR__ . '/../view/admin/dashboard.php';
    }

    public function manageParticipants() {
        // Check if user is admin or superadmin
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
            $_SESSION['error'] = "Access denied. Admin privileges required.";
            header('Location: index.php');
            exit();
        }

        if (!isset($_GET['event_id'])) {
            header('Location: index.php?page=admin&action=dashboard');
            exit();
        }

        $eventId = $_GET['event_id'];
        $event = $this->model->getEventById($eventId);

        // Check if user is the event organizer or superadmin
        if ($event['supervisor_id'] !== $_SESSION['user']['id'] && $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "You can only manage participants for your own events.";
            header('Location: index.php?page=admin&action=dashboard');
            exit();
        }

        $participants = $this->model->getEventParticipants($eventId);
        include __DIR__ . '/../view/admin/manageParticipants.php';
    }

    public function updateParticipantStatus() {
        // Check if user is admin or superadmin
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
            $_SESSION['error'] = "Access denied. Admin privileges required.";
            header('Location: index.php');
            exit();
        }

        if (!isset($_POST['participant_id']) || !isset($_POST['status']) || !isset($_POST['event_id'])) {
            header('Location: index.php?page=admin&action=dashboard');
            exit();
        }

        $participantId = $_POST['participant_id'];
        $status = $_POST['status'];
        $eventId = $_POST['event_id'];

        // Verify event ownership
        $event = $this->model->getEventById($eventId);
        if ($event['supervisor_id'] !== $_SESSION['user']['id'] && $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "You can only manage participants for your own events.";
            header('Location: index.php?page=admin&action=dashboard');
            exit();
        }

        if ($this->model->updateParticipantStatus($participantId, $status)) {
            $_SESSION['success'] = "Participant status updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update participant status.";
        }

        header('Location: index.php?page=admin&action=manageParticipants&event_id=' . $eventId);
        exit();
    }
}