<?php
require_once '../config/config.php';
requireAdmin();

$table = $_POST['table'] ?? '';
$config = getAdminTableConfig($table);
if (!$config) {
    header('Location: ../admin.php');
    exit;
}

$pk = $config['pk'];
$id = (int)($_POST['id'] ?? 0);
$isEdit = $id > 0;

$columnsResult = mysqli_query($conn, "SHOW COLUMNS FROM `{$table}`");
$fields = [];
$values = [];

while ($column = mysqli_fetch_assoc($columnsResult)) {
    $field = $column['Field'];
    $isPk = $field === $pk;
    $isAuto = stripos($column['Extra'], 'auto_increment') !== false;

    if ($isPk || $isAuto) {
        continue;
    }

    if (!array_key_exists($field, $_POST)) {
        continue;
    }

    $value = trim((string)$_POST[$field]);

    if ($table === 'users' && $field === 'password' && $value !== '' && strpos($value, '$2y$') !== 0) {
        $value = password_hash($value, PASSWORD_DEFAULT);
    }

    if ($value === '' && stripos($column['Null'], 'YES') !== false) {
        $value = null;
    }

    $fields[] = $field;
    $values[] = $value;
}

try {
    if ($isEdit) {
        if (empty($fields)) {
            throw new Exception('Нет данных для сохранения.');
        }

        $set = implode(', ', array_map(fn($field) => "`{$field}` = ?", $fields));
        $sql = "UPDATE `{$table}` SET {$set} WHERE `{$pk}` = ?";
        $stmt = mysqli_prepare($conn, $sql);

        $types = str_repeat('s', count($values)) . 'i';
        $values[] = $id;
        mysqli_stmt_bind_param($stmt, $types, ...$values);
        mysqli_stmt_execute($stmt);
    } else {
        if (empty($fields)) {
            throw new Exception('Нет данных для добавления.');
        }

        $fieldList = implode(', ', array_map(fn($field) => "`{$field}`", $fields));
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $sql = "INSERT INTO `{$table}` ({$fieldList}) VALUES ({$placeholders})";
        $stmt = mysqli_prepare($conn, $sql);

        $types = str_repeat('s', count($values));
        mysqli_stmt_bind_param($stmt, $types, ...$values);
        mysqli_stmt_execute($stmt);
    }

    $_SESSION['admin_success'] = 'Данные успешно сохранены.';
} catch (Throwable $e) {
    $_SESSION['admin_error'] = 'Ошибка сохранения: ' . $e->getMessage();
}

header('Location: ../admin_table.php?table=' . urlencode($table));
exit;
