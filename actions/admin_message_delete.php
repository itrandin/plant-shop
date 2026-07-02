<?php

require_once '../config/config.php';

if (!isAdmin()) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin_messages.php');
    exit;
}

$id_message = (int)($_POST['id_message'] ?? 0);

if ($id_message <= 0) {
    $_SESSION['admin_error'] = 'Некорректное сообщение.';
    header('Location: ../admin_messages.php');
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM contact_messages WHERE id_message = ?");
mysqli_stmt_bind_param($stmt, 'i', $id_message);

if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['admin_success'] = 'Сообщение удалено.';
} else {
    $_SESSION['admin_error'] = 'Сообщение не найдено или уже удалено.';
}

header('Location: ../admin_messages.php');
exit;
