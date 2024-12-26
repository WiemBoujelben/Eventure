<?php
$baseUrl = '';  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List - Eventure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .event-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
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
                        <a class="nav-link active" href="index.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?action=Create">Create Event</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

                   
                </div>
    <div class="container mt-4">
        <!-- Category Filter -->
        <div class="row mb-4">
           
        </div>

        <div class="row mb-4">
            <div class="col">
              
                <div class="d-flex justify-content-between align-items-center">
                    <form action="index.php" method="GET" class="d-flex gap-2">
                        <div class="input-group">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>" 
                                            <?php echo isset($_GET['category']) && $_GET['category'] === $cat ? 'selected' : ''; ?>>
                                        <?php echo ucfirst(htmlspecialchars($cat)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                        <?php if(isset($_GET['category']) && $_GET['category']): ?>
                            <a href="index.php" class="btn btn-outline-secondary">Clear Filter</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row" id="eventsList">
            <?php if (!empty($events)): ?>
                <?php foreach($events as $event): ?>
                    <div class="col-md-4 mb-4" data-category="<?php echo htmlspecialchars($event['category']); ?>">
                        <div class="card h-100">
                            <img src="<?php echo $baseUrl . '/' . htmlspecialchars($event['photo']); ?>" 
                                 class="card-img-top event-image" 
                                 alt="<?php echo htmlspecialchars($event['title']); ?>">
                            
                            <?php if ($event['status'] === 'pending'): ?>
                                <span class="badge bg-warning status-badge">Pending</span>
                            <?php elseif ($event['status'] === 'cancelled'): ?>
                                <span class="badge bg-danger status-badge">Cancelled</span>
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['city']); ?>
                                    </small>
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> 
                                        <?php echo date('F j, Y, g:i a', strtotime($event['date_time'])); ?>
                                    </small>
                                </p>
                                <p class="card-text"><?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?></p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-primary">
                                        <?php echo ucfirst(htmlspecialchars($event['category'])); ?>
                                    </span>
                                    <div>
                                        <a href="index.php?action=Edit&id=<?php echo $event['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="index.php?action=Delete&id=<?php echo $event['id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this event?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col">
                    <div class="alert alert-info">No events found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.event-card');
            
            cards.forEach(card => {
                const parent = card.parentElement;
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const description = card.querySelector('.card-text').textContent.toLowerCase();
                const city = card.querySelector('.fa-map-marker-alt').nextSibling.textContent.toLowerCase();
                
                if (title.includes(searchTerm) || 
                    description.includes(searchTerm) || 
                    city.includes(searchTerm)) {
                    parent.style.display = '';
                } else {
                    parent.style.display = 'none';
                }
            });
        });

        document.getElementById('categoryFilter').addEventListener('click', function(e) {
            if (e.target.classList.contains('dropdown-item')) {
                const category = e.target.dataset.category;
                const cards = document.querySelectorAll('[data-category]');
                
                cards.forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        });

        function deleteEvent(eventId) {
            if (confirm('Are you sure you want to delete this event?')) {
                window.location.href = `index.php?action=Delete&id=${eventId}`;
            }
        }
    </script>
</body>
</html>