<?php
require_once 'config/config.php';
requireAdmin();

$table = $_GET['table'] ?? '';
$config = getAdminTableConfig($table);

if (!$config) {
    header('Location: admin.php');
    exit;
}

$pk = $config['pk'];
$columnsResult = mysqli_query($conn, "SHOW COLUMNS FROM `{$table}`");
$columns = [];
while ($column = mysqli_fetch_assoc($columnsResult)) {
    $columns[] = $column;
}

$rowsResult = mysqli_query($conn, "SELECT * FROM `{$table}` ORDER BY `{$pk}` DESC LIMIT 200");
include 'includes/header.php';
?>

<main>
    <section class="admin-section">
        <div class="container">
            <div class="admin-page-head">
                <div>
                    <h1><?= h($config['title']) ?></h1>
                    <p>Таблица: <?= h($table) ?></p>
                </div>
                <div class="admin-head-actions">
                    <a class="admin-btn admin-btn-light" href="admin.php">← Назад</a>
                    <a class="admin-btn" href="admin_edit.php?table=<?= h($table) ?>">Добавить запись</a>
                </div>
            </div>

            <?php if (!empty($_SESSION['admin_error'])): ?>
                <div class="admin-message admin-message-error"><?= h($_SESSION['admin_error']) ?></div>
                <?php unset($_SESSION['admin_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['admin_success'])): ?>
                <div class="admin-message"><?= h($_SESSION['admin_success']) ?></div>
                <?php unset($_SESSION['admin_success']); ?>
            <?php endif; ?>

            <div class="admin-data-card">
                <div class="admin-table-wrap">
                    <table class="admin-data-table">
                        <thead>
                            <tr>
                                <?php foreach ($columns as $column): ?>
                                    <th><?= h($column['Field']) ?></th>
                                <?php endforeach; ?>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($rowsResult)): ?>
                                <tr>
                                    <?php foreach ($columns as $column): ?>
                                        <?php $field = $column['Field']; ?>
                                        <td><?= h(mb_strimwidth((string)($row[$field] ?? ''), 0, 80, '...')) ?></td>
                                    <?php endforeach; ?>
                                    <td class="admin-row-actions">
                                        <a href="admin_edit.php?table=<?= h($table) ?>&id=<?= (int)$row[$pk] ?>">Изменить</a>
                                        <form action="actions/admin_delete.php" method="POST" onsubmit="return confirm('Удалить запись?');">
                                            <input type="hidden" name="table" value="<?= h($table) ?>">
                                            <input type="hidden" name="id" value="<?= (int)$row[$pk] ?>">
                                            <button type="submit">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
