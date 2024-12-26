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

    <div class="row mb-4">
        <div class="col">
            <h2>Admin Dashboard</h2>
        </div>
        <div class="col text-end">
            <a href="index.php?page=event&action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Event
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Your Events</h3>
        </div>
        <div class="card-body">
            <?php if (empty($events)): ?>
                <div class="alert alert-info">
                    You haven't created any events yet.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date & Time</th>
                                <th>City</th>
                                <th>Category</th>
                                <th>Total Participants</th>
                                <th>Pending Requests</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($event['date_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($event['city']); ?></td>
                                    <td><?php echo htmlspecialchars($event['category']); ?></td>
                                    <td><?php echo $event['participant_count']; ?></td>
                                    <td>
                                        <?php if ($event['pending_count'] > 0): ?>
                                            <span class="badge bg-warning"><?php echo $event['pending_count']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $event['status'] === 'active' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($event['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="index.php?page=event&action=details&id=<?php echo $event['id']; ?>" 
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?page=admin&action=manageParticipants&event_id=<?php echo $event['id']; ?>" 
                                               class="btn btn-sm btn-primary" title="Manage Participants">
                                                <i class="fas fa-users"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
        <!-- Admins Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Liste des Administrateurs</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($admins) && !empty($admins)): ?>
                            <?php foreach($admins as $admin): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($admin['id']); ?></td>
                                <td><?php echo htmlspecialchars($admin['name']); ?></td>
                                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($admin['created_at'])); ?></td>
                                <td>
                                    <a href="index.php?page=superadmin&action=remove_admin_role&user_id=<?php echo $admin['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir retirer le rôle d\'administrateur à cet utilisateur ?');">Retirer le rôle admin</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucun administrateur trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php include_once dirname(__FILE__) . '/../shared/footer.php'; ?>