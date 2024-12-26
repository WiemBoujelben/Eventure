<?php
require_once __DIR__ . '/../model/superAdminModel.php';
require_once __DIR__ . '/../model/AdminRequest.php';

class SuperAdminControler {
    private $model;
    private $adminRequestModel;

    public function __construct() {
        $this->model = new SuperAdminModel();
        $this->adminRequestModel = new AdminRequest();
    }

    public function dashboard() {
        // Check if user is superadmin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "Access denied. SuperAdmin privileges required.";
            header('Location: index.php');
            exit();
        }

        // Get admin requests
        $adminRequests = $this->model->getAdminRequests();
        
        // Get list of admins
        $admins = $this->model->getAdmins();

        // Get users and reports (existing functionality)
        $users = $this->model->findAllUsers();
        $reports = $this->model->getReports();

        // Include the dashboard view
        include __DIR__ . '/../view/superadmin/dashboard.php';
    }

    public function approveRequest($userId) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "Access denied.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }
    
        if ($this->model->approveAdminRequest($userId)) {
            $_SESSION['success'] = "Admin request approved successfully.";
        } else {
            $_SESSION['error'] = "Failed to approve admin request.";
        }
    
        header('Location: index.php?page=superadmin&action=dashboard');
        exit();
    }
    
    public function rejectRequest($userId) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "Access denied.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }
    
        if ($this->model->rejectAdminRequest($userId)) {
            $_SESSION['success'] = "Admin request rejected.";
        } else {
            $_SESSION['error'] = "Failed to reject admin request.";
        }
    
        header('Location: index.php?page=superadmin&action=dashboard');
        exit();
    }

    public function viewUser($id) {
        $user = $this->model->findUser($id);
        require_once 'app/view/superAdmin/manageUsers.php';
    }

    public function deleteUser($id) {
        $this->model->deleteUser($id);
        header('Location: index.php?page=superadmin&action=dashboard');
        exit();
    }
    
    public function acceptUser($id) {
        $this->model->acceptUser($id);
        header('Location: index.php?page=superadmin&action=dashboard');
        exit();
    }

    public function viewReport($id) {
        if (isset($id)) {
            $report = $this->model->findReport($id);
            require_once "app/view/superAdmin/manageReports.php";
        } else {
            header("Location: index.php?page=superadmin&action=dashboard");
            exit();
        }
    }

    public function deleteReport($id) {
        $this->model->deleteReport($id);
        header("Location: index.php?page=superadmin&action=dashboard");
        exit();
    }

    public function removeAdminRole($userId) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['error'] = "Access denied.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }

        if ($this->model->removeAdminRole($userId)) {
            $_SESSION['success'] = "Admin role removed successfully.";
        } else {
            $_SESSION['error'] = "Failed to remove admin role.";
        }

        header('Location: index.php?page=superadmin&action=dashboard');
        exit();
    }
}