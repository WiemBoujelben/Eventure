<?php 
// Define base path ............
define('BASE_PATH', __DIR__);

// Start session
session_start();

// Include necessary files
require_once BASE_PATH . '/config/Database.php';
require_once BASE_PATH . '/app/model/User.php';
require_once BASE_PATH . '/app/controler/Usercontroler.php';

// Initialize controller
$userController = new UserController();

// Get the page parameter
$page = $_GET['page'] ?? 'login'; // Default to login if no page is specified

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

    case 'events':
        include 'app/view/event/list.php'; // Load the events list
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?page=login');
        break;

    case 'request_admin':
        include 'app/view/user/request_admin.php'; // Load the request admin view
        break;
    default:
        $userController->login();
        break;
}
?>
