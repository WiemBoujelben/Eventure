<?php 
// Define base path ............
define('BASE_PATH', __DIR__);

// Start session
session_start();

// Include necessary files
require_once BASE_PATH . '/config/Database.php';
require_once BASE_PATH . '/app/model/User.php';
require_once BASE_PATH . '/app/model/Event.php';
require_once BASE_PATH . '/app/controler/Usercontroler.php';
require_once BASE_PATH . '/app/controler/Eventcontroler.php';
require_once BASE_PATH . '/app/controler/adminControler.php';
require_once BASE_PATH . '/app/controler/superAdminControler.php';
require_once BASE_PATH . '/app/model/AdminRequest.php';

// Initialize controllers
$userController = new UserController();
$eventController = new EventControler();
$adminController = new AdminControler();
$superAdminController = new SuperAdminControler();

// Get the page parameter
$page = $_GET['page'] ?? 'index'; // Default to login if no page is specified
$action = $_GET['action'] ?? 'index'; // Default action for event-related functionality

switch ($page) {
    case 'register':
        $userController->register();
        break;

    case 'login':
        $userController->login();
        break;

    case 'profile':
        $userController->profile();
        break;

    case 'edit_profile':
        $userController->edit_profile();
        break;

    case 'update_profile_photo':
        $userController->update_profile_photo();
        break;

    case 'admin':
        // Check if user is admin or superadmin
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
            $_SESSION['error'] = "Access denied. Admin privileges required.";
            header('Location: index.php?page=login');
            exit();
        }

        switch ($action) {
            case 'dashboard':
                $adminController->dashboard();
                break;
            case 'manageParticipants':
                $adminController->manageParticipants();
                break;
            case 'updateParticipantStatus':
                $adminController->updateParticipantStatus();
                break;
            default:
                $adminController->dashboard();
                break;
        }
        break;

    case 'events':
        // Handle event-related actions
        switch (strtolower($action)) {
            case 'create':
                $eventController->Create();
                break;

            case 'edit':
                $eventController->Edit();
                break;

            case 'delete':
                $eventController->Delete();
                break;

            default:
                $eventController->index();
                break;
        }
        break;

    case 'event':
        require_once 'app/controler/eventControler.php';
        $eventController = new EventControler();

        switch ($action) {
            case 'list':
                $eventController->index();
                break;
            case 'create_event':
                // Check if user is admin or superadmin
                if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
                    $_SESSION['error'] = "Access denied. Admin privileges required.";
                    header('Location: index.php');
                    exit();
                }
                $eventController->create();
                break;
            case 'details':
                $eventController->details();
                break;
            case 'register':
                $eventController->register();
                break;
            case 'manageParticipants':
                $eventController->manageParticipants();
                break;
            case 'updateParticipantStatus':
                $eventController->updateParticipantStatus();
                break;
            default:
                $eventController->index();
                break;
        }
        break;

    case 'superadmin':
        // Check if user is superadmin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "Access denied. SuperAdmin privileges required.";
            header('Location: index.php');
            exit();
        }
        
        switch ($action) {
            case 'dashboard':
                $superAdminController->dashboard();
                break;
            case 'manage_admin_requests':
                $superAdminController->manageAdminRequests();
                break;
            case 'approve_admin':
                $superAdminController->approveAdmin();
                break;
            case 'reject_admin':
                $superAdminController->rejectAdmin();
                break;
            case 'approve_admin_request':
                if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
                    $_SESSION['error'] = "Access denied. SuperAdmin privileges required.";
                    header('Location: index.php');
                    exit();
                }

                if (!isset($_GET['user_id'])) {
                    $_SESSION['error'] = "Invalid user ID.";
                    header('Location: index.php?page=superadmin&action=dashboard');
                    exit();
                }

                $superAdminController->approveRequest($_GET['user_id']);
                break;
            case 'reject_admin_request':
                if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
                    $_SESSION['error'] = "Access denied. SuperAdmin privileges required.";
                    header('Location: index.php');
                    exit();
                }

                if (!isset($_GET['user_id'])) {
                    $_SESSION['error'] = "Invalid user ID.";
                    header('Location: index.php?page=superadmin&action=dashboard');
                    exit();
                }

                $superAdminController->rejectRequest($_GET['user_id']);
                break;
            case 'remove_admin_role':
                if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
                    $_SESSION['error'] = "Access denied. SuperAdmin privileges required.";
                    header('Location: index.php');
                    exit();
                }

                if (!isset($_GET['user_id'])) {
                    $_SESSION['error'] = "Invalid user ID.";
                    header('Location: index.php?page=superadmin&action=dashboard');
                    exit();
                }

                $superAdminController->removeAdminRole($_GET['user_id']);
                break;
            default:
                $superAdminController->dashboard();
                break;
        }
        break;

    case 'request_admin':
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit();
        }

        // Create an instance of AdminRequest
        $adminRequest = new AdminRequest();

        if ($action === 'submit') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Check for existing pending request
                if ($adminRequest->checkExistingRequest($_SESSION['user']['id'])) {
                    $_SESSION['error'] = "You already have a pending admin request.";
                } else {
                    $adminRequest->user_id = $_SESSION['user']['id'];
                    $adminRequest->reason = $_POST['reason'];
                    
                    if ($adminRequest->create()) {
                        $_SESSION['success'] = "Your admin request has been submitted successfully.";
                    } else {
                        $_SESSION['error'] = "There was an error submitting your request.";
                    }
                }
            }
            header('Location: index.php?page=request_admin');
            exit();
        }
        
        // Load the request admin view
        include BASE_PATH . '/app/view/user/request_admin.php';
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?page=login');
        exit();
        break;

    default:
        if (isset($_SESSION['user'])) {
            header('Location: index.php?page=event&action=list');
        } else {
            header('Location: index.php?page=login');
        }
        exit();
        break;
}
?>
