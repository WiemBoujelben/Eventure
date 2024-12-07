<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Tableau de Bord Administrateur</h1>

    <!-- Liste des utilisateurs en attente -->
    <h2>Utilisateurs en attente</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pendingUsers as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td>
                        <a href="/admin/approve_user?id=<?= $user['id']; ?>">Approuver</a>
                        <a href="/admin/reject_user?id=<?= $user['id']; ?>">Rejeter</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Liste des événements -->
    <h2>Événements</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['title']); ?></td>
                    <td><?= htmlspecialchars($event['description']); ?></td>
                    <td><?= htmlspecialchars($event['date']); ?></td>
                    <td>
                        <a href="/admin/delete_event?id=<?= $event['id']; ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
