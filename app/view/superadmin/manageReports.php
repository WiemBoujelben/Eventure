<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails signalement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h2>Détails du Signalement</h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($report)): ?>
                            <div class="mb-3">
                                <h5>Informations du signalement</h5>
                                <table class="table">
                                    <tr>
                                        <th width="30%">ID:</th>
                                        <td><?php echo htmlspecialchars($report->id); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Signalé par:</th>
                                        <td><?php echo htmlspecialchars($report->reporter_name); ?></td>
                                        <td>
                                            <a href="index.php?action=viewUser&id=<?php echo $report->reported_id; ?>" class="btn btn-warning btn-sm">Voir utilisateur</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Utilisateur signalé:</th>
                                        <td>
                                            <?php echo htmlspecialchars($report->reported_name); ?>
                                        </td>
                                        <td>
                                            <a href="index.php?action=viewUser&id=<?php echo $report->reported_id; ?>" class="btn btn-warning btn-sm">Voir utilisateur</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Raison:</th>
                                        <td><?php echo htmlspecialchars($report->reason); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date:</th>
                                        <td><?php echo htmlspecialchars($report->created_at); ?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="index.php?action=deleteReport&id=<?php echo $report->id; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce signalement ?');">
                                    Supprimer le signalement
                                </a>
                            </div>
                            
                            <div class="mt-3">
                                <a href="index.php?action=dashboard" class="btn btn-secondary">
                                    Retour au tableau de bord
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                Signalement non trouvé.
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