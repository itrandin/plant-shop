<?php

require_once '../config/config.php';

if (!isLoggedIn()) {
    $_SESSION['review_error'] = 'Для удаления отзыва необходимо войти в аккаунт.';
    header('Location: ../reviews.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../reviews.php');
    exit;
}

$id_review = (int)($_POST['id_review'] ?? 0);

if ($id_review <= 0) {
    $_SESSION['review_error'] = 'Некорректный отзыв.';
    header('Location: ../reviews.php');
    exit;
}

$id_user = (int)getCurrentUserId();

if (isAdmin()) {
    $stmt = mysqli_prepare($conn, "DELETE FROM reviews WHERE id_review = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_review);
} else {
    $stmt = mysqli_prepare($conn, "DELETE FROM reviews WHERE id_review = ? AND id_user = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id_review, $id_user);
}

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['review_success'] = 'Отзыв удален.';
    } else {
        $_SESSION['review_error'] = 'Отзыв не найден или у вас нет прав на удаление.';
    }
} else {
    $_SESSION['review_error'] = 'Ошибка при удалении отзыва.';
}

header('Location: ../reviews.php');
exit;
