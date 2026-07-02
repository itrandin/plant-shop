<?php
require_once '../config/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin_users.php');
    exit;
}

$id_user = (int)($_POST['id_user'] ?? 0);
$id_role = (int)($_POST['id_role'] ?? 0);
$current_admin_id = getCurrentUserId();

if ($id_user <= 0 || $id_role <= 0) {
    $_SESSION['admin_error'] = 'Некорректные данные пользователя.';
    header('Location: ../admin_users.php');
    exit;
}

$roleCheck = mysqli_prepare($conn, "SELECT id_role FROM roles WHERE id_role = ?");
mysqli_stmt_bind_param($roleCheck, 'i', $id_role);
mysqli_stmt_execute($roleCheck);
$roleResult = mysqli_stmt_get_result($roleCheck);

if (!mysqli_fetch_assoc($roleResult)) {
    $_SESSION['admin_error'] = 'Выбранная роль не существует.';
    header('Location: ../admin_users.php');
    exit;
}

$stmt = mysqli_prepare($conn, "UPDATE users SET id_role = ? WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, 'ii', $id_role, $id_user);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['admin_success'] = 'Роль пользователя обновлена.';

    if ($id_user === $current_admin_id && $id_role !== 2) {
        logout();
        header('Location: ../login.php');
        exit;
    }
} else {
    $_SESSION['admin_error'] = 'Не удалось обновить роль пользователя.';
}

header('Location: ../admin_users.php');
exit;
