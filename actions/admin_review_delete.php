<?php
require_once '../config/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin_reviews.php');
    exit;
}

$id_review = (int)($_POST['id_review'] ?? 0);

if ($id_review <= 0) {
    $_SESSION['admin_error'] = 'Некорректный отзыв.';
    header('Location: ../admin_reviews.php');
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM reviews WHERE id_review = ?");
mysqli_stmt_bind_param($stmt, 'i', $id_review);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['admin_success'] = 'Отзыв удален.';
} else {
    $_SESSION['admin_error'] = 'Не удалось удалить отзыв.';
}

header('Location: ../admin_reviews.php');
exit;
