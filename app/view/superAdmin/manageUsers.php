<?php
include_once 'app/views/shared/header.php';
include_once 'app/views/shared/navbar.php';
?>

<h1>Gestion des utilisateurs</h1>
<p>Liste des utilisateurs admin.</p>

<?php if (!empty($users)): ?>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <!-- Ajoutez des boutons pour activer, désactiver, supprimer, etc. -->
                        <a href="?page=activate&id=<?php echo $user['id']; ?>">Activer</a>
                        <a href="?page=deactivate&id=<?php echo $user['id']; ?>">Désactiver</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>

<?php include_once 'app/views/shared/footer.php'; ?>
