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

if ($id <= 0) {
    $_SESSION['admin_error'] = 'Некорректный идентификатор записи.';
    header('Location: ../admin_table.php?table=' . urlencode($table));
    exit;
}

try {
    $stmt = mysqli_prepare($conn, "DELETE FROM `{$table}` WHERE `{$pk}` = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $_SESSION['admin_success'] = 'Запись удалена.';
} catch (Throwable $e) {
    $_SESSION['admin_error'] = 'Ошибка удаления. Возможно, запись связана с другими таблицами.';
}

header('Location: ../admin_table.php?table=' . urlencode($table));
exit;
