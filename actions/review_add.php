<?php
require_once '../config/config.php';

if (!isLoggedIn()) {
    $_SESSION['review_error'] = 'Чтобы оставить отзыв, необходимо авторизоваться.';
    header('Location: ../reviews.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../reviews.php');
    exit;
}

$id_user = (int)getCurrentUserId();
$id_plant = (int)($_POST['id_plant'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if ($id_plant <= 0 || $rating < 1 || $rating > 5 || $comment === '') {
    $_SESSION['review_error'] = 'Заполните все поля отзыва корректно.';
    header('Location: ../reviews.php');
    exit;
}

$allowedStmt = mysqli_prepare($conn, "
    SELECT COUNT(*) AS total
    FROM orders o
    INNER JOIN order_items oi ON oi.id_order = o.id_order
    WHERE o.id_user = ?
      AND o.status = 'Доставлен'
      AND oi.id_plant = ?
");
mysqli_stmt_bind_param($allowedStmt, "ii", $id_user, $id_plant);
mysqli_stmt_execute($allowedStmt);
$allowedResult = mysqli_stmt_get_result($allowedStmt);
$allowed = mysqli_fetch_assoc($allowedResult);

if (!$allowed || (int)$allowed['total'] === 0) {
    $_SESSION['review_error'] = 'Отзыв можно оставить только на растение из доставленного заказа.';
    header('Location: ../reviews.php');
    exit;
}

$checkStmt = mysqli_prepare($conn, "
    SELECT id_review
    FROM reviews
    WHERE id_user = ? AND id_plant = ?
    LIMIT 1
");
mysqli_stmt_bind_param($checkStmt, "ii", $id_user, $id_plant);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (mysqli_fetch_assoc($checkResult)) {
    $_SESSION['review_error'] = 'Вы уже оставили отзыв на это растение.';
    header('Location: ../reviews.php');
    exit;
}

$insertStmt = mysqli_prepare($conn, "
    INSERT INTO reviews (id_user, id_plant, rating, comment, review_date)
    VALUES (?, ?, ?, ?, NOW())
");
mysqli_stmt_bind_param($insertStmt, "iiis", $id_user, $id_plant, $rating, $comment);

if (mysqli_stmt_execute($insertStmt)) {
    $_SESSION['review_success'] = 'Спасибо! Ваш отзыв успешно опубликован.';
} else {
    $_SESSION['review_error'] = 'Не удалось сохранить отзыв. Попробуйте еще раз.';
}

header('Location: ../reviews.php');
exit;
