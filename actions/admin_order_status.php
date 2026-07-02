<?php
require_once '../config/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin_orders.php');
    exit;
}

$id_order = (int)($_POST['id_order'] ?? 0);
$status = trim($_POST['status'] ?? '');
$allowed = ['Новый', 'В обработке', 'Отправлен', 'Доставлен', 'Отменен'];

if ($id_order <= 0 || !in_array($status, $allowed, true)) {
    $_SESSION['admin_error'] = 'Некорректные данные для изменения статуса.';
    header('Location: ../admin_orders.php');
    exit;
}

$stmt = mysqli_prepare($conn, "UPDATE orders SET status = ? WHERE id_order = ?");
mysqli_stmt_bind_param($stmt, 'si', $status, $id_order);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['admin_success'] = 'Статус заказа #' . $id_order . ' обновлен.';
} else {
    $_SESSION['admin_error'] = 'Не удалось обновить статус заказа.';
}

header('Location: ../admin_orders.php');
exit;
