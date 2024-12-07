<?php
// app/controllers/AdminController.php

require_once 'app/model/AdminModel.php';

class AdminController {
    private $adminModel;

    public function __construct() {
        $this->adminModel = new AdminModel();
    }

    // Afficher le tableau de bord
    public function dashboard() {
        $pendingUsers = $this->adminModel->getPendingUsers();
        $events = $this->adminModel->getAllEvents();
        require 'app/views/admin/dashboard.php';
    }

    // Approuver un utilisateur
    public function approveUser($userId) {
        $this->adminModel->approveUser($userId);
        header("Location: /admin/dashboard");
        exit;
    }

    // Rejeter un utilisateur
    public function rejectUser($userId) {
        $this->adminModel->rejectUser($userId);
        header("Location: /admin/dashboard");
        exit;
    }

    // Supprimer un événement
    public function deleteEvent($eventId) {
        $this->adminModel->deleteEvent($eventId);
        header("Location: /admin/dashboard");
        exit;
    }
}


