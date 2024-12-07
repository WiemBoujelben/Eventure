<?php 

include_once dirname(__FILE__) . '/../shared/header.php';
?>

<div class="form-container">
    <h2 class="text-center mb-4">Register</h2>
    <form method="POST" action="/index.php?page=register">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="/index.php?page=login">Login here</a></p>
        </div>
    </form>
</div>

<?php include_once dirname(__FILE__) . '/../shared/footer.php'; ?>