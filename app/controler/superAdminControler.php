<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../model/superAdminModel.php';

class SuperAdminControler {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function dashboard() {
        $adminRequests = $this->getAdminRequests();
        $users = $this->getAllUsers();
        $reports = $this->getReports();
        include __DIR__ . '/../view/superadmin/dashboard.php';
    }

    public function getAdminRequests() {
        try {
            $query = "SELECT ar.*, u.name, u.email 
                     FROM admin_requests ar
                     INNER JOIN users u ON ar.user_id = u.id
                     WHERE ar.status = 'pending'
                     ORDER BY ar.created_at DESC";
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting admin requests: " . $e->getMessage());
            return array();
        }
    }

    public function getAllUsers() {
        try {
            $query = "SELECT * FROM users ORDER BY name";
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error getting all users: " . $e->getMessage());
            return array();
        }
    }

    public function getReports() {
        try {
            $query = "SELECT r.id, 
                             r.reason,
                             reporter.name as reporter_name,
                             reported.name as reported_name,
                             reported.id as reported_id,
                             COUNT(*) OVER (PARTITION BY r.reported_id) as report_count
                      FROM reports r
                      JOIN users reporter ON r.reporter_id = reporter.id
                      JOIN users reported ON r.reported_id = reported.id
                      ORDER BY r.created_at DESC";

            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting reports: " . $e->getMessage());
            return array();
        }
    }

    public function viewReport($id) {
        if (!isset($id)) {
            $_SESSION['error'] = "Invalid report ID.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }

        try {
            $query = "SELECT r.*, 
                            u1.username as reporter_name,
                            u2.username as reported_name
                     FROM reports r
                     LEFT JOIN users u1 ON r.reporter_id = u1.id
                     LEFT JOIN users u2 ON r.reported_id = u2.id
                     WHERE r.id = :id";
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $report = $stmt->fetch(PDO::FETCH_OBJ);

            if ($report) {
                require_once __DIR__ . '/../view/superadmin/manageReports.php';
            } else {
                $_SESSION['error'] = "Report not found.";
                header('Location: index.php?page=superadmin&action=dashboard');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Error viewing report: " . $e->getMessage());
            $_SESSION['error'] = "Error viewing report.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }
    }

    public function deleteReport($id) {
        try {
            $query = "DELETE FROM reports WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Report deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete report.";
            }
        } catch (PDOException $e) {
            error_log("Error deleting report: " . $e->getMessage());
            $_SESSION['error'] = "Failed to delete report.";
        }
        
        header('Location: index.php?page=superadmin&action=dashboard');
        exit();
    }

    public function approveAdminRequest() {
        if (!isset($_GET['user_id'])) {
            $_SESSION['error'] = "Invalid user ID.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }

        try {
            $this->db->getConnection()->beginTransaction();

            // Update user role to admin
            $updateUserQuery = "UPDATE users SET role = 'admin' WHERE id = :user_id";
            $stmt = $this->db->getConnection()->prepare($updateUserQuery);
            $stmt->bindParam(':user_id', $_GET['user_id']);
            $stmt->execute();

            // Update request status to approved
            $updateRequestQuery = "UPDATE admin_requests SET status = 'approved' WHERE user_id = :user_id";
            $stmt = $this->db->getConnection()->prepare($updateRequestQuery);
            $stmt->bindParam(':user_id', $_GET['user_id']);
            $stmt->execute();

            $this->db->getConnection()->commit();
            $_SESSION['success'] = "Admin request approved successfully.";
        } catch (PDOException $e) {
            $this->db->getConnection()->rollBack();
            error_log("Error approving admin request: " . $e->getMessage());
            $_SESSION['error'] = "Failed to approve admin request.";
        }

        header('Location: index.php?page=superadmin&action=dashboard');
        exit();
    }

    public function rejectAdminRequest() {
        if (!isset($_GET['user_id'])) {
            $_SESSION['error'] = "Invalid user ID.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }

        try {
            $query = "UPDATE admin_requests SET status = 'rejected' WHERE user_id = :user_id";
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->bindParam(':user_id', $_GET['user_id']);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Admin request rejected successfully.";
            } else {
                $_SESSION['error'] = "Failed to reject admin request.";
            }
        } catch (PDOException $e) {
            error_log("Error rejecting admin request: " . $e->getMessage());
            $_SESSION['error'] = "Failed to reject admin request.";
        }

        header('Location: index.php?page=superadmin&action=dashboard');
        exit();
    }

    public function viewUser() {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "Invalid user ID.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }

        try {
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if ($user) {
                require_once __DIR__ . '/../view/superadmin/user_details.php';
            } else {
                $_SESSION['error'] = "User not found.";
                header('Location: index.php?page=superadmin&action=dashboard');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Error viewing user: " . $e->getMessage());
            $_SESSION['error'] = "Error viewing user details.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }
    }

    public function deleteUser($userId) {
        try {
            error_log("Starting user deletion process for userId: $userId");
            // Check if user exists and is not a superadmin
            $checkQuery = "SELECT role FROM users WHERE id = :userId";
            error_log("Checking user existence for userId: $userId");
            $stmt = $this->db->getConnection()->prepare($checkQuery);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                error_log("User not found for userId: $userId");
                $_SESSION['error'] = "Utilisateur non trouvé.";
                return false;
            }
            
            if ($user['role'] === 'superadmin') {
                error_log("Attempted to delete superadmin userId: $userId");
                $_SESSION['error'] = "Impossible de supprimer un superadmin.";
                return false;
            }

            // Start transaction
            error_log("Starting transaction for user deletion: $userId");
            $this->db->getConnection()->beginTransaction();

            // Delete related reports first
            $deleteReports = "DELETE FROM reports WHERE reporter_id = :userId OR reported_id = :userId";
            $stmt = $this->db->getConnection()->prepare($deleteReports);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            // Delete user's events
            $deleteEvents = "DELETE FROM events WHERE supervisor_id = :userId";
            $stmt = $this->db->getConnection()->prepare($deleteEvents);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            // Delete admin requests
            $deleteRequests = "DELETE FROM admin_requests WHERE user_id = :userId";
            $stmt = $this->db->getConnection()->prepare($deleteRequests);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            // Finally delete the user
            $deleteUser = "DELETE FROM users WHERE id = :userId";
            $stmt = $this->db->getConnection()->prepare($deleteUser);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            // Commit transaction
            error_log("Committing transaction for user deletion: $userId");
            $this->db->getConnection()->commit();
            $_SESSION['success'] = "Utilisateur supprimé avec succès.";
            return true;

        } catch (PDOException $e) {
            // Rollback on error
            if ($this->db->getConnection()->inTransaction()) {
                $this->db->getConnection()->rollBack();
            }
            error_log("SQL Error: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur: " . $e->getMessage();
            return false;
        }
    }

    public function deleteUserOld($id) {
        if (!isset($id)) {
            $_SESSION['error'] = "Invalid user ID.";
            header('Location: index.php?page=superadmin&action=dashboard');
            exit();
        }

        try {
            $query = "DELETE FROM users WHERE id = :id AND role != 'superadmin'";
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $_SESSION['success'] = "User deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete user.";
            }
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            $_SESSION['error'] = "Failed to delete user.";
        }

        header('Location: index.php?page=superadmin&action=dashboard');
        exit();
    }
}