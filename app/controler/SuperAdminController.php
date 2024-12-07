<?php
class SuperAdminController {
    private $superAdmin;

    public function __construct($db) {
        $this->superAdmin = new SuperAdmin($db);
    }

    public function dashboard() {
        $users = $this->superAdmin->getAllUsers();
        $reports = $this->superAdmin->getReports();
        require_once 'app/views/superadmin/dashboard.php';
    }

    public function manageUsers() {
        $users = $this->superAdmin->getAllUsers();
        require_once 'app/views/superadmin/manage_users.php';
    }

    public function viewReports() {
        $reports = $this->superAdmin->getReports();
        require_once 'app/views/superadmin/reports.php';
    }
}
?>
