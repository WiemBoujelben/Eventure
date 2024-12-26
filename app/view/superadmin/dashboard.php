<?php
include_once __DIR__ . '/../shared/header.php';
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
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

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
                                    <a href="index.php?page=superadmin&action=viewUser&id=<?php echo $u->id; ?>" 
                                       class="btn btn-warning btn-sm">Voir détails</a>	
                                    <?php if ($u->role !== 'superadmin'): ?>
                                    <a href="index.php?page=superadmin&action=deleteUser&id=<?php echo $u->id; ?>" 
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
        <div class="card">
            <div class="card-header">
                <h2>Liste des Signalements</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Signaleur</th>
                            <th>Signalé</th>
                            <th>Raisons</th>
                            <th>Status</th>
                            <th>Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($reports) && !empty($reports)): ?>
                            <?php foreach($reports as $r): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($r->id); ?></td>
                                <td><?php echo htmlspecialchars($r->reporter_id); ?></td>
                                <td><?php echo htmlspecialchars($r->reported_id); ?></td>
                                <td><?php echo htmlspecialchars($r->reason); ?></td>
                                <td><?php echo htmlspecialchars($r->status); ?></td>
                                <td> 
                                    <a href="index.php?page=superadmin&action=viewReport&id=<?php echo $r->id; ?>" 
                                       class="btn btn-warning btn-sm">Voir détails</a>
                                    <a href="index.php?page=superadmin&action=deleteReport&id=<?php echo $r->id; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce signalement ?');">Supprimer</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucun signalement trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>