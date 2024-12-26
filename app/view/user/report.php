<?php
include_once dirname(__FILE__) . '/../shared/header.php';

// Get list of users that can be reported
$users = $reportController->getUsersToReport();

// Debug information
if (empty($users)) {
    error_log("No users returned from getUsersToReport");
    error_log("Current user ID: " . (isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'Not set'));
}
?>

<div class="container mt-4">
    <h2>Report a User</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="index.php?page=reporting&action=submit" method="POST">
                <div class="mb-3">
                    <label for="reported_id" class="form-label">Select User to Report</label>
                    <select class="form-select" id="reported_id" name="reported_id" required>
                        <option value="">Select a user...</option>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <?php echo htmlspecialchars($user['name']); ?> 
                                    (<?php echo htmlspecialchars($user['role']); ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No users available to report</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason for Report</label>
                    <textarea class="form-control" id="reason" name="reason" rows="4" required 
                              placeholder="Please provide detailed information about why you are reporting this user..."></textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once dirname(__FILE__) . '/../shared/footer.php'; ?>