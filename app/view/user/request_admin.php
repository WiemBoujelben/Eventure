<?php
include_once __DIR__ . '/../shared/header.php';

// Get user's current request status if any
$adminRequest = new AdminRequest();
$currentRequest = $adminRequest->getUserRequest($_SESSION['user']['id']);
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Request Admin Access</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($currentRequest): ?>
                        <div class="alert alert-info">
                            <h4>Current Request Status: <?php echo ucfirst($currentRequest['status']); ?></h4>
                            <p>Submitted on: <?php echo date('F j, Y, g:i a', strtotime($currentRequest['created_at'])); ?></p>
                            <?php if ($currentRequest['status'] === 'pending'): ?>
                                <p>Your request is being reviewed by our administrators.</p>
                            <?php elseif ($currentRequest['status'] === 'rejected'): ?>
                                <p>Your previous request was rejected. You may submit a new request below.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!$currentRequest || $currentRequest['status'] !== 'pending'): ?>
                        <form action="index.php?page=request_admin&action=submit" method="POST">
                            <div class="mb-3">
                                <label for="reason" class="form-label">Why do you want to become an admin?</label>
                                <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                                <div class="form-text">Please explain why you would be a good administrator for our platform.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
