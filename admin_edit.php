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
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;

$columnsResult = mysqli_query($conn, "SHOW COLUMNS FROM `{$table}`");
$columns = [];
while ($column = mysqli_fetch_assoc($columnsResult)) {
    $columns[] = $column;
}

$row = [];
if ($isEdit) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM `{$table}` WHERE `{$pk}` = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if (!$row) {
        header('Location: admin_table.php?table=' . urlencode($table));
        exit;
    }
}

include 'includes/header.php';
?>

<main>
    <section class="admin-section">
        <div class="container">
            <div class="admin-page-head">
                <div>
                    <h1><?= $isEdit ? 'Изменить запись' : 'Добавить запись' ?></h1>
                    <p>Таблица: <?= h($config['title']) ?></p>
                </div>
                <a class="admin-btn admin-btn-light" href="admin_table.php?table=<?= h($table) ?>">← Назад</a>
            </div>

            <form class="admin-form-card" action="actions/admin_save.php" method="POST">
                <input type="hidden" name="table" value="<?= h($table) ?>">
                <input type="hidden" name="id" value="<?= (int)$id ?>">

                <div class="admin-form-grid">
                    <?php foreach ($columns as $column): ?>
                        <?php
                            $field = $column['Field'];
                            $isPk = $field === $pk;
                            $isAuto = stripos($column['Extra'], 'auto_increment') !== false;
                            if (!$isEdit && ($isPk || $isAuto)) {
                                continue;
                            }
                            $value = $row[$field] ?? '';
                            $readonly = $isPk ? 'readonly' : '';
                            $type = 'text';
                            if (preg_match('/int|decimal|float|double/i', $column['Type'])) $type = 'number';
                            if (preg_match('/date|time/i', $column['Type'])) $type = 'datetime-local';
                        ?>
                        <label>
                            <span><?= h($field) ?></span>
                            <?php if (preg_match('/text/i', $column['Type'])): ?>
                                <textarea name="<?= h($field) ?>" <?= $readonly ?>><?= h($value) ?></textarea>
                            <?php else: ?>
                                <input type="<?= $type ?>" name="<?= h($field) ?>" value="<?= h($value) ?>" <?= $readonly ?>>
                            <?php endif; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <button class="admin-btn" type="submit">Сохранить</button>
            </form>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
