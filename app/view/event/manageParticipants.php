<?php include_once dirname(__FILE__) . '/../shared/header.php'; ?>

<div class="container mt-4">
    <h2>Manage Participants - <?php echo htmlspecialchars($event['title']); ?></h2>

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
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participants as $participant): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($participant['name']); ?></td>
                                <td><?php echo htmlspecialchars($participant['email']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($participant['registration_date'])); ?></td>
                                <td>
                                    <span class="badge <?php 
                                        echo $participant['status'] === 'approved' ? 'bg-success' : 
                                            ($participant['status'] === 'rejected' ? 'bg-danger' : 'bg-warning');
                                    ?>">
                                        <?php echo ucfirst($participant['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($participant['status'] === 'pending'): ?>
                                        <form action="index.php?page=event&action=updateParticipantStatus" method="POST" class="d-inline">
                                            <input type="hidden" name="participant_id" value="<?php echo $participant['id']; ?>">
                                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                        <form action="index.php?page=event&action=updateParticipantStatus" method="POST" class="d-inline">
                                            <input type="hidden" name="participant_id" value="<?php echo $participant['id']; ?>">
                                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($participants)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No participants yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once dirname(__FILE__) . '/../shared/footer.php'; ?>
