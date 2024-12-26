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
require_once BASE_PATH . '/app/controler/ReportControler.php'; // Include ReportControler

// Initialize controllers
$userController = new UserController();
$eventController = new EventControler();
$adminController = new AdminControler();
$superAdminController = new SuperAdminControler();
$reportController = new ReportController(); // Fixed class name

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

 

    case 'event':
        // Debug log for event routing
        error_log("Event routing - Action: " . $action);
        error_log("GET params: " . print_r($_GET, true));
        
        switch ($action) {
            case 'details':
                error_log("Routing to details action");
                $eventController->details();
                break;
            case 'list':
                $eventController->index();
                break;
            case 'submitRating':
                $eventController->submitRating();
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
            case 'register':
                $eventController->register();
                break;
            case 'manageParticipants':
                $eventController->manageParticipants();
                break;
            case 'updateParticipantStatus':
                $eventController->updateParticipantStatus();
                break;
            case 'create':
                $eventController->Create();
                break;
            case 'Edit':  
                $eventController->Edit();
                break;
            case 'Delete':
                $eventController->Delete();
                break;
            default:
                error_log("No matching action found, defaulting to index");
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
                $adminRequests = $superAdminController->getAdminRequests();
                $users = $superAdminController->getAllUsers();
                $reports = $superAdminController->getReports();
                include_once __DIR__ . '/app/view/superadmin/dashboard.php';
                break;

            case 'viewReport':
                if (isset($_GET['id'])) {
                    $superAdminController->viewReport($_GET['id']);
                } else {
                    header('Location: index.php?page=superadmin&action=dashboard');
                }
                break;

            case 'deleteReport':
                if (isset($_GET['id'])) {
                    $superAdminController->deleteReport($_GET['id']);
                }
                header('Location: index.php?page=superadmin&action=dashboard');
                break;

            case 'approve_admin_request':
                $superAdminController->approveAdminRequest();
                break;

            case 'reject_admin_request':
                $superAdminController->rejectAdminRequest();
                break;

            case 'viewUser':
                $superAdminController->viewUser();
                break;

            case 'deleteUser':
                if (!isset($_GET['id'])) {
                    $_SESSION['error'] = "ID d'utilisateur manquant.";
                    header('Location: index.php?page=superadmin');
                    exit();
                }
                
                if ($superAdminController->deleteUser($_GET['id'])) {
                    $_SESSION['success'] = "Utilisateur supprimé avec succès.";
                }
                header('Location: index.php?page=superadmin');
                exit();
                break;

            case 'delete_user':
                if (!isset($_GET['id'])) {
                    $_SESSION['error'] = "ID d'utilisateur manquant.";
                    header('Location: index.php?page=superadmin');
                    exit();
                }
                
                if ($superAdminController->deleteUser($_GET['id'])) {
                    $_SESSION['success'] = "Utilisateur supprimé avec succès.";
                }
                header('Location: index.php?page=superadmin');
                exit();
                break;

            default:
                header('Location: index.php?page=superadmin&action=dashboard');
                break;
        }
        break;

    case 'view_user':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "Access denied. SuperAdmin privileges required.";
            header('Location: index.php');
            exit();
        }

        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "Invalid user ID.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }

        $superAdminController->viewUserDetails($_GET['id']);
        break;

    case 'delete_user':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "Access denied. SuperAdmin privileges required.";
            header('Location: index.php');
            exit();
        }

        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "Invalid user ID.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }

        $superAdminController->deleteUser($_GET['id']);
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

    case 'reporting':
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Please login to report users.";
            header('Location: index.php?page=login');
            exit();
        }

        switch ($action) {
            case 'submit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['reported_id']) && isset($_POST['reason'])) {
                        $reportController->submitReport($_POST['reported_id'], $_POST['reason']);
                    } else {
                        $_SESSION['error'] = "Missing required information.";
                        header('Location: index.php?page=reporting');
                    }
                }
                break;
                
            default:
                include_once __DIR__ . '/app/view/user/report.php';
                break;
        }
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
