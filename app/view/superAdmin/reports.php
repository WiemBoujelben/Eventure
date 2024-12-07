<?php
include_once 'app/view/shared/header.php';
include_once 'app/view/shared/navbar.php';
?>

<h1>Rapports</h1>
<p>Liste des rapports disponibles.</p>

<?php if (!empty($reports)): ?>
    <ul>
        <?php foreach ($reports as $report): ?>
            <li><?php echo htmlspecialchars($report['title']); ?> - <?php echo htmlspecialchars($report['date']); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun rapport disponible.</p>
<?php endif; ?>

<?php include_once 'app/view/shared/footer.php'; ?>
