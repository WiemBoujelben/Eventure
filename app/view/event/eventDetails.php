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
                    <p><strong>City:</strong> <?php echo htmlspecialchars($event['city']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($event['category']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Organizer:</strong> <?php echo htmlspecialchars($event['organizer_name']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($event['status']); ?></p>
                    <?php if (!empty($event['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($event['photo']); ?>" alt="Event Photo" class="img-fluid mt-3">
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($participationStatus === false): ?>
                    <form action="index.php?page=event&action=register&id=<?php echo $event['id']; ?>" method="POST" class="mt-4">
                        <button type="submit" class="btn btn-primary">Register for Event</button>
                    </form>
                <?php elseif ($participationStatus === 'pending'): ?>
                    <div class="alert alert-info mt-4">
                        Your registration request is pending approval.
                    </div>
                <?php elseif ($participationStatus === 'approved'): ?>
                    <div class="alert alert-success mt-4">
                        You are registered for this event!
                    </div>
                <?php elseif ($participationStatus === 'rejected'): ?>
                    <div class="alert alert-danger mt-4">
                        Your registration request was not approved.
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['user']['role']) && 
                        ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'superadmin') && 
                        ($_SESSION['user']['id'] === $event['supervisor_id'] || $_SESSION['user']['role'] === 'superadmin')): ?>
                    <div class="mt-4">
                        <a href="index.php?page=event&action=manageParticipants&id=<?php echo $event['id']; ?>" 
                           class="btn btn-primary">Manage Participants</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-warning mt-4">
                    Please <a href="index.php?page=login">login</a> to register for this event.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once dirname(__FILE__) . '/../shared/footer.php'; ?>
