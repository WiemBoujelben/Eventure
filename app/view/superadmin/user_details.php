<?php
include_once __DIR__ . '/../shared/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Détails de l'utilisateur</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if ($user): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Informations personnelles</h2>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
                    <p><strong>Nom:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Rôle:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                    <p><strong>Date de création:</strong> <?php 
                    echo isset($user['created_at']) ? 
                        date('d/m/Y H:i', strtotime($user['created_at'])) : 
                        'Date non disponible'; 
                    ?></p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Événements de l'utilisateur</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($userEvents)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Date</th>
                                    <th>Ville</th>
                                    <th>Catégorie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($userEvents as $event): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($event['id']); ?></td>
                                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                                        <td><?php 
                                            echo isset($event['date_time']) ? 
                                                date('d/m/Y H:i', strtotime($event['date_time'])) : 
                                                'Date non disponible'; 
                                        ?></td>
                                        <td><?php echo htmlspecialchars($event['city']); ?></td>
                                        <td><?php echo htmlspecialchars($event['category']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">Aucun événement trouvé pour cet utilisateur.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-3">
                <a href="index.php?page=superadmin&action=dashboard" class="btn btn-secondary">Retour au tableau de bord</a>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                Utilisateur non trouvé.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include_once __DIR__ . '/../shared/footer.php'; ?>
