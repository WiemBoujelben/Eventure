<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h2>Détails de l'utilisateur</h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($user)): ?>
                            <div class="mb-3">
                                <h5>Informations personnelles</h5>
                                <table class="table">
                                    <tr>
                                        <th width="30%">ID:</th>
                                        <td><?php echo htmlspecialchars($user->id); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nom:</th>
                                        <td><?php echo htmlspecialchars($user->name); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?php echo htmlspecialchars($user->email); ?></td>
                                    </tr>                              
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="index.php?action=acceptUser&id=<?php echo $user->id; ?>" 
                                   class="btn btn-success">
                                    Accepter
                                </a>
                                <a href="index.php?action=deleteUser&id=<?php echo $user->id; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                    Supprimer
                                </a>
                            </div>
                            
                            <div class="mt-3">
                                <a href="index.php?action=dashboard" class="btn btn-secondary">
                                    Retour au tableau de bord
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                Utilisateur non trouvé.
                                <a href="index.php?action=dashboard" class="btn btn-secondary mt-2">
                                    Retour au tableau de bord
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>