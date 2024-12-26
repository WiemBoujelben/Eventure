<?php
include_once dirname(__FILE__) . '/../shared/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Tableau de bord SuperAdmin</h1>

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

        <!-- Admin Requests Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Demandes d'administrateur</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Raison</th>
                            <th>Date de demande</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($adminRequests) && !empty($adminRequests)): ?>
                            <?php foreach($adminRequests as $request): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($request['id']); ?></td>
                                    <td><?php echo htmlspecialchars($request['name']); ?></td>
                                    <td><?php echo htmlspecialchars($request['email']); ?></td>
                                    <td><?php echo htmlspecialchars($request['reason']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($request['created_at'])); ?></td>
                                    <td>
                                        <a href="index.php?page=superadmin&action=approve_admin_request&user_id=<?php echo $request['user_id']; ?>" 
                                           class="btn btn-success btn-sm">Approuver</a>
                                        <a href="index.php?page=superadmin&action=reject_admin_request&user_id=<?php echo $request['user_id']; ?>" 
                                           class="btn btn-danger btn-sm">Rejeter</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucune demande d'administrateur en attente</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Users Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Liste des Utilisateurs</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($users) && !empty($users)): ?>
                            <?php foreach($users as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u->id); ?></td>
                                <td><?php echo htmlspecialchars($u->name); ?></td>
                                <td><?php echo htmlspecialchars($u->email); ?></td>
                                <td><?php echo htmlspecialchars($u->role); ?></td>
                                <td> 
                                    <a href="index.php?page=view_user&id=<?php echo $u->id; ?>" 
                                       class="btn btn-warning btn-sm">Voir détails</a>	
                                    <?php if ($u->role !== 'superadmin'): ?>
                                    <a href="index.php?page=delete_user&id=<?php echo $u->id; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucun utilisateur trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Liste des Signalements</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Signaleur</th>
                                <th>Signalé</th>
                                <th>Raisons</th>
                                <th>Nombre de Signalements</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php 
    $reports = $superAdminController->getReports();
    foreach ($reports as $report): ?>
        <tr>
            <td><?php echo htmlspecialchars($report['id']); ?></td>
            <td><?php echo htmlspecialchars($report['reporter_name']); ?></td>
            <td><?php echo htmlspecialchars($report['reported_name']); ?></td>
            <td>
                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" 
                        data-bs-target="#reasonModal<?php echo $report['id']; ?>">
                    Voir Raison
                </button>
            </td>
            <td>
                <span class="badge bg-danger">
                    <?php echo htmlspecialchars($report['report_count']); ?>
                </span>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a href="index.php?page=superadmin&action=delete_user&id=<?php echo $report['reported_id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');">
                        Supprimer Utilisateur
                    </a>
                </div>
            </td>
        </tr>
        <!-- Keep the modal code as is -->
    <?php endforeach; ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include_once dirname(__FILE__) . '/../shared/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>