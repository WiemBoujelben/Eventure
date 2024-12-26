<?php
// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit();
}
$user = $_SESSION['user'];

include_once dirname(__FILE__) . '/../shared/header.php';
?>

<div class="container py-5">
    <h2 class="text-center mb-4">User Profile</h2>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" role="alert">
            Profile updated successfully!
        </div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                <form action="request_admin.php" method="POST">
    <button type="submit">Request Admin Status</button>
</form>
                    <div class="profile-photo-container mb-3">
                        <?php if (isset($user['photo']) && !empty($user['photo'])) : ?>
                            <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Photo" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        <?php else : ?>
                            <img src="assets/images/default-avatar.png" alt="Default Profile Photo" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <form action="index.php?page=update_profile_photo" method="POST" enctype="multipart/form-data" class="mb-3">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Photo</button>
                    </form>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Name:</label>
                            <p class="ms-2"><?php echo htmlspecialchars($user['name']); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Email:</label>
                            <p class="ms-2"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Age:</label>
                            <p class="ms-2"><?php echo isset($user['age']) ? htmlspecialchars($user['age']) : 'Not set'; ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Role:</label>
                            <p class="ms-2"><?php echo isset($user['role']) ? htmlspecialchars($user['role']) : 'User'; ?></p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="fw-bold">Rating:</label>
                            <p class="ms-2"><?php echo isset($user['rating']) ? htmlspecialchars($user['rating']) : '0'; ?>/5</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="fw-bold">Profile Details:</label>
                            <p class="ms-2"><?php echo isset($user['profile_details']) ? nl2br(htmlspecialchars($user['profile_details'])) : 'No details available'; ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="index.php?page=edit_profile" class="btn btn-primary me-2">Edit Profile</a>
                        <a href="index.php?page=logout" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once dirname(__FILE__) . '/../shared/footer.php'; ?>
