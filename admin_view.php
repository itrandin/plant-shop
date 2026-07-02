<?php
require_once 'config/config.php';
requireAdmin();

$view = $_GET['view'] ?? '';
$views = adminViews();
if (!isset($views[$view])) {
    header('Location: admin.php');
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM `{$view}` LIMIT 300");
$fields = [];
if ($result) {
    while ($field = mysqli_fetch_field($result)) {
        $fields[] = $field->name;
    }
}
include 'includes/header.php';
?>

<main>
    <section class="admin-section">
        <div class="container">
            <div class="admin-page-head">
                <div>
                    <h1><?= h($views[$view]) ?></h1>
                    <p>Представление: <?= h($view) ?></p>
                </div>
                <a class="admin-btn admin-btn-light" href="admin.php">← Назад</a>
            </div>

            <div class="admin-data-card">
                <div class="admin-table-wrap">
                    <table class="admin-data-table">
                        <thead>
                            <tr>
                                <?php foreach ($fields as $field): ?>
                                    <th><?= h($field) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <?php foreach ($fields as $field): ?>
                                            <td><?= h(mb_strimwidth((string)($row[$field] ?? ''), 0, 80, '...')) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
