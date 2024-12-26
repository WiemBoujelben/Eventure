<?php
$baseUrl = '';  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Eventure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Eventure</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=Create">Create Event</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Edit Event</h3>
                    </div>
                    <div class="card-body">
                        <form action="index.php?action=Edit&id=<?php echo $event['id']; ?>" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Event Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <option value="cycling" <?php echo $event['category'] === 'cycling' ? 'selected' : ''; ?>>Cycling</option>
                                    <option value="walking" <?php echo $event['category'] === 'walking' ? 'selected' : ''; ?>>Walking</option>
                                    <option value="running" <?php echo $event['category'] === 'running' ? 'selected' : ''; ?>>Running</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">City and Address</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($event['city']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="date_time" class="form-label">Date and Time</label>
                                <input type="datetime-local" class="form-control" id="date_time" name="date_time" 
                                       value="<?php echo date('Y-m-d\TH:i', strtotime($event['date_time'])); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" <?php echo $event['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="approved" <?php echo $event['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="canceled" <?php echo $event['status'] === 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="photo" class="form-label">Event Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                <div class="mt-2">
                                    <p class="form-text">Current photo:</p>
                                    <img src="<?php echo htmlspecialchars($event['photo']); ?>" 
                                         alt="Current event photo" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px;"
                                         onerror="this.src='public/images/placeholder.jpg'">
                                </div>
                                <div id="photoHelp" class="form-text">Leave empty to keep current photo</div>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Event</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview for new photo
        document.getElementById('photo').addEventListener('change', function(e) {
            const preview = document.querySelector('img.img-thumbnail');
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
