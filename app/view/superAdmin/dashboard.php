<?php
// Inclure le header et la barre de navigation
include_once 'app/views/shared/header.php';
include_once 'app/views/shared/navbar.php';
?>

<h1>Tableau de bord du SuperAdmin</h1>
<p>Bienvenue sur le tableau de bord du SuperAdmin.</p>

<h2>Utilisateurs</h2>
<!-- Afficher la liste des utilisateurs -->
<?php if (!empty($users)): ?>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user['name']); ?> - <?php echo htmlspecialchars($user['email']); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>

<h2>Rapports</h2>
<!-- Afficher la liste des rapports -->
<?php if (!empty($reports)): ?>
    <ul>
        <?php foreach ($reports as $report): ?>
            <li><?php echo htmlspecialchars($report['title']); ?> - <?php echo htmlspecialchars($report['date']); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun rapport disponible.</p>
<?php endif; ?>

<?php include_once 'app/views/shared/footer.php'; ?>
