<?php
require_once __DIR__ . '/../../config/Database.php';

class ReportController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function submitReport($reported_id, $reason) {
        try {
            if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
                $_SESSION['error'] = "You must be logged in to submit a report.";
                header('Location: index.php?page=reporting');
                exit();
            }

            // Validate reported user exists
            $checkUser = "SELECT id FROM users WHERE id = :reported_id";
            $stmt = $this->db->getConnection()->prepare($checkUser);
            $stmt->bindParam(':reported_id', $reported_id);
            $stmt->execute();

            if (!$stmt->fetch()) {
                $_SESSION['error'] = "User not found.";
                header('Location: index.php?page=reporting');
                exit();
            }

            // Check if user is trying to report themselves
            if ($reported_id == $_SESSION['user']['id']) {
                $_SESSION['error'] = "You cannot report yourself.";
                header('Location: index.php?page=reporting');
                exit();
            }

            // Insert the report
            $query = "INSERT INTO reports (reporter_id, reported_id, reason) 
                     VALUES (:reporter_id, :reported_id, :reason)";
            
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->bindParam(':reporter_id', $_SESSION['user']['id']);
            $stmt->bindParam(':reported_id', $reported_id);
            $stmt->bindParam(':reason', $reason);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Report submitted successfully.";
            } else {
                $error = $stmt->errorInfo();
                error_log("Database error: " . print_r($error, true));
                $_SESSION['error'] = "Failed to submit report. Please try again.";
            }

        } catch (PDOException $e) {
            error_log("Error submitting report: " . $e->getMessage());
            $_SESSION['error'] = "An error occurred while submitting the report.";
        }

        header('Location: index.php?page=reporting');
        exit();
    }

    public function getUsersToReport() {
        try {
            if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
                error_log("User not logged in when trying to get users to report");
                return array();
            }

            // Get all users except the current user and superadmins
            $query = "SELECT id, name, role 
                     FROM users 
                     WHERE id != :current_user_id 
                     AND role != 'superadmin'
                     ORDER BY name";
            
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->bindParam(':current_user_id', $_SESSION['user']['id']);
            
            if (!$stmt->execute()) {
                $error = $stmt->errorInfo();
                error_log("Failed to execute getUsersToReport query: " . print_r($error, true));
                return array();
            }
            
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($users)) {
                error_log("No users found to report");
            }
            
            return $users;
        } catch (PDOException $e) {
            error_log("Error getting users to report: " . $e->getMessage());
            return array();
        }
    }
}
