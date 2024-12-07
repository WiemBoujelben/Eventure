<?php
// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit();
}
$user = $_SESSION['user'];

include_once dirname(__FILE__) . '/../shared/header.php';
?>

<div class="form-container">
    <h2 class="text-center mb-4">User Profile</h2>
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="fw-bold">Name:</label>
                <p class="ms-2"><?php echo htmlspecialchars($user['name']); ?></p>
            </div>
            <div class="mb-3">
                <label class="fw-bold">Email:</label>
                <p class="ms-2"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <div class="d-grid gap-2">
                <a href="index.php?page=logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>

<?php include_once dirname(__FILE__) . '/../shared/footer.php'; ?>
