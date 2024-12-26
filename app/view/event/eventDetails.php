<?php include_once dirname(__FILE__) . '/../shared/header.php'; ?>

<div class="container mt-4">
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
            <h2 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h2>
            <p class="card-text"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <p><strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($event['date_time'])); ?></p>
                    <p><strong>Category:</strong> <?php echo ucfirst(htmlspecialchars($event['category'])); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['city']); ?></p>
                </div>
                <?php if (!empty($event['photo'])): ?>
                <div class="col-md-6">
                    <img src="<?php echo htmlspecialchars($event['photo']); ?>" class="img-fluid rounded" alt="Event photo">
                </div>
                <?php endif; ?>
            </div>

            <!-- Approved Participants Section -->
            <div class="mt-4">
                <h3>Approved Participants</h3>
                <?php if (!empty($approvedParticipants)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>User ID</th>
                                    <th>Registration Date</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($approvedParticipants as $participant): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($participant['name']); ?></td>
                                        <td><?php echo htmlspecialchars($participant['user_id']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($participant['registration_date'])); ?></td>
                                        <td><span class="badge bg-success"><?php echo htmlspecialchars($participant['status']); ?></span></td>
                                        <td>
                                            <div class="star-rating" data-participant-id="<?php echo $participant['user_id']; ?>">
                                                <span class="star" data-value="1">&#9733;</span>
                                                <span class="star" data-value="2">&#9733;</span>
                                                <span class="star" data-value="3">&#9733;</span>
                                                <span class="star" data-value="4">&#9733;</span>
                                                <span class="star" data-value="5">&#9733;</span>
                                            </div>
                                            <div class="rating-display">
                                                <span class="average-rating">
                                                    <?php echo number_format($participant['average_rating'], 1); ?>
                                                </span>
                                            </div>
                                            <form action="index.php?page=event&action=submitRating" method="POST">
                                                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                                <input type="hidden" name="participant_id" value="<?php echo htmlspecialchars($participant['user_id']); ?>">
                                                <input type="hidden" name="rating" class="rating-input">
                                                <button type="submit" class="btn btn-primary btn-sm mt-1">Rate</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No approved participants yet.</p>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4">
            <form action="index.php?page=event&action=register" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'admin' && $_SESSION['user']['id'] == $event['supervisor_id']): ?>
                        <a href="index.php?page=event&action=Edit&id=<?php echo $event['id']; ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Event
                        </a>
                        <a href="index.php?page=event&action=Delete&id=<?php echo $event['id']; ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to delete this event?');">
                            <i class="fas fa-trash"></i> Delete Event
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="index.php?page=events" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Events
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .star {
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.star-rating').forEach(starContainer => {
            const stars = starContainer.querySelectorAll('.star');
            const ratingInput = starContainer.parentElement.querySelector('.rating-input');
            
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const value = star.getAttribute('data-value');
                    ratingInput.value = value;
                    
                    // Highlight selected stars
                    stars.forEach(s => {
                        s.style.color = s.getAttribute('data-value') <= value ? '#FFD700' : '#000';
                    });
                });
            });
        });
    });
</script>
<?php include_once dirname(__FILE__) . '/../shared/footer.php'; ?>
