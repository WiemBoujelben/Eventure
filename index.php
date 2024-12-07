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

// Check if 'page' is set in the URL query parameters and route accordingly
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

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

    case 'logout':
        session_destroy();
        header('Location: index.php?page=login');
        break;

    default:
        $userController->login();
        break;
}
?>
