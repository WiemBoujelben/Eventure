<?php
$baseUrl = '';  
include_once __DIR__ . '/../shared/header.php';
?>
<div class="container mt-4">
    <!-- Category Filter -->
    <div class="row mb-4">
           
    </div>

    <div class="row mb-4">
        <div class="col">
          
            <div class="d-flex justify-content-between align-items-center">
                <form action="index.php?page=<?php echo $_GET['page']; ?>&action=<?php echo $_GET['action']; ?>" method="GET" class="d-flex gap-2">
                    <input type="hidden" name="page" value="event">
                    <input type="hidden" name="action" value="list">
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
                        <a href="index.php?page=event&action=list" class="btn btn-outline-secondary">Clear Filter</a>
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
                                    <?php if (isset($_SESSION['user']) && 
                                              $_SESSION['user']['role'] === 'admin' && 
                                              $_SESSION['user']['id'] == $event['supervisor_id']): ?>
                                        <a href="index.php?page=event&action=Edit&id=<?php echo $event['id']; ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="index.php?page=event&action=Delete&id=<?php echo $event['id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this event?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    <?php endif; ?>
                                    <a href="index.php?page=event&action=details&id=<?php echo $event['id']; ?>" 
                                       class="btn btn-primary btn-sm"
                                       onclick="console.log('Clicked details for event <?php echo $event['id']; ?>'); return true;">
                                        <i class="fas fa-eye"></i> View Details
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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.card');
            
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
    }

    const dropdownItems = document.querySelectorAll('.dropdown-item');
    if (dropdownItems) {
        dropdownItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const category = this.dataset.category;
                const cards = document.querySelectorAll('[data-category]');
                
                cards.forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
});
</script>